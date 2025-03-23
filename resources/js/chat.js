import moment from "moment-timezone"; // Make sure to import moment-timezone

document.addEventListener("DOMContentLoaded", function () {
    const messageInput = document.getElementById("message-input");
    const messageForm = document.getElementById("message-form");
    const messageList = document.getElementById("messages");
    const conversationId = document
        .querySelector('meta[name="conversation-id"]')
        .getAttribute("content");
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const userId = parseInt(
        document.querySelector('meta[name="user-id"]').getAttribute("content")
    );

    // Ensure the conversation ID exists
    if (!conversationId) {
        console.error("Conversation ID is not defined!");
    } else {
        // Listen for new messages in the private channel using Echo
        window.Echo.private("chat." + conversationId).listen(
            "MessageSent",
            (event) => {
                console.log("Received event:", event); // Log the entire event object
                console.log("Message content:", event.message); // Log message content

                const chatContainer = document.getElementById("chat-container");

                // Check if the message is an image
                if (event.message.type === "image") {
                    const messageContainer = document.createElement("div");
                    messageContainer.classList.add("message");

                    const image = document.createElement("img");
                    image.src = event.message; // QR code image URL

                    // Handle error if the image fails to load
                    image.onerror = function () {
                        console.error("Failed to load image:", event.message);
                        image.src = "fallback_image_url.png"; // Optional fallback image
                    };

                    messageContainer.appendChild(image);
                    chatContainer.appendChild(messageContainer);
                } else {
                    // For text messages, log and process them
                    const newMessageElement = document.createElement("li");
                    newMessageElement.classList.add(
                        "mb-2",
                        "flex",
                        event.senderId === userId ? "text-right" : "text-left",
                        event.senderId === userId ? "ml-auto" : "mr-auto"
                    );

                    // Convert event.date from UTC to Asia/Manila timezone
                    const messageTime = moment
                        .utc(event.date)
                        .tz("Asia/Manila");
                    const isOlderThanDay = messageTime.isBefore(
                        moment().subtract(1, "day")
                    );
                    const formattedDate = isOlderThanDay
                        ? messageTime.format("MMM D YYYY")
                        : "";
                    const formattedTime = messageTime.format("hh:mm A");

                    // Log message content before rendering
                    console.log("Message content to display:", event.message);

                    // Display message content safely
                    newMessageElement.innerHTML = `
                    <div>
                        <strong class="text-white">${event.user.first_name} ${
                        event.user.last_name
                    } ${
                        formattedDate ? `(${formattedDate})` : ""
                    } (<span class="text-white">${formattedTime}</span>)</strong><br>
                        <span class="bg-accent text-white p-2 rounded-lg inline-block">${
                            event.message || "No content available"
                        }</span>
                    </div>
                    `;

                    messageList.appendChild(newMessageElement);
                }

                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        );
    }

    messageForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const message = messageInput.value.trim();

        if (message) {
            fetch("/send-message", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    message: message,
                    convoID: conversationId,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        messageInput.value = "";
                    } else if (data.error) {
                        console.error(data.error);
                    }
                })
                .catch((error) =>
                    console.error("Error sending message:", error)
                );
        }
    });

    function fetchMessages(convoID) {
        fetch(`/messages/${convoID}`)
            .then((response) => response.json())
            .then((data) => {
                if (data.messages && data.messages.length) {
                    messageList.innerHTML = ""; // Clear existing messages

                    data.messages.forEach((message) => {
                        const newMessage = document.createElement("li");
                        newMessage.className =
                            message.user_id === userId
                                ? "text-right ml-auto"
                                : "text-left mr-auto";

                        const messageTime = moment
                            .utc(message.messDate)
                            .tz("Asia/Manila");

                        const isOlderThanDay = messageTime.isBefore(
                            moment().subtract(1, "day")
                        );

                        const formattedDate = isOlderThanDay
                            ? messageTime.format("MMM D YYYY")
                            : "";
                        const formattedTime = messageTime.format("hh:mm A");

                        // Check if the message content is a valid image URL
                        let messageContent = "";

                        try {
                            // Check if the URL is valid and contains '/storage/orders/qrcodes/'
                            const url = new URL(message.messContent);
                            if (
                                url.pathname.includes(
                                    "/storage/orders/qrcodes/"
                                )
                            ) {
                                // If it's a QR Code image, display the image
                                messageContent = `<img src="${message.messContent}" alt="QR Code" class="max-w-xs mx-auto mt-2">`;
                            } else {
                                // If it's a regular text message, display the text
                                messageContent = `<span class="bg-accent text-white p-2 rounded-lg inline-block">${message.messContent}</span>`;
                            }
                        } catch (error) {
                            // If the content is not a valid URL, treat it as text
                            messageContent = `<span class="bg-accent text-white p-2 rounded-lg inline-block">${message.messContent}</span>`;
                        }

                        newMessage.innerHTML = `
                        <div>
                            <strong class="text-white">${
                                message.user.first_name
                            } ${message.user.last_name} ${
                            formattedDate ? `(${formattedDate})` : ""
                        } (<span class="text-white">${formattedTime}</span>)</strong><br>
                            ${messageContent}
                        </div>
                    `;

                        messageList.appendChild(newMessage);
                    });

                    setTimeout(() => {
                        scrollToBottom();
                    }, 100);
                }
            })
            .catch((error) => {
                console.error("Error fetching messages:", error);
            });
    }

    function scrollToBottom() {
        const messageList = document.getElementById("messages");
        if (messageList) {
            messageList.scrollTop = messageList.scrollHeight;
        }
    }

    fetchMessages(conversationId);
});
