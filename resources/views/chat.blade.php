<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-name" content="{{ Auth::user()->name }}">
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<body class="bg-mainbackground bg-cover overflow-hidden">
    <!-- Chat -->
    <div class="ChatContainer bg-brownbgcolor h-screen bg-opacity-80 backdrop-blur-md flex flex-col">

        <!-- ChatHeader -->
        <h1 class="TopBar text-white text-center w-full bg-maroonbgcolor text-2xl py-2 relative">

            <!-- Go back button -->
            <a href="{{ Auth::check() ? (Auth::user()->isEmployee() ? route('employeeui.assistcustomer') : route('faq')) : '#' }}" class="GoBackButton absolute top-0.5 right-2 group">
                <img class="BackButton h-8 w-8 rounded-lg p-1 translate-y-2" src="../img/gobackbutton.svg">
            </a>

            @php
            echo "You're talking with " . $recipient->name;
            @endphp
        </h1>

        <!-- ChatBody -->
        <div id="chat-container" class="flex flex-col h-screen overflow-y-auto">
            <ul id="messages" class="flex-1 overflow-y-auto flex flex-col p-4">
                <!-- Messages will be loaded here dynamically -->
            </ul>
            
            <div class="p-4 flex items-center">
                <form id="message-form" class="flex-grow">
                    <input type="text" id="message-input" class="w-full rounded-lg" placeholder="Type your message">
                </form>

                <!-- Button for employees only -->
                @if(Auth::check() && Auth::user()->isEmployee())
                    <button id="employeeActionButton" class="bg-maroonbgcolor text-white rounded-lg px-4 py-2 ml-2 hover:bg-white hover:text-maroonbgcolor transition duration-300 ease-in-out">
                        Terminate Session
                    </button>
                @endif
            </div>
            
        </div>
        
    <script>
        window.currentUserId = {{ Auth::user()->user_id }};

// chat js
document.addEventListener('DOMContentLoaded', function () {
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const messageList = document.getElementById('messages');
    const currentUserId = window.currentUserId;

    // Function to load message history from sessionStorage
    function loadMessageHistory() {
        const messageHistory = JSON.parse(sessionStorage.getItem('messageHistory')) || [];
        messageHistory.forEach(message => {
            const messageElement = document.createElement('li');
            messageElement.className = 'mb-2 flex';
            const isSender = message.senderId === currentUserId;
            messageElement.innerHTML = `
                <div class="${isSender ? 'ml-auto text-right' : 'mr-auto text-left'}">
                    <strong class="text-white">${message.user} (${message.date})</strong><br>
                    <span class="bg-maroonbgcolor text-white p-2 rounded-lg inline-block w-auto h-auto">${message.content}</span>
                </div>
            `;
            messageList.appendChild(messageElement);
        });
        // Scroll to the bottom to show the latest message
        messageList.scrollTop = messageList.scrollHeight;
    }

    // Load message history on page load
    loadMessageHistory();

    // Listen for real-time messages
    window.Echo.private('chat')
    .listen('MessageSent', (e) => {
        const messageContent = e.message || 'No Content';
        const messageDate = normalizeDate(e.date) || 'Unknown Date'; // Normalize the date
        const senderId = e.senderId || null;

        // Create a new message element
        const messageElement = document.createElement('li');
        messageElement.className = 'mb-2 flex';
        
        const isSender = senderId === currentUserId;
        messageElement.innerHTML = `
            <div class="${isSender ? 'ml-auto text-right' : 'mr-auto text-left'}">
                <strong class="text-white">${e.user} (${messageDate})</strong><br>
                <span class="bg-maroonbgcolor text-white p-2 rounded-lg inline-block w-auto h-auto">${messageContent}</span>
            </div>
        `;
        
        // Append the new message at the bottom
        messageList.appendChild(messageElement);

        // Scroll to the bottom to show the latest message
        messageList.scrollTop = messageList.scrollHeight;

        // Save the message to sessionStorage
        saveMessageToHistory(e.user, messageContent, messageDate, senderId);
    });

    messageForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        const message = messageInput.value;

        fetch('/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                message: message
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`HTTP error! Status: ${response.status}. Response: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Message sent:', data); // Debug output
            messageInput.value = ''; // Clear the input after sending
            
            // Save the sent message to sessionStorage
            saveMessageToHistory(data.user, message, normalizeDate(data.date), data.senderId);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Function to save message to sessionStorage
    function saveMessageToHistory(user, content, date, senderId) {
        const messageHistory = JSON.parse(sessionStorage.getItem('messageHistory')) || [];
        
        // Check if the message is already saved
        const isDuplicate = messageHistory.some(msg => 
            msg.content === content && 
            msg.senderId === senderId
        );

        // Save to sessionStorage only if it's not a duplicate
        if (!isDuplicate) {
            messageHistory.push({ user, content, date, senderId });
            sessionStorage.setItem('messageHistory', JSON.stringify(messageHistory));
        } else {
            console.log('Duplicate message not saved:', { user, content, date, senderId });
        }
    }

    // Function to normalize date format
    function normalizeDate(dateString) {
        // Parse the date string and convert it to a standard format
        const date = new Date(dateString);
        return date.toISOString().slice(0, 19).replace('T', ' '); // e.g., '2024-09-25 11:34:56'
    }
});

// localStorage.removeItem('messageHistory');

    </script>

    </div>
</body>
</html>
