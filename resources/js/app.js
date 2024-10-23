import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// addorder js
document.addEventListener('DOMContentLoaded', function() {
    // load header.html file
    // get the header element
    const header = document.getElementById('header');
    // set the header's innerHTML to the loaded HTML

    // call and assign SchoolUniform, SportsWear, TShirts, and Curtains elements
    const schoolUniform = document.querySelector('.SchoolUniform');
    const sportsWear = document.querySelector('.SportsWear');
    const tshirts = document.querySelector('.TShirts');
    const curtains = document.querySelector('.Curtains');

    // call and assign SchoolUniformOptionsOuter, SportsWearOptionsOuter, TShirtsOptionsOuter, and CurtainsOptionsOuter elements
    const schoolUniformOptions = document.querySelector('.SchoolUniformOptionsOuter');
    const sportsWearOptions = document.querySelector('.SportsWearOptionsOuter');
    const tshirtsOptions = document.querySelector('.TShirtsOptionsOuter');
    const curtainsOptions = document.querySelector('.CurtainsOptionsOuter');

    // hide SportsWearOptionsOuter, TShirtsOptionsOuter, and CurtainOptionsOuter elements by default
    sportsWearOptions.classList.add('hidden');
    tshirtsOptions.classList.add('hidden');
    curtainsOptions.classList.add('hidden');

    // add event listeners to SchoolUniform, SportsWear, TShirts, and Curtains elements

    schoolUniform.addEventListener('click', function() {
        // hide SportsWearOptionsOuter, TShirtsOptionsOuter, and CurtainsOptionsOuter elements
        sportsWearOptions.classList.add('hidden');
        tshirtsOptions.classList.add('hidden');
        curtainsOptions.classList.add('hidden');
        // show SchoolUniformOptionsOuter element
        schoolUniformOptions.classList.remove('hidden');
    });

    sportsWear.addEventListener('click', function() {
        // hide SchoolUniformOptionsOuter, TShirtsOptionsOuter, and CurtainsOptionsOuter elements
        schoolUniformOptions.classList.add('hidden');
        tshirtsOptions.classList.add('hidden');
        curtainsOptions.classList.add('hidden');
        // show SportsWearOptionsOuter element
        sportsWearOptions.classList.remove('hidden');
    });

    tshirts.addEventListener('click', function() {
        // hide SchoolUniformOptionsOuter, SportsWearOptionsOuter, and CurtainsOptionsOuter elements
        schoolUniformOptions.classList.add('hidden');
        sportsWearOptions.classList.add('hidden');
        curtainsOptions.classList.add('hidden');
        // show TShirtsOptionsOuter element
        tshirtsOptions.classList.remove('hidden');
    });

    curtains.addEventListener('click', function(){
        // hide SchoolUniformOptionsOuter, SportsWearOptionsOuter, and TShirtsOptionsOuter elements
        schoolUniformOptions.classList.add('hidden');
        sportsWearOptions.classList.add('hidden');
        tshirtsOptions.classList.add('hidden');
        // show CurtainsOptionsOuter element
        curtainsOptions.classList.remove('hidden');
    })

    // get all OptionPngs elements
const optionPngs = document.querySelectorAll('.OptionPngs');

//turn cursor to pointer upon hovering over OptionPngs
optionPngs.forEach(function(optionPng) {
    optionPng.style.cursor = 'pointer';
});

});

// faq js
document.addEventListener('DOMContentLoaded', function () {
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
});

// user icon 
document.addEventListener('DOMContentLoaded', function() {
    const userIcon = document.querySelector('.userIcon');
    const dropdown = document.querySelector('.userIconDropdown');

    userIcon.addEventListener('click', function() {
        dropdown.classList.toggle('hidden');
    });
});

// Function to hide the gender modal
window.hideGenderModal = function() {
    document.getElementById('genderModal').classList.add('hidden');
}

// Event listener for the add order trigger
document.addEventListener('DOMContentLoaded', function() {
    const addOrderTrigger = document.getElementById('addOrderTrigger');
    if (addOrderTrigger) {
        addOrderTrigger.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior
            document.getElementById('genderModal').classList.remove('hidden'); // Show the modal
        });
    }

    // Handle male option
    const maleOption = document.getElementById('maleOption');
    if (maleOption) {
        maleOption.addEventListener('click', function() {
            // Redirect to add custom order page for male
            window.location.href = "/addcustomorder/male"; // Adjust the route as necessary
        });
    }

    // Handle female option
    const femaleOption = document.getElementById('femaleOption');
    if (femaleOption) {
        femaleOption.addEventListener('click', function() {
            // Redirect to add custom order page for female
            window.location.href = "/addcustomorder/female"; // Adjust the route as necessary
        });
    }
});
