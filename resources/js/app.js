import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    // load header.html file
            // get the header element
            const header = document.getElementById('header');
            // set the header's innerHTML to the loaded HTML

            // call and assign SchoolUniform, SportsWear, TShirts, Curtains and Accessories elements
            const schoolUniform = document.querySelector('.SchoolUniform');
            const sportsWear = document.querySelector('.SportsWear');
            const tshirts = document.querySelector('.TShirts');
            const curtains = document.querySelector('.Curtains');
            const accessories = document.querySelector('.Accessories');

            // call and assign SchoolUniformOptionsOuter, SportsWearOptionsOuter, TShirtsOptionsOuter, CurtainsOptionsOuter, and AccessoriesOptionsOuter elements
            const schoolUniformOptions = document.querySelector('.SchoolUniformOptionsOuter');
            const sportsWearOptions = document.querySelector('.SportsWearOptionsOuter');
            const tshirtsOptions = document.querySelector('.TShirtsOptionsOuter');
            const curtainsOptions = document.querySelector('.CurtainsOptionsOuter');
            const accessoriesOptions = document.querySelector('.AccessoriesOptionsOuter');

            // hide SportsWearOptionsOuter, TShirtsOptionsOuter, CurtainOptionsOuter, and AccessoriesOptionsOuter elements by default
            sportsWearOptions.classList.add('hidden');
            tshirtsOptions.classList.add('hidden');
            curtainsOptions.classList.add('hidden');
            accessoriesOptions.classList.add('hidden');

            // add event listeners to SchoolUniform, SportsWear, TShirts, Curtains, and Accessories elements

            schoolUniform.addEventListener('click', function() {
                // hide SportsWearOptionsOuter, TShirtsOptionsOuter, CurtainsOptionsOuter, and AccessoriesOptionsOuter elements
                sportsWearOptions.classList.add('hidden');
                tshirtsOptions.classList.add('hidden');
                curtainsOptions.classList.add('hidden');
                accessoriesOptions.classList.add('hidden');
                // show SchoolUniformOptionsOuter element
                schoolUniformOptions.classList.remove('hidden');
            });

            sportsWear.addEventListener('click', function() {
                // hide SchoolUniformOptionsOuter, TShirtsOptionsOuter, CurtainsOptionsOuter, and AccessoriesOptionsOuter elements
                schoolUniformOptions.classList.add('hidden');
                tshirtsOptions.classList.add('hidden');
                curtainsOptions.classList.add('hidden');
                accessoriesOptions.classList.add('hidden');
                // show SportsWearOptionsOuter element
                sportsWearOptions.classList.remove('hidden');
            });

            tshirts.addEventListener('click', function() {
                // hide SchoolUniformOptionsOuter, SportsWearOptionsOuter, CurtainsOptionsOuter, and AccessoriesOptionsOuter elements
                schoolUniformOptions.classList.add('hidden');
                sportsWearOptions.classList.add('hidden');
                curtainsOptions.classList.add('hidden');
                accessoriesOptions.classList.add('hidden');
                // show TShirtsOptionsOuter element
                tshirtsOptions.classList.remove('hidden');
            });

            curtains.addEventListener('click', function(){
                // hide SchoolUniformOptionsOuter, SportsWearOptionsOuter, TShirtsOptionsOuter, and AccessoriesOptionsOuter elements
                schoolUniformOptions.classList.add('hidden');
                sportsWearOptions.classList.add('hidden');
                tshirtsOptions.classList.add('hidden');
                accessoriesOptions.classList.add('hidden');
                // show CurtainsOptionsOuter element
                curtainsOptions.classList.remove('hidden');
            })

            accessories.addEventListener('click', function(){
                // hide SchoolUniformOptionsOuter, SportsWearOptionsOuter, TShirtsOptionsOuter, and CurtainsOptionsOuter elements
                schoolUniformOptions.classList.add('hidden');
                sportsWearOptions.classList.add('hidden');
                tshirtsOptions.classList.add('hidden');
                curtainsOptions.classList.add('hidden');
                // show AccessoriesOptionsOuter element
                accessoriesOptions.classList.remove('hidden');
            })
        });

    // function for dropdown animation in FAQ
    // call all RevealAnswer elements
    const revealAnswers = document.querySelectorAll('.RevealAnswer');

    // add event listeners to each RevealAnswer element
    revealAnswers.forEach(function(revealAnswer) {
        revealAnswer.addEventListener('click', function() {
            // get HiddenAnswer sibling of the current RevealAnswer element
            const hiddenAnswer = revealAnswer.nextElementSibling;

            // toggle the max-h-screen and opacity-100 classes on the HiddenAnswer element
            hiddenAnswer.classList.toggle('max-h-screen');
            hiddenAnswer.classList.toggle('opacity-100');
        });
    });

// get all OptionPngs elements
const optionPngs = document.querySelectorAll('.OptionPngs');

//turn cursor to pointer upon hovering over OptionPngs
optionPngs.forEach(function(optionPng) {
    optionPng.style.cursor = 'pointer';
});


document.addEventListener('DOMContentLoaded', function() {
    const userIcon = document.querySelector('.userIcon');
    const dropdown = document.querySelector('.userIconDropdown');

    userIcon.addEventListener('click', function() {
        dropdown.classList.toggle('hidden');
    });
});


// chat js
document.addEventListener('DOMContentLoaded', function () {
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const messageList = document.getElementById('messages');
    const userName = document.querySelector('meta[name="user-name"]').getAttribute('content');

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
        .then(response => response.json())
        .then(data => {
            // Debug output
            // console.log('Message sent:', data); 
            messageInput.value = ''; // Clear the input after sending
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Listen for real-time messages
    window.Echo.private('chat')
    .listen('MessageSent', (e) => {
        // Extract data from event
        const userName = e.user || 'Unknown User'; // Fallback if user name is not defined
        const messageContent = e.message || 'No Content'; // Fallback if message content is not defined
        const messageDate = e.date || 'Unknown Date'; // Fallback if date is not defined
        const senderId = e.senderId || null; // Add senderId to your event data
    
        // Set this to the ID of the current user
        const currentUserId = window.currentUserId || null;
    
        // Create a new message element
        const messageElement = document.createElement('li');
        messageElement.className = 'mb-2 flex'; // Tailwind margin-bottom for spacing and flexbox for alignment
        
        // Determine alignment based on whether the sender is the current user
        const isSender = senderId === currentUserId;
        messageElement.innerHTML = `
            <div class="${isSender ? 'ml-auto text-right' : 'mr-auto text-left'}">
                <strong class="text-white">${userName} (${messageDate})</strong><br>
                <span class="bg-maroonbgcolor text-white p-2 rounded-lg inline-block w-auto h-auto">${messageContent}</span>
            </div>
        `;
    
        // Insert the new message at the top
        const messagesContainer = document.getElementById('messages');
        messagesContainer.insertBefore(messageElement, messagesContainer.firstChild);
    
        // Scroll to the top to show the latest message
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    });    
});
