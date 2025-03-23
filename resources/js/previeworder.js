import * as THREE from "three";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader.js";
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls.js";
import { DecalGeometry } from "three/examples/jsm/geometries/DecalGeometry.js";

// Store the scene reference globally so it can be accessed from anywhere
let globalScene = null;

/* Model Preview Initialization */
export function initTshirtModel(colors = {}) {
    if (!colors || Object.keys(colors).length === 0) {
        console.error("Colors are missing or empty.");
        return;
    }
    // console.log("Parsed colors:", colors);

    const canvas = document.getElementById("tshirtCanvas");
    const scene = new THREE.Scene();
    // Store the scene reference globally
    globalScene = scene;

    // Set the background color
    scene.background = new THREE.Color("#374256");

    const camera = new THREE.PerspectiveCamera(
        75,
        canvas.offsetWidth / canvas.offsetHeight,
        0.1,
        1000
    );
    // Set default camera position to match customize.js
    const defaultCameraPosition = new THREE.Vector3(-0.075, 2.4, 3.85);
    camera.position.copy(defaultCameraPosition);
    camera.zoom = 0.9;
    camera.updateProjectionMatrix();

    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
    renderer.setSize(canvas.offsetWidth, canvas.offsetHeight);

    // Listen for window resize events to update renderer and camera aspect
    window.addEventListener("resize", () => {
        const width = canvas.offsetWidth;
        const height = canvas.offsetHeight;
        renderer.setSize(width, height);
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
    });

    // Lighting setup - match the lighting in customize.js
    setupLighting(scene);

    // Initialize OrbitControls with parameters from customize.js
    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.25;
    controls.screenSpacePanning = false;
    controls.maxPolarAngle = Math.PI / 2;
    controls.enableZoom = true;
    controls.enableRotate = true;
    controls.zoomSpeed = 1.5;

    // Initialize the model and apply colors
    loadModelPreview(scene, camera, controls, renderer, colors);

    // Reset camera and model when "r" is pressed
    window.addEventListener("keydown", (event) => {
        if (event.key.toLowerCase() === "r") {
            console.log("Resetting camera and model to default values.");
            // Reset camera position and lookAt
            camera.position.copy(defaultCameraPosition);
            controls.target.set(0, 0, 0);
            controls.update();
            camera.zoom = 0.9;
            camera.updateProjectionMatrix();
        }
    });

    const animate = function () {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    };
    animate();
}

function setupLighting(scene) {
    // Match the lighting setup from customize.js
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
    scene.add(ambientLight);

    const directionalLight = new THREE.DirectionalLight(0xffffff, 2);
    directionalLight.position.set(1, 2, 2).normalize();
    scene.add(directionalLight);

    const pointLight = new THREE.PointLight(0xffffff, 1, 100);
    pointLight.position.set(5, 5, 5);
    scene.add(pointLight);
}

// Load the preview model with the selected colors
function loadModelPreview(scene, camera, controls, renderer, colors) {
    const modelPath = "/models/shirtmockup.gltf";
    const loader = new GLTFLoader();

    let modelLoaded = false;

    loader.load(
        modelPath,
        (gltf) => {
            const model = gltf.scene;

            // Match the same transforms as in customize.js
            model.scale.set(5, 5, 5);
            model.position.y = -5.3;

            scene.add(model);
            window.selectedModel = model;
            modelLoaded = true;
            applyColorsToModel(colors);

            // Extract text customization data from the DOM after model is loaded
            const textDataStr = document.body.getAttribute("data-text");
            if (textDataStr && textDataStr !== "null") {
                try {
                    // Parse as array of text objects
                    const textDataArray = JSON.parse(textDataStr);
                    console.log(
                        "Parsed text customization array:",
                        textDataArray
                    );

                    // Use setTimeout to ensure model is fully loaded and processed
                    setTimeout(() => {
                        // Handle both single object and array formats
                        if (Array.isArray(textDataArray)) {
                            applyMultipleTextCustomizations(
                                textDataArray,
                                scene,
                                model
                            );
                        } else {
                            // Legacy support for single text object
                            applyTextCustomization(textDataArray, scene, model);
                        }
                    }, 500);
                } catch (e) {
                    console.error("Error parsing text customization data:", e);
                }
            } else {
                console.log("No text customization data found.");
            }
        },
        undefined,
        (error) => {
            console.error("Error loading model:", error);
        }
    );
}

// Apply the colors to the model
function applyColorsToModel(colors) {
    const { backColor, frontColor, sleevesColor, collarColor } = colors;

    console.log(`Back Color: ${backColor}`);
    console.log(`Front Color: ${frontColor}`);
    console.log(`Sleeves Color: ${sleevesColor}`);
    console.log(`Collar Color: ${collarColor}`);

    if (window.selectedModel) {
        window.selectedModel.traverse((child) => {
            if (child.isMesh) {
                if (child.name === "Object_115") {
                    // Make sure we're creating a proper THREE.Color object
                    child.material.emissive.set(new THREE.Color(frontColor));
                    child.material.emissiveIntensity = 0.6;
                    child.material.needsUpdate = true;
                }
                if (child.name === "Object_113") {
                    child.material.emissive.set(new THREE.Color(backColor));
                    child.material.emissiveIntensity = 0.6;
                    child.material.needsUpdate = true;
                }
                if (
                    child.name === "Object_123" ||
                    child.name === "Object_125"
                ) {
                    child.material.emissive.set(new THREE.Color(sleevesColor));
                    child.material.emissiveIntensity = 0.6;
                    child.material.needsUpdate = true;
                }
                if (child.name === "Object_121") {
                    child.material.emissive.set(new THREE.Color(collarColor));
                    child.material.emissiveIntensity = 0.6;
                    child.material.needsUpdate = true;
                }
            }
        });
    }
}

// Listen for the DOM content loaded and initialize the model
document.addEventListener("DOMContentLoaded", function () {
    if (window.location.pathname.includes("/previeworder")) {
        const colorsData = document.body.getAttribute("data-colors");
        if (colorsData) {
            try {
                const colors = JSON.parse(colorsData);
                // console.log("Parsed colors from data attribute:", colors);
                initTshirtModel(colors);
            } catch (e) {
                console.error("Error parsing colors:", e);
            }
        } else {
            console.error("Colors are missing.");
        }
    }
});

// New function to handle multiple text customizations
function applyMultipleTextCustomizations(textDataArray, scene, model) {
    if (!Array.isArray(textDataArray)) {
        console.error("textDataArray must be an array");
        return;
    }

    console.log(`Applying ${textDataArray.length} text customizations`);

    // Filter out empty text entries
    const validTextData = textDataArray.filter(
        (textData) => textData.text && textData.text.trim() !== ""
    );

    // Apply each valid text customization
    validTextData.forEach((textData, index) => {
        console.log(
            `Applying text customization #${index + 1}: "${textData.text}"`
        );
        applyTextCustomization(textData, scene, model, index);
    });
}

// Updated function to apply a single text customization, now with an index parameter
function applyTextCustomization(textData, scene, model, index = 0) {
    if (!textData.text || textData.text.trim() === "") {
        console.log(`Text customization #${index} has empty text, skipping`);
        return;
    }

    if (!scene) {
        console.error("Scene is not defined in applyTextCustomization");
        // Fall back to global scene if available
        if (globalScene) {
            scene = globalScene;
        } else {
            return; // Can't proceed without a scene
        }
    }

    // Create a canvas to render the text - match the canvas size from customize.js
    const decalCanvas = document.createElement("canvas");
    const dctx = decalCanvas.getContext("2d");
    decalCanvas.width = 512;
    decalCanvas.height = 256;
    dctx.clearRect(0, 0, decalCanvas.width, decalCanvas.height);

    // Parse fontSize to integer if it's a string with 'px'
    let fontSize = textData.fontSize;
    if (typeof fontSize === "string") {
        fontSize = parseInt(fontSize.replace("px", ""), 10);
    }

    // Set font using the provided font size and color
    dctx.font = `${fontSize}px Arial`;
    dctx.textAlign = "center";
    dctx.textBaseline = "middle";
    dctx.fillStyle = textData.fontColor;
    dctx.fillText(textData.text, decalCanvas.width / 2, decalCanvas.height / 2);

    // Create a texture and material for the decal
    const decalTexture = new THREE.CanvasTexture(decalCanvas);
    decalTexture.needsUpdate = true;

    // Check if THREE.sRGBEncoding is available, otherwise use the newer THREE.SRGBColorSpace
    if (THREE.sRGBEncoding !== undefined) {
        decalTexture.encoding = THREE.sRGBEncoding;
    } else if (THREE.SRGBColorSpace !== undefined) {
        decalTexture.colorSpace = THREE.SRGBColorSpace;
    }

    // Use MeshBasicMaterial for better visibility
    const decalMaterial = new THREE.MeshBasicMaterial({
        map: decalTexture,
        transparent: true,
        depthTest: true,
        depthWrite: false,
        polygonOffset: true,
        polygonOffsetFactor: -4,
    });

    // Find the front mesh from the loaded model
    let frontMesh = null;
    if (window.selectedModel) {
        window.selectedModel.traverse((child) => {
            if (child.isMesh && child.name === "Object_115") {
                frontMesh = child;
            }
        });
    } else {
        console.error("Selected model not found for text application");
        return;
    }

    if (frontMesh) {
        // Get the bounding box of the front mesh to position the text properly
        const bbox = new THREE.Box3().setFromObject(frontMesh);
        const center = new THREE.Vector3();
        bbox.getCenter(center);

        // Use the position from textData if available, otherwise use a centered position
        let position;
        if (
            textData.fontPosition &&
            typeof textData.fontPosition === "object"
        ) {
            // Convert from object if necessary
            position = new THREE.Vector3(
                parseFloat(textData.fontPosition.x) || 0,
                parseFloat(textData.fontPosition.y) || center.y,
                parseFloat(textData.fontPosition.z) || 0.1 // Small offset from surface
            );
        } else {
            // Default centered position on front of shirt
            position = new THREE.Vector3(center.x, center.y, center.z + 0.1);
        }

        // Find normal at position for proper orientation
        // For simplicity, use the front direction since this is the front of the shirt
        const normal = new THREE.Vector3(0, 0, 1).normalize();

        // Use quaternion for rotation
        const quaternion = new THREE.Quaternion();
        quaternion.setFromUnitVectors(new THREE.Vector3(0, 0, 1), normal);
        const orientation = new THREE.Euler().setFromQuaternion(quaternion);

        // Scale based on font size - adjusted for better visibility
        const scale = fontSize * 0.01;
        const size = new THREE.Vector3(scale * 4, scale * 2, 1);

        try {
            // Create the decal geometry
            const decalGeometry = new DecalGeometry(
                frontMesh,
                position,
                orientation,
                size
            );

            const decalMesh = new THREE.Mesh(decalGeometry, decalMaterial);

            // Store the user data like in customize.js
            decalMesh.userData = {
                type: "text",
                text: textData.text,
                fontSize: fontSize,
                textColor: textData.fontColor,
                position: position,
                index: index, // Add index to track multiple texts
            };

            scene.add(decalMesh);
            console.log(
                `[INFO] Applied text customization #${index} decal at`,
                position
            );
        } catch (error) {
            console.error(`Error creating text decal #${index}:`, error);

            // Fallback: Create a simple plane with the text as a backup method
            const planeGeometry = new THREE.PlaneGeometry(scale * 8, scale * 4);
            const planeMesh = new THREE.Mesh(planeGeometry, decalMaterial);
            planeMesh.position.copy(position);
            planeMesh.lookAt(position.clone().add(normal));
            planeMesh.userData = {
                type: "text",
                text: textData.text,
                fontSize: fontSize,
                textColor: textData.fontColor,
                position: position,
                index: index, // Add index to track multiple texts
            };
            scene.add(planeMesh);
            console.log(
                `[FALLBACK] Applied text #${index} as plane at`,
                position
            );
        }
    } else {
        console.error("Front mesh not found. Cannot apply text customization.");
    }
}
