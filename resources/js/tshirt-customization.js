import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import iro from '@jaames/iro';

let scene, camera, renderer, tShirt, controls;
let isSpinning = false; 
let isRightMouseButton = false; 
let previousMousePosition = { x: 0, y: 0 };

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

    // Renderer setup
    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.getElementById('tshirt-container').appendChild(renderer.domElement);

    // Load t-shirt model
    loadModel();

    // Lighting setup
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
    scene.add(ambientLight);
    const directionalLight = new THREE.DirectionalLight(0xffffff, 2);
    directionalLight.position.set(1, 2, 2).normalize();
    scene.add(directionalLight);
    const pointLight = new THREE.PointLight(0xffffff, 1, 100);
    pointLight.position.set(5, 5, 5);
    scene.add(pointLight);

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
        color: "#ffffff" // Default color
    });

    // Color change event
    colorPicker.on('color:change', (color) => {
        if (tShirt) {
            tShirt.traverse((child) => {
                if (child.isMesh) {
                    child.material.color.set(color.hexString); // Change color of the t-shirt mesh
                }
            });
        }
    });

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
