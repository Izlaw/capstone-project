import './bootstrap';

import Alpine from 'alpinejs';
import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';

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

// user icon 
document.addEventListener('DOMContentLoaded', function() {
    const userIcon = document.querySelector('.userIcon');
    const dropdown = document.querySelector('.userIconDropdown');

    userIcon.addEventListener('click', function() {
        dropdown.classList.toggle('hidden');
    });
});



