import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import iro from '@jaames/iro';

let scene, camera, renderer, tShirt, controls;
let isSpinning = false; 
let isRightMouseButton = false; 
let previousMousePosition = { x: 0, y: 0 };

// Customization data
let selectedColor = "#ffffff"; // Default color
let collarType = "Round"; // Example collar type

// Customize
function init() {
    // Scene setup
    scene = new THREE.Scene();
    scene.background = new THREE.Color(0xffffff);

    // Camera setup
    camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.set(-0.075, 2.40, 3.85);
    camera.zoom = 0.9;
    camera.updateProjectionMatrix();

    // Expose the camera to the global scope
    window.camera = camera;

    // Lighting setup
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
    scene.add(ambientLight);
    const directionalLight = new THREE.DirectionalLight(0xffffff, 2);
    directionalLight.position.set(1, 2, 2).normalize();
    scene.add(directionalLight);
    const pointLight = new THREE.PointLight(0xffffff, 1, 100);
    pointLight.position.set(5, 5, 5);
    scene.add(pointLight);

    // Renderer setup
    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.getElementById('tshirt-container').appendChild(renderer.domElement);

    // Load t-shirt model
    loadModel();

    // Initialize OrbitControls
    controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.25;
    controls.screenSpacePanning = false;
    controls.maxPolarAngle = Math.PI / 2;
    controls.enableZoom = true;

    // Handle window resizing
    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });

    // Mouse event listeners
    window.addEventListener('mousedown', (event) => {
        if (event.button === 2) {
            isRightMouseButton = true;
            previousMousePosition = { x: event.clientX, y: event.clientY };
        }
    });

    window.addEventListener('mouseup', (event) => {
        if (event.button === 2) {
            isRightMouseButton = false;
        }
    });

    window.addEventListener('mousemove', (event) => {
        if (isRightMouseButton) {
            const deltaX = event.clientX - previousMousePosition.x;
            const deltaY = event.clientY - previousMousePosition.y;
            camera.position.x -= deltaX * 0.01;
            camera.position.y += deltaY * 0.01;
            previousMousePosition = { x: event.clientX, y: event.clientY };
        }
    });

    // Button to reset camera position
    document.getElementById('resetCamera').addEventListener('click', () => {
        camera.position.set(-0.075, 2.40, 3.85);
        controls.target.set(0, 0, 0);
        controls.update();
        if (tShirt) {
            tShirt.rotation.set(0, 0, 0);
        }

        // Reset camera zoom
        camera.zoom = 0.9; // Set to default zoom level
        camera.updateProjectionMatrix(); // Apply the zoom change
    });

    // Toggle spin animation
    document.getElementById('toggleSpin').addEventListener('click', () => {
        const button = document.getElementById('toggleSpin');
        button.classList.toggle('bg-green-500');
        button.classList.toggle('bg-red-500');
        isSpinning = !isSpinning;
    });

    // Initialize iro.js color picker
    const colorPicker = new iro.ColorPicker("#colorPickerContainer", {
        width: 150,
        color: selectedColor // Default color
    });

    // Color change event
    colorPicker.on('color:change', (color) => {
        selectedColor = color.hexString; // Update selected color
        if (tShirt) {
            tShirt.traverse((child) => {
                if (child.isMesh) {
                    child.material.color.set(selectedColor); // Change color of the t-shirt mesh
                }
            });
        }
    });

    // Confirm order event
document.getElementById('confirmOrder').addEventListener('click', async () => {
    // Capture the customization data
    const customizations = {
        color: selectedColor,
        collarType: collarType,
        size_xs: document.querySelector('input[name="size_xs"]').value || 0,
        size_s: document.querySelector('input[name="size_s"]').value || 0,
        size_m: document.querySelector('input[name="size_m"]').value || 0,
        size_l: document.querySelector('input[name="size_l"]').value || 0,
        size_xl: document.querySelector('input[name="size_xl"]').value || 0,
    };

    // Get the CSRF token from the meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        // Fetch the QR code view from the backend
        const response = await fetch('/qrcode', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(customizations),
        });

        if (response.ok) {
            const qrCodeHtml = await response.text();
            showQRCodeModal(qrCodeHtml, customizations);
        } else {
            console.error('Failed to fetch QR code view:', response.status, response.statusText);
        }
        
    } catch (error) {
        console.error('Error fetching QR code view:', error);
    }
});

// Function to display QR code in a modal
function showQRCodeModal(qrCodeHtml, customizations) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Construct the modal HTML
    const modalHtml = `
        <div id="qrCodeModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white p-5 rounded-lg shadow-lg text-center max-w-md w-full">
                ${qrCodeHtml} <!-- Insert the QR code view directly here -->
                <p class="mt-2">Scan this QR code to view your customized design!</p>
                <button id="closeModal" class="mt-4 p-2 bg-red-500 text-white rounded hover:bg-red-600 transition duration-300">Close</button>
            </div>
        </div>
    `;

    // Insert the modal into the DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Close modal event
    document.getElementById('closeModal').addEventListener('click', () => {
        document.getElementById('qrCodeModal').remove();
    });

    // Download billing statement event
    document.getElementById('downloadBillingStatement').addEventListener('click', () => {
        // Prepare the URL for generating the billing statement
        const url = '/generate-billing-statement'; // Your endpoint for generating the PDF

        // Create a form to submit the customizations data
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        // Add customization data
        for (const [key, value] of Object.entries(customizations)) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit(); // Submit the form to download the PDF
        form.remove(); // Clean up
    });
}


    animate();
}

function loadModel() {
    const loader = new GLTFLoader();
    loader.load('/models/tshirt.glb', (gltf) => {
        tShirt = gltf.scene;
        tShirt.scale.set(5, 5, 5);
        tShirt.position.y = -5.3;
        scene.add(tShirt);
    }, undefined, (error) => {
        console.error('An error occurred while loading the model:', error);
    });
}

function animate() {
    requestAnimationFrame(animate);
    controls.update();
    if (isSpinning && tShirt) {
        tShirt.rotation.y += 0.01;
    }
    renderer.render(scene, camera);
}

// Initialize
window.onload = init;

// Preview model
function initTshirtModel(color) {
    const canvas = document.getElementById('tshirtCanvas');
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.set(-0.075, 2.40, 3.85);
    camera.zoom = 2;
    camera.updateProjectionMatrix();
    const renderer = new THREE.WebGLRenderer({ canvas });
    renderer.setSize(canvas.clientWidth, canvas.clientHeight); // Set renderer size based on canvas size

    // Lighting setup
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
    scene.add(ambientLight);
    const directionalLight = new THREE.DirectionalLight(0xffffff, 2);
    directionalLight.position.set(1, 2, 2).normalize();
    scene.add(directionalLight);
    const pointLight = new THREE.PointLight(0xffffff, 1, 100);
    pointLight.position.set(5, 5, 5);
    scene.add(pointLight);
    scene.background = new THREE.Color(0xffffff);


    // Initialize OrbitControls
    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.25;
    controls.screenSpacePanning = false;
    controls.maxPolarAngle = Math.PI / 2;
    controls.enableZoom = true;

    // Load the model
    const loader = new GLTFLoader();
    loader.load('/models/tshirt.glb', (gltf) => {
        const tshirt = gltf.scene;
        scene.add(tshirt);

        // Set the color
        tshirt.traverse((child) => {
            if (child.isMesh) {
                child.material.color.set(color); // Apply the color
            }
        });

        camera.position.z = 5;

        const animate = function () {
            requestAnimationFrame(animate);
            controls.update(); // Update controls
            renderer.render(scene, camera);
        };
        animate();
    }, undefined, function (error) {
        console.error('An error occurred while loading the model:', error);
    });
}

// Call this function after the page has loaded, passing the color from Laravel
document.addEventListener('DOMContentLoaded', () => {
    const color = document.getElementById('tshirtCanvas').getAttribute('data-color'); // Use a data attribute to pass the color
    initTshirtModel(color);
});
