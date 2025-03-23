import Swal from "sweetalert2";

// Import necessary functions and variables from calculation.js
import { calculateTotal, sizeMapping, selectedSizes } from "./calculation.js";

// Global variable to track which part is being colored. It can be 'back', 'front', 'sleeves', or 'collar'.
// Set this when the user clicks on a specific toggle button.
window.currentColorTarget = null;
// Also, store the last color chosen for the current target.
window.currentColorValue = null;

export function showAlert(title, text, icon, customClass = "") {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        background: "#171c2f", // Custom background color
        color: "#fff", // Text color
        confirmButtonColor: "#b2192b", // Custom button color
        toast: true, // Toast notification style
        position: "top-end", // Position it in the top-right corner
        showConfirmButton: false, // No confirm button
        timer: 3000, // Time before it auto-closes
        timerProgressBar: true, // Display a progress bar for the timer
        customClass: {
            popup: customClass, // Use the custom class passed for this specific instance
        },
    });
}

export function showQRCodeModal(qrCodeHtml, customizations) {
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    const modalHtml = `
    <div id="qrCodeModal" class="fixed bg-black bg-opacity-50 inset-0 flex items-center justify-center z-50">
        <div class="bg-white p-5 rounded-lg shadow-lg text-center max-w-md w-full">
            ${qrCodeHtml}
            <button id="closeModal" class="mt-4 p-2 bg-red-500 text-white rounded hover:bg-red-600 transition duration-300">Close</button>
        </div>
    </div>
    `;

    document.body.insertAdjacentHTML("beforeend", modalHtml);
    document.getElementById("closeModal").addEventListener("click", () => {
        document.getElementById("qrCodeModal").remove();
    });
}

export async function newcustomorder() {
    const confirmOrderBtn = document.getElementById("confirmOrder");

    if (confirmOrderBtn) {
        confirmOrderBtn.addEventListener("click", async () => {
            console.log("Collected Sizes:", selectedSizes); // Debugging line

            if (selectedSizes.length === 0) {
                showAlert(
                    "No Sizes Selected",
                    "Please select at least one size.",
                    "error"
                );
                return;
            }

            const sizes = selectedSizes
                .map((size) => {
                    const sizeKey = `size_${size.size.toUpperCase()}`;
                    const sizeData = sizeMapping[sizeKey];
                    if (!sizeData) {
                        console.error(
                            `Size data not found for key: ${sizeKey}`
                        );
                        return null;
                    }
                    return {
                        sizeID: sizeData.id,
                        quantity: size.quantity,
                    };
                })
                .filter((size) => size !== null);

            const fabricTypeSelect = document.getElementById("fabric_type");
            const fabricType = fabricTypeSelect.value.trim();
            const customFabricTypeInput =
                document.getElementById("custom_fabric_type");
            const customFabricType = customFabricTypeInput.value.trim();

            if (!fabricType) {
                showAlert(
                    "Invalid Fabric",
                    "Please enter a fabric type before proceeding.",
                    "error"
                );
                return;
            }

            // Use the global currentColorValue based on the current target.
            // If the user hasn't set a target, use previously stored values.
            let backColor = window.selectedBackColor || "#ffffff";
            let frontColor = window.selectedFrontBodyColor || "#ffffff";
            let sleevesColor = window.selectedSleevesColor || "#000000";
            let collarColor = window.selectedCollarColor || "#000000";

            if (window.currentColorTarget) {
                // Update the corresponding color with the current value.
                if (window.currentColorTarget === "back") {
                    backColor = window.currentColorValue;
                    window.selectedBackColor = window.currentColorValue;
                } else if (window.currentColorTarget === "front") {
                    frontColor = window.currentColorValue;
                    window.selectedFrontBodyColor = window.currentColorValue;
                } else if (window.currentColorTarget === "sleeves") {
                    sleevesColor = window.currentColorValue;
                    window.selectedSleevesColor = window.currentColorValue;
                } else if (window.currentColorTarget === "collar") {
                    collarColor = window.currentColorValue;
                    window.selectedCollarColor = window.currentColorValue;
                }
            }

            console.log("Colors Selected:", {
                backColor,
                frontColor,
                sleevesColor,
                collarColor,
            }); // Debugging line

            const colors = {
                backColor: backColor,
                frontColor: frontColor,
                sleevesColor: sleevesColor,
                collarColor: collarColor,
            };

            // Get all text decals from the global variable
            const textDecalsArray = window.textDecals || [];
            let textCustomization = null;

            // Process all text decals and format them in the specified format
            if (textDecalsArray.length > 0) {
                // Create individual text customization objects
                let textCustomizationsStr = "";

                textDecalsArray.forEach((decal) => {
                    const singleTextCustomization = {
                        text: decal.userData.text,
                        fontSize: decal.userData.fontSize + "px",
                        fontColor: decal.userData.textColor,
                        fontPosition: {
                            x: decal.userData.position.x.toFixed(2),
                            y: decal.userData.position.y.toFixed(2),
                            z: decal.userData.position.z.toFixed(2),
                        },
                    };

                    // Add this object's JSON representation to the string (no commas between objects)
                    textCustomizationsStr += JSON.stringify(
                        singleTextCustomization
                    );
                });

                // Store the formatted string in textCustomization
                textCustomization = textCustomizationsStr;
            }

            console.log("Text Customization:", textCustomization); // Debugging line

            // Build the customizations object to send to the backend
            const customizations = {
                sizes: sizes,
                fabric_type:
                    fabricType === "custom" ? customFabricType : fabricType,
                colors: colors,
                text: textCustomization, // Use the formatted string with multiple text customizations
            };

            console.log("Customizations:", customizations); // Debugging line

            // Proceed to send the data with fetch as before
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            try {
                const qrResponse = await fetch("/qrcode", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify(customizations),
                });

                if (qrResponse.ok) {
                    const qrCodeHtml = await qrResponse.text();
                    showQRCodeModal(qrCodeHtml, customizations);

                    // Send the QR code to a random employee
                    const sendQRCodeResponse = await fetch("/send-qrcode", {
                        method: "GET",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                        },
                    });

                    if (!sendQRCodeResponse.ok) {
                        console.error(
                            "Failed to send QR code to the employee."
                        );
                    }

                    // Generate billing statement without triggering download
                    const billingResponse = await fetch(
                        "/generate-billing-statement",
                        {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken,
                            },
                            body: JSON.stringify(customizations),
                        }
                    );

                    if (billingResponse.ok) {
                        const data = await billingResponse.json();
                        console.log("Billing statement generated:", data);
                        showAlert(
                            "Billing Statement Generated",
                            "Your billing statement has been generated successfully.",
                            "success",
                            "slide-in-toast"
                        );

                        if (data.fileUrl) {
                            showAlert(
                                "Billing Statement Ready",
                                "You can download your Billing Statement in the View Order page",
                                "success"
                            );
                        }
                    } else {
                        console.error(
                            "Failed to generate billing statement:",
                            billingResponse.status
                        );
                    }
                } else {
                    console.error(
                        "Failed to fetch QR code view:",
                        qrResponse.status,
                        qrResponse.statusText
                    );
                }
            } catch (error) {
                console.error("Error fetching QR code view:", error);
            }
        });
    }
}
