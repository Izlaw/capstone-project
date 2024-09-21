document.addEventListener('DOMContentLoaded', function () {
    const conversationList = document.getElementById('conversation-list');

    conversationList.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default link behavior

        const target = event.target.closest('li[data-conversation-id]');
        if (target) {
            const recipientId = target.getAttribute('data-conversation-id');
            if (recipientId) {
                // Construct the URL using the chat.recipient route
                const chatUrl = `{{ route('chat.recipient', ':recipient') }}`.replace(':recipient', recipientId);
                window.location.href = chatUrl; // Redirect to the chat page with the recipient's user_id
            } else {
                console.error('Recipient ID is undefined.');
            }
        }
    });

    // Fetch and update messages when loading the chat page
    const currentUrl = window.location.pathname;
    const match = currentUrl.match(/\/chat\/(\d+)/);
    if (match) {
        const recipientId = match[1];
        fetchMessages(recipientId);
    }

    window.Echo.private('chat')
        .listen('MessageSent', (event) => {
            console.log('Message received:', event);

            const conversationList = document.getElementById('conversation-list');
            const messageContent = event.message;
            const userName = event.user;
            const userId = event.user_id; // Assuming this is part of the event

            console.log(`Message Content: ${messageContent}`);
            console.log(`User Name: ${userName}`);

            // Find the existing conversation item based on the user ID
            const existingItem = Array.from(conversationList.children).find(item => 
                item.querySelector('p.font-semibold').textContent === userName
            );

            if (existingItem) {
                // Update existing conversation item
                const messageParagraph = existingItem.querySelector('p:last-of-type');
                if (messageParagraph) {
                    messageParagraph.textContent = messageContent || 'No content'; // Update message content
                } else {
                    console.error('Message paragraph not found in existing item.');
                }
            } else {
                // Create a new list item for the new message
                const newConversationItem = document.createElement('li');
                newConversationItem.classList.add('mb-4');
                newConversationItem.dataset.conversationId = userId; // Use the user_id from the event
                newConversationItem.innerHTML = `
                    <a href="#" class="block p-4 bg-gray-100 rounded-lg shadow">
                        <p class="font-semibold">${userName}</p>
                        <p>${messageContent || 'No content'}</p>
                    </a>
                `;

                // Prepend the new conversation to the conversation list
                conversationList.prepend(newConversationItem);
            }
        });

    function fetchMessages(conversationId) {
        axios.get(`/api/conversations/${conversationId}/messages`
