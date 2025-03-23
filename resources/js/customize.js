import Swal from "sweetalert2";
import "./swalpopup.js";
import * as THREE from "three";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader.js";
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls.js";
import { FontLoader } from "three/examples/jsm/loaders/FontLoader.js";
import { DecalGeometry } from "three/examples/jsm/geometries/DecalGeometry.js";
window.OrbitControls = OrbitControls;
import iro from "@jaames/iro";
window.iro = iro;
import { newcustomorder } from "./newcustomorder";
import "./fabrictype";

const canvas = document.createElement("canvas");
const ctx = canvas.getContext("2d");
canvas.width = 512;
canvas.height = 512;

let scene, camera, renderer, controls, selectedModel;
let isSpinning = false;
let isRightMouseButton = false;
let previousMousePosition = { x: 0, y: 0 };

let selectedParts = {
    sleeves: false,
    front: false,
    back: false,
    collar: false,
};

let sleevesMesh, frontMesh, backMesh, collarMesh;
let textMesh = null;
let loadedFont = null;
let textDecals = [];
let editingDecal = null;

const fontLoader = new FontLoader();
fontLoader.load(
    "/fonts/helvetiker_regular.typeface.json",
    (font) => {
        loadedFont = font;
    },
    undefined,
    (err) => {}
);

function activateTextDecalPlacement(text, fontSize, textColor) {
    const decalCanvas = document.createElement("canvas");
    const dctx = decalCanvas.getContext("2d");
    decalCanvas.width = 512;
    decalCanvas.height = 256;
    dctx.clearRect(0, 0, decalCanvas.width, decalCanvas.height);
    dctx.font = `${fontSize}px Arial`;
    dctx.textAlign = "center";
    dctx.textBaseline = "middle";
    dctx.fillStyle = textColor;
    dctx.fillText(text, decalCanvas.width / 2, decalCanvas.height / 2);
    const decalTexture = new THREE.CanvasTexture(decalCanvas);
    decalTexture.needsUpdate = true;
    decalTexture.encoding = THREE.sRGBEncoding;
    const decalMaterial = new THREE.MeshBasicMaterial({
        map: decalTexture,
        transparent: true,
        depthTest: true,
        depthWrite: false,
        polygonOffset: true,
        polygonOffsetFactor: -4,
    });
    const raycaster = new THREE.Raycaster();
    const mouse = new THREE.Vector2();

    function onDecalClick(event) {
        mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
        mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;
        raycaster.setFromCamera(mouse, camera);
        const intersects = raycaster.intersectObject(frontMesh, true);
        if (intersects.length > 0) {
            const intersect = intersects[0];
            const position = intersect.point.clone();
            const orientation = new THREE.Euler();
            const quaternion = new THREE.Quaternion();
            const normal = intersect.face.normal.clone();
            normal.transformDirection(frontMesh.matrixWorld);
            quaternion.setFromUnitVectors(new THREE.Vector3(0, 0, 1), normal);
            orientation.setFromQuaternion(quaternion);
            const scale = fontSize * 0.01;
            const size = new THREE.Vector3(scale * 4, scale * 2, 1);
            const decalGeometry = new DecalGeometry(
                frontMesh,
                position,
                orientation,
                size
            );
            const decalMesh = new THREE.Mesh(decalGeometry, decalMaterial);
            // Store the computed position in userData
            decalMesh.userData = {
                type: "text",
                text: text,
                fontSize: fontSize,
                textColor: textColor,
                position: position, // <-- Added this line
            };
            scene.add(decalMesh);
            textDecals.push(decalMesh);
            makeDecalEditable(decalMesh);

            // Debugging log for text position
            console.log("Text Decal Position:", position);

            renderer.domElement.removeEventListener("click", onDecalClick);
        }
    }
    renderer.domElement.addEventListener("click", onDecalClick);
}

function makeDecalEditable(decalMesh) {
    const raycaster = new THREE.Raycaster();
    const mouse = new THREE.Vector2();
    function onDoubleClick(event) {
        mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
        mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;
        raycaster.setFromCamera(mouse, camera);
        const intersects = raycaster.intersectObjects(textDecals, true);
        if (intersects.length > 0) {
            const clickedDecal = intersects[0].object;
            const textData = clickedDecal.userData;
            if (textData && textData.type === "text") {
                editingDecal = clickedDecal;
                openTextEditModal(
                    textData.text,
                    textData.fontSize,
                    textData.textColor
                );
            }
        }
    }
    renderer.domElement.addEventListener("dblclick", onDoubleClick);
}

function openTextEditModal(
    currentText = "",
    currentFontSize = 64,
    currentColor = "#ff0000"
) {
    Swal.fire({
        title: "Edit Text",
        html: `
        <div class="w-full">
            <div class="mb-4">
                <label for="swalInputText" class="block text-left mb-2">Text:</label>
                <input type="text" id="swalInputText" class="w-full p-2 border rounded" value="${currentText}" placeholder="Enter your text here">
            </div>
            <div class="mb-4">
                <label for="swalFontSize" class="block text-left mb-2">Font Size:</label>
                <input type="range" id="swalFontSize" class="w-full" min="12" max="120" value="${currentFontSize}">
                <div class="flex justify-between text-xs mt-1">
                    <span>12px</span>
                    <span>120px</span>
                </div>
            </div>
            <div class="mb-4">
                <label for="swalColorPicker" class="block text-left mb-2">Text Color:</label>
                <input type="color" id="swalColorPicker" class="w-full h-10" value="${currentColor}">
            </div>
            <div class="mt-6 p-4 border rounded bg-gray-100">
                <h3 class="mb-2 text-left font-bold">Preview:</h3>
                <div class="p-4 bg-white border rounded flex items-center justify-center min-h-16">
                    <span id="swalPreviewText" style="font-size: ${currentFontSize}px; color: ${currentColor};">
                        ${currentText || "Your Text Here"}
                    </span>
                </div>
            </div>
        </div>
    `,
        width: "600px",
        padding: "2rem",
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: "Apply Text",
        cancelButtonText: "Cancel",
        showDenyButton: editingDecal !== null,
        denyButtonText: "Delete Text",
        preConfirm: () => {
            const text = document.getElementById("swalInputText").value;
            const fontSize = parseInt(
                document.getElementById("swalFontSize").value,
                10
            );
            const textColor = document.getElementById("swalColorPicker").value;
            return { text, fontSize, textColor };
        },
        didOpen: () => {
            document.addEventListener("keydown", handleEscapeKey);
        },
        willClose: () => {
            document.removeEventListener("keydown", handleEscapeKey);
        },
    }).then((result) => {
        if (result.isConfirmed) {
            const { text, fontSize, textColor } = result.value;

            if (editingDecal) {
                scene.remove(editingDecal);
                const index = textDecals.indexOf(editingDecal);
                if (index > -1) {
                    textDecals.splice(index, 1);
                }
                editingDecal = null;

                window.showAlert(
                    "Place Your Text",
                    "Click on the t-shirt to place your text",
                    "info",
                    "text-placement-toast"
                );
                activateTextDecalPlacement(text, fontSize, textColor);
            } else {
                window.showAlert(
                    "Place Your Text",
                    "Click on the t-shirt to place your text",
                    "info",
                    "text-placement-toast"
                );
                activateTextDecalPlacement(text, fontSize, textColor);
            }
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            editingDecal = null;
        } else if (result.isDenied) {
            if (editingDecal) {
                scene.remove(editingDecal);
                const index = textDecals.indexOf(editingDecal);
                if (index > -1) {
                    textDecals.splice(index, 1);
                }
                editingDecal = null;
                window.showAlert(
                    "Deleted!",
                    "Text has been deleted.",
                    "success",
                    "text-delete-toast"
                );
            }
        }
    });

    function handleEscapeKey(event) {
        if (event.key === "Escape") {
            Swal.close();
            editingDecal = null;
        }
    }

    setTimeout(() => {
        const inputText = document.getElementById("swalInputText");
        const fontSizeSlider = document.getElementById("swalFontSize");
        const colorPickerInput = document.getElementById("swalColorPicker");
        const previewText = document.getElementById("swalPreviewText");
        if (inputText && fontSizeSlider && colorPickerInput && previewText) {
            inputText.addEventListener("input", (e) => {
                previewText.innerText = e.target.value || "Your Text Here";
            });
            fontSizeSlider.addEventListener("input", (e) => {
                previewText.style.fontSize = e.target.value + "px";
            });
            colorPickerInput.addEventListener("input", (e) => {
                previewText.style.color = e.target.value;
            });
        }
    }, 100);
}

function resetDesign() {
    Swal.fire({
        title: "Reset Design?",
        text: "This will remove all customizations and revert to the original shirt. Are you sure?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#b2192b", // danger color from tailwind.config.js
        cancelButtonColor: "#374256", // accent color from tailwind.config.js
        confirmButtonText: "Yes, reset it!",
        cancelButtonText: "Cancel",
        customClass: {
            popup: "bg-primary text-white", // Using your tailwind classes
            title: "text-highlight", // Adjust as needed
            content: "text-secondary", // Adjust as needed
        },
    }).then((result) => {
        if (result.isConfirmed) {
            clearCustomizationState();

            selectedParts = {
                sleeves: false,
                front: false,
                back: false,
                collar: false,
            };

            document
                .getElementById("toggleSleeves")
                .classList.remove("bg-green-500");
            document.getElementById("toggleSleeves").classList.add("bg-accent");
            document
                .getElementById("toggleFront")
                .classList.remove("bg-green-500");
            document.getElementById("toggleFront").classList.add("bg-accent");
            document
                .getElementById("toggleBack")
                .classList.remove("bg-green-500");
            document.getElementById("toggleBack").classList.add("bg-accent");
            document
                .getElementById("toggleCollar")
                .classList.remove("bg-green-500");
            document.getElementById("toggleCollar").classList.add("bg-accent");

            // Reset color variables
            window.selectedSleevesColor = null;
            window.selectedFrontBodyColor = null;
            window.selectedBackColor = null;
            window.selectedCollarColor = null;

            // Reset the color picker if available
            try {
                const colorPickerContainer = document.querySelector(
                    "#colorPickerContainer"
                );
                if (
                    colorPickerContainer &&
                    colorPickerContainer._iroColorPicker
                ) {
                    colorPickerContainer._iroColorPicker.color.set("#ffffff");
                }
            } catch (error) {
                console.warn("Could not reset color picker:", error);
            }

            // Remove all text decals
            while (textDecals.length > 0) {
                const decal = textDecals.pop();
                scene.remove(decal);
            }

            // Reset spinning state
            isSpinning = false;
            const spinButton = document.getElementById("toggleSpin");
            const spinIcon = spinButton.querySelector("img");
            spinButton.classList.remove("bg-green-500");
            spinButton.classList.add("bg-red-500");
            spinIcon.classList.remove("animate-spin");

            // Reset camera and controls
            camera.position.set(-0.075, 2.4, 3.85);
            controls.target.set(0, 0, 0);
            controls.update();
            camera.zoom = 0.9;
            camera.updateProjectionMatrix();

            // Complete reset of the model to original state by removing and reloading
            if (selectedModel) {
                // Store rotation before removing
                const currentRotation = selectedModel.rotation.clone();

                // Remove the current model from the scene
                scene.remove(selectedModel);

                // Load a fresh model
                const modelPath = "/models/shirtmockup.gltf";
                const loader = new GLTFLoader();
                loader.load(
                    modelPath,
                    (gltf) => {
                        selectedModel = gltf.scene;
                        selectedModel.scale.set(5, 5, 5);
                        selectedModel.position.y = -5.3;

                        // Reset rotation to zero
                        selectedModel.rotation.set(0, 0, 0);

                        // Get reference to important meshes
                        sleevesMesh =
                            selectedModel.getObjectByName("Object_123");
                        frontMesh = selectedModel.getObjectByName("Object_115");
                        backMesh = selectedModel.getObjectByName("Object_113");
                        collarMesh =
                            selectedModel.getObjectByName("Object_121");

                        // Add the fresh model to the scene
                        scene.add(selectedModel);

                        // Show success message
                        window.showAlert(
                            "Design Reset",
                            "Your design has been reset to default",
                            "success",
                            "design-reset-toast"
                        );
                    },
                    undefined,
                    (error) => {
                        console.error("Error reloading model:", error);
                        window.showAlert(
                            "Reset Error",
                            "Could not fully reset the model",
                            "error",
                            "reset-error-toast"
                        );
                    }
                );
            } else {
                // Show success message even if no model was loaded yet
                window.showAlert(
                    "Design Reset",
                    "Your design has been reset to default",
                    "success",
                    "design-reset-toast"
                );
            }
        }
    });
}

// Add event listener for the reset design button
document.addEventListener("DOMContentLoaded", function () {
    const resetDesignButton = document.getElementById("resetDesign");
    if (resetDesignButton) {
        resetDesignButton.addEventListener("click", resetDesign);
    }
});

// Export the reset function
window.resetDesign = resetDesign;

function applyTextDecal(text, fontSize, textColor, relX, relY) {
    const decalCanvas = document.createElement("canvas");
    const dctx = decalCanvas.getContext("2d");
    decalCanvas.width = 512;
    decalCanvas.height = 256;
    dctx.clearRect(0, 0, decalCanvas.width, decalCanvas.height);
    dctx.font = `${fontSize}px Arial`;
    dctx.textAlign = "center";
    dctx.textBaseline = "middle";
    dctx.fillStyle = textColor;
    dctx.fillText(text, decalCanvas.width / 2, decalCanvas.height / 2);
    const decalTexture = new THREE.CanvasTexture(decalCanvas);
    decalTexture.needsUpdate = true;
    decalTexture.encoding = THREE.sRGBEncoding;
    const decalMaterial = new THREE.MeshPhongMaterial({
        map: decalTexture,
        transparent: true,
        depthTest: true,
        depthWrite: false,
        polygonOffset: true,
        polygonOffsetFactor: -4,
    });
    const zOffset = -1;
    const bbox = new THREE.Box3().setFromObject(frontMesh);
    const boxCenter = bbox.getCenter(new THREE.Vector3());
    const boxSize = bbox.getSize(new THREE.Vector3());
    const offsetX = (relX - 0.5) * boxSize.x;
    const offsetY = (relY - 0.5) * boxSize.y;
    const decalPosition = boxCenter
        .clone()
        .add(new THREE.Vector3(offsetX, offsetY, zOffset));
    const worldQuat = new THREE.Quaternion();
    frontMesh.getWorldQuaternion(worldQuat);
    const decalOrientation = new THREE.Euler().setFromQuaternion(worldQuat);
    const scale = fontSize * 0.005;
    const decalSize = new THREE.Vector3(scale * 2, scale, scale);
    const decalGeometry = new DecalGeometry(
        frontMesh,
        decalPosition,
        decalOrientation,
        decalSize
    );
    const decalMesh = new THREE.Mesh(decalGeometry, decalMaterial);
    decalMesh.userData = {
        type: "text",
        text: text,
        fontSize: fontSize,
        textColor: textColor,
    };
    frontMesh.add(decalMesh);
    textDecals.push(decalMesh);
    makeDecalEditable(decalMesh);
}

function initializeScene() {
    scene = new THREE.Scene();
    scene.background = new THREE.Color("#374256");
    camera = new THREE.PerspectiveCamera(
        75,
        window.innerWidth / window.innerHeight,
        0.1,
        1000
    );
    camera.position.set(-0.075, 2.4, 3.85);
    camera.zoom = 0.9;
    camera.updateProjectionMatrix();
    setupLighting();
    setupRenderer();
    loadModel();
    setupOrbitControls();
    window.addEventListener("resize", onWindowResize);
    setupMouseEvents();
    setupResetCamera();
    setupToggleSpin();
    setupColorPicker();
    setupToggleButtons();
    setupTextModal();
    animate();
}

function setupLighting() {
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
    scene.add(ambientLight);
    const directionalLight = new THREE.DirectionalLight(0xffffff, 2);
    directionalLight.position.set(1, 2, 2).normalize();
    scene.add(directionalLight);
    const pointLight = new THREE.PointLight(0xffffff, 1, 100);
    pointLight.position.set(5, 5, 5);
    scene.add(pointLight);
}

function setupRenderer() {
    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    document
        .getElementById("tshirt-container")
        .appendChild(renderer.domElement);
}

function setupOrbitControls() {
    controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.25;
    controls.screenSpacePanning = false;
    controls.maxPolarAngle = Math.PI / 2;
    controls.enableZoom = true;
    controls.enableRotate = true;
    controls.zoomSpeed = 1.5;
}

function onWindowResize() {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
}

function setupMouseEvents() {
    window.addEventListener("mousedown", (event) => {
        if (event.button === 2) {
            isRightMouseButton = true;
            previousMousePosition = { x: event.clientX, y: event.clientY };
            controls.enableRotate = false;
        }
    });
    window.addEventListener("mouseup", () => {
        isRightMouseButton = false;
        controls.enableRotate = true;
    });
    window.addEventListener("mousemove", (event) => {
        if (isRightMouseButton) {
            const deltaX = event.clientX - previousMousePosition.x;
            const deltaY = event.clientY - previousMousePosition.y;
            const scale = camera.position.z / 10;
            camera.position.x -= deltaX * 0.001 * scale;
            camera.position.y += deltaY * 0.001 * scale;
            previousMousePosition = { x: event.clientX, y: event.clientY };
        }
    });
}

function setupResetCamera() {
    document.getElementById("resetCamera").addEventListener("click", () => {
        if (selectedModel) {
            camera.position.set(-0.075, 2.4, 3.85);
            selectedModel.rotation.set(0, 0, 0);
        } else {
            camera.position.set(0, 2, 5);
        }
        controls.target.set(0, 0, 0);
        controls.update();
        camera.zoom = 0.9;
        camera.updateProjectionMatrix();
    });
}

function setupToggleSpin() {
    const toggleSpinButton = document.getElementById("toggleSpin");

    // Add null check before setting up the event listener
    if (!toggleSpinButton) {
        console.warn("Toggle spin button not found in the DOM");
        return;
    }

    toggleSpinButton.addEventListener("click", () => {
        const button = document.getElementById("toggleSpin");
        const spinIcon = button.querySelector("img");

        button.classList.toggle("bg-green-500");
        button.classList.toggle("bg-red-500");

        if (isSpinning) {
            spinIcon.classList.remove("animate-spin");
        } else {
            spinIcon.classList.add("animate-spin");
        }

        isSpinning = !isSpinning;
    });
}

function setupColorPicker() {
    const colorPicker = new iro.ColorPicker("#colorPickerContainer", {
        width: 150,
        color: window.selectedColor,
    });
    colorPicker.on("color:change", (color) => {
        window.selectedColor = color.hexString;
        if (selectedModel) {
            selectedModel.traverse((child) => {
                if (child.isMesh) {
                    if (
                        (selectedParts.sleeves &&
                            (child.name === "Object_123" ||
                                child.name === "Object_125")) ||
                        (selectedParts.front && child.name === "Object_115") ||
                        (selectedParts.back && child.name === "Object_113") ||
                        (selectedParts.collar && child.name === "Object_121")
                    ) {
                        if (
                            child.material.emissive.getHexString() !==
                            color.hexString
                        ) {
                            child.material.emissive.set(window.selectedColor);
                            child.material.emissiveIntensity = 0.4;
                            child.material.needsUpdate = true;
                        }
                    }
                }
            });
            if (selectedParts.sleeves) {
                window.selectedSleevesColor = color.hexString;
            }
            if (selectedParts.front) {
                window.selectedFrontBodyColor = color.hexString;
            }
            if (selectedParts.back) {
                window.selectedBackColor = color.hexString;
            }
            if (selectedParts.collar) {
                window.selectedCollarColor = color.hexString;
            }
        }
    });
}

function setupToggleButtons() {
    document.getElementById("toggleSleeves").addEventListener("click", () => {
        selectedParts.sleeves = !selectedParts.sleeves;
        document
            .getElementById("toggleSleeves")
            .classList.toggle("bg-green-500", selectedParts.sleeves);
        document
            .getElementById("toggleSleeves")
            .classList.toggle("bg-accent", !selectedParts.sleeves);
    });
    document.getElementById("toggleFront").addEventListener("click", () => {
        selectedParts.front = !selectedParts.front;
        document
            .getElementById("toggleFront")
            .classList.toggle("bg-green-500", selectedParts.front);
        document
            .getElementById("toggleFront")
            .classList.toggle("bg-accent", !selectedParts.front);
    });
    document.getElementById("toggleBack").addEventListener("click", () => {
        selectedParts.back = !selectedParts.back;
        document
            .getElementById("toggleBack")
            .classList.toggle("bg-green-500", selectedParts.back);
        document
            .getElementById("toggleBack")
            .classList.toggle("bg-accent", !selectedParts.back);
    });
    document.getElementById("toggleCollar").addEventListener("click", () => {
        selectedParts.collar = !selectedParts.collar;
        document
            .getElementById("toggleCollar")
            .classList.toggle("bg-green-500", selectedParts.collar);
        document
            .getElementById("toggleCollar")
            .classList.toggle("bg-accent", !selectedParts.collar);
    });
}

function setupTextModal() {
    document
        .getElementById("openTextModalButton")
        .addEventListener("click", () => {
            openTextEditModal();
        });
}

function loadModel() {
    const modelPath = "/models/shirtmockup.gltf";
    const loader = new GLTFLoader();
    loader.load(
        modelPath,
        (gltf) => {
            selectedModel = gltf.scene;
            selectedModel.scale.set(5, 5, 5);
            selectedModel.position.y = -5.3;
            sleevesMesh = selectedModel.getObjectByName("Object_123");
            frontMesh = selectedModel.getObjectByName("Object_115");
            backMesh = selectedModel.getObjectByName("Object_113");
            collarMesh = selectedModel.getObjectByName("Object_121");
            scene.add(selectedModel);
        },
        undefined,
        (error) => {}
    );
}

function animate() {
    requestAnimationFrame(animate);
    controls.update();
    renderer.render(scene, camera);
    if (isSpinning && selectedModel) {
        selectedModel.rotation.y += 0.01;
    }
}

window.onload = () => {
    initializeScene();
};

function saveStateToSessionStorage() {
    const state = {
        selectedParts,
        sleevesColor: window.selectedSleevesColor,
        frontColor: window.selectedFrontBodyColor,
        backColor: window.selectedBackColor,
        collarColor: window.selectedCollarColor,
        textDecals: textDecals.map((decal) => ({
            type: decal.userData.type,
            text: decal.userData.text,
            fontSize: decal.userData.fontSize,
            textColor: decal.userData.textColor,
            position: decal.userData.position
                ? {
                      x: decal.userData.position.x,
                      y: decal.userData.position.y,
                      z: decal.userData.position.z,
                  }
                : null,
        })),
        isSpinning,
    };

    sessionStorage.setItem("tshirtCustomization", JSON.stringify(state));
    console.log("State saved to session storage");
}

// Function to load state from sessionStorage
function loadStateFromSessionStorage() {
    const savedState = sessionStorage.getItem("tshirtCustomization");
    if (!savedState) {
        console.log("No saved state found");
        return false;
    }

    try {
        const state = JSON.parse(savedState);
        console.log("Loading saved state:", state);

        // Restore selected parts
        selectedParts = state.selectedParts;

        // Update UI toggle buttons
        document
            .getElementById("toggleSleeves")
            .classList.toggle("bg-green-500", selectedParts.sleeves);
        document
            .getElementById("toggleSleeves")
            .classList.toggle("bg-accent", !selectedParts.sleeves);
        document
            .getElementById("toggleFront")
            .classList.toggle("bg-green-500", selectedParts.front);
        document
            .getElementById("toggleFront")
            .classList.toggle("bg-accent", !selectedParts.front);
        document
            .getElementById("toggleBack")
            .classList.toggle("bg-green-500", selectedParts.back);
        document
            .getElementById("toggleBack")
            .classList.toggle("bg-accent", !selectedParts.back);
        document
            .getElementById("toggleCollar")
            .classList.toggle("bg-green-500", selectedParts.collar);
        document
            .getElementById("toggleCollar")
            .classList.toggle("bg-accent", !selectedParts.collar);

        // Store colors
        window.selectedSleevesColor = state.sleevesColor;
        window.selectedFrontBodyColor = state.frontColor;
        window.selectedBackColor = state.backColor;
        window.selectedCollarColor = state.collarColor;

        // Restore isSpinning state
        isSpinning = state.isSpinning;
        const spinButton = document.getElementById("toggleSpin");
        const spinIcon = spinButton.querySelector("img");
        spinButton.classList.toggle("bg-green-500", isSpinning);
        spinButton.classList.toggle("bg-red-500", !isSpinning);
        if (isSpinning) {
            spinIcon.classList.add("animate-spin");
        } else {
            spinIcon.classList.remove("animate-spin");
        }

        // Try to apply colors immediately if model is loaded
        if (selectedModel) {
            applyColorsToModel();
            restoreTextDecals(state.textDecals);
        }

        // Set a more robust check for model loading
        let attempts = 0;
        const maxAttempts = 50;
        const checkModelInterval = setInterval(() => {
            attempts++;
            if (selectedModel) {
                clearInterval(checkModelInterval);
                console.log("Model loaded, applying colors and text decals");
                applyColorsToModel();
                restoreTextDecals(state.textDecals);
            } else if (attempts >= maxAttempts) {
                clearInterval(checkModelInterval);
                console.warn("Model not loaded after maximum attempts");
            }
        }, 500);

        return true;
    } catch (error) {
        console.error("Error loading state from session storage:", error);
        return false;
    }
}

// Function to apply saved colors to model
function applyColorsToModel() {
    if (!selectedModel) {
        console.warn("Model not loaded yet, can't apply colors");
        return;
    }

    console.log("Applying colors to model:", {
        sleeves: window.selectedSleevesColor,
        front: window.selectedFrontBodyColor,
        back: window.selectedBackColor,
        collar: window.selectedCollarColor,
    });

    selectedModel.traverse((child) => {
        if (child.isMesh) {
            if (child.name === "Object_123" || child.name === "Object_125") {
                // Apply sleeves color regardless of selectedParts for testing
                if (window.selectedSleevesColor) {
                    child.material.emissive.set(window.selectedSleevesColor);
                    child.material.emissiveIntensity = 0.4;
                    child.material.needsUpdate = true;
                    console.log(
                        "Applied sleeves color:",
                        window.selectedSleevesColor
                    );
                }
            } else if (child.name === "Object_115") {
                if (window.selectedFrontBodyColor) {
                    child.material.emissive.set(window.selectedFrontBodyColor);
                    child.material.emissiveIntensity = 0.4;
                    child.material.needsUpdate = true;
                    console.log(
                        "Applied front color:",
                        window.selectedFrontBodyColor
                    );
                }
            } else if (child.name === "Object_113") {
                if (window.selectedBackColor) {
                    child.material.emissive.set(window.selectedBackColor);
                    child.material.emissiveIntensity = 0.4;
                    child.material.needsUpdate = true;
                    console.log(
                        "Applied back color:",
                        window.selectedBackColor
                    );
                }
            } else if (child.name === "Object_121") {
                if (window.selectedCollarColor) {
                    child.material.emissive.set(window.selectedCollarColor);
                    child.material.emissiveIntensity = 0.4;
                    child.material.needsUpdate = true;
                    console.log(
                        "Applied collar color:",
                        window.selectedCollarColor
                    );
                }
            }
        }
    });
}

// Function to restore text decals
function restoreTextDecals(savedDecals) {
    if (
        !savedDecals ||
        !Array.isArray(savedDecals) ||
        savedDecals.length === 0
    ) {
        return;
    }

    savedDecals.forEach((decalData) => {
        if (decalData.type === "text" && decalData.text && decalData.position) {
            // Create decal canvas
            const decalCanvas = document.createElement("canvas");
            const dctx = decalCanvas.getContext("2d");
            decalCanvas.width = 512;
            decalCanvas.height = 256;
            dctx.clearRect(0, 0, decalCanvas.width, decalCanvas.height);
            dctx.font = `${decalData.fontSize}px Arial`;
            dctx.textAlign = "center";
            dctx.textBaseline = "middle";
            dctx.fillStyle = decalData.textColor;
            dctx.fillText(
                decalData.text,
                decalCanvas.width / 2,
                decalCanvas.height / 2
            );

            const decalTexture = new THREE.CanvasTexture(decalCanvas);
            decalTexture.needsUpdate = true;
            decalTexture.encoding = THREE.sRGBEncoding;

            const decalMaterial = new THREE.MeshBasicMaterial({
                map: decalTexture,
                transparent: true,
                depthTest: true,
                depthWrite: false,
                polygonOffset: true,
                polygonOffsetFactor: -4,
            });

            // Create position and orientation for the decal
            const position = new THREE.Vector3(
                decalData.position.x,
                decalData.position.y,
                decalData.position.z
            );

            // Get normal at this position
            const normal = new THREE.Vector3(0, 0, 1);
            normal.transformDirection(frontMesh.matrixWorld);

            const quaternion = new THREE.Quaternion();
            quaternion.setFromUnitVectors(new THREE.Vector3(0, 0, 1), normal);
            const orientation = new THREE.Euler().setFromQuaternion(quaternion);

            // Create and add the decal
            const scale = decalData.fontSize * 0.01;
            const size = new THREE.Vector3(scale * 4, scale * 2, 1);

            const decalGeometry = new DecalGeometry(
                frontMesh,
                position,
                orientation,
                size
            );

            const decalMesh = new THREE.Mesh(decalGeometry, decalMaterial);
            decalMesh.userData = {
                type: "text",
                text: decalData.text,
                fontSize: decalData.fontSize,
                textColor: decalData.textColor,
                position: position,
            };

            scene.add(decalMesh);
            textDecals.push(decalMesh);
            makeDecalEditable(decalMesh);
        }
    });
}

// Function to clear session storage
function clearCustomizationState() {
    sessionStorage.removeItem("tshirtCustomization");
    console.log("Customization state cleared from session storage");
}

// Add auto-save functionality - Save every 5 seconds and on important changes
function setupAutoSave() {
    // Save periodically
    setInterval(saveStateToSessionStorage, 5000);

    // Save on color changes
    const colorPicker = document.querySelector("#colorPickerContainer");
    if (colorPicker) {
        const observer = new MutationObserver(saveStateToSessionStorage);
        observer.observe(colorPicker, { attributes: true, subtree: true });
    }

    // Save when parts are toggled
    document
        .getElementById("toggleSleeves")
        .addEventListener("click", saveStateToSessionStorage);
    document
        .getElementById("toggleFront")
        .addEventListener("click", saveStateToSessionStorage);
    document
        .getElementById("toggleBack")
        .addEventListener("click", saveStateToSessionStorage);
    document
        .getElementById("toggleCollar")
        .addEventListener("click", saveStateToSessionStorage);

    // Save when text is added/modified
    const originalActivateTextDecalPlacement = activateTextDecalPlacement;
    window.activateTextDecalPlacement = function () {
        originalActivateTextDecalPlacement.apply(this, arguments);
        saveStateToSessionStorage();
    };

    const originalMakeDecalEditable = makeDecalEditable;
    window.makeDecalEditable = function () {
        originalMakeDecalEditable.apply(this, arguments);
        saveStateToSessionStorage();
    };
}

// Modify the document.addEventListener("DOMContentLoaded") to include our new functionality
document.addEventListener("DOMContentLoaded", function () {
    newcustomorder();

    // Load state from session storage after the scene is initialized
    setTimeout(() => {
        loadStateFromSessionStorage();
        setupAutoSave();
    }, 1000);
});

window.textDecals = textDecals;
