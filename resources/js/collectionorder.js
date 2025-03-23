import Swal from "sweetalert2";
import "./swalpopup.js";

// Initialize the sizeMapping object
let sizeMapping = {};

// Fetch sizes data from API
async function fetchSizes() {
    try {
        const response = await fetch("/api/sizes");
        if (!response.ok) throw new Error("Network response was not ok");
        const sizes = await response.json();
        sizeMapping = sizes.reduce((acc, size) => {
            acc[size.sizeName] = {
                id: size.sizeID,
                price: parseFloat(size.sizePrice),
            };
            return acc;
        }, {});
        return sizes;
    } catch (error) {
        console.error("Failed to fetch sizes:", error);
        return [];
    }
}

// Function to create a size and quantity row
function createSizeRow(sizes, selectedSize = "", selectedQuantity = 0) {
    const row = document.createElement("tr");
    row.className = "border-b border-gray-200";

    const sizeCell = document.createElement("td");
    sizeCell.className = "py-2 px-4 border-b border-gray-200";

    const sizeDropdown = document.createElement("select");
    // Use a placeholder option that is disabled so that users cannot submit an empty value.
    sizeDropdown.name = "sizes[]";
    sizeDropdown.className =
        "sizeDropdown border rounded-md p-2 w-full text-black bg-white hover:bg-gray-300 transition-colors duration-300 focus:outline-none";
    sizeDropdown.innerHTML =
        `<option value="" disabled selected>Select Size</option>` +
        sizes
            .map(
                (size) =>
                    `<option value="${size.sizeName}" data-id="${size.sizeID}">${size.sizeName}</option>`
            )
            .join("");
    if (selectedSize) sizeDropdown.value = selectedSize;
    sizeCell.appendChild(sizeDropdown);
    row.appendChild(sizeCell);

    const priceCell = document.createElement("td");
    priceCell.className = "py-2 px-4 border-b border-gray-200 price-column";
    priceCell.style.width = "200px";
    priceCell.textContent = selectedSize
        ? `₱${sizeMapping[selectedSize].price}`
        : "₱0.00";
    row.appendChild(priceCell);

    const quantityCell = document.createElement("td");
    quantityCell.className = "py-2 px-4 border-b border-gray-200";
    const quantityInput = document.createElement("input");
    quantityInput.type = "number";
    quantityInput.name = "quantities[]";
    quantityInput.min = "0";
    quantityInput.value = selectedQuantity;
    quantityInput.className =
        "quantityInput text-center border rounded-md w-3/4 text-black bg-white hover:bg-gray-300 transition-colors duration-300";
    quantityCell.appendChild(quantityInput);
    row.appendChild(quantityCell);

    const deleteCell = document.createElement("td");
    deleteCell.className = "py-2 px-4 border-b border-gray-200";
    const deleteButton = document.createElement("button");
    deleteButton.type = "button";
    deleteButton.className =
        "bg-red-500 text-white py-1 px-2 rounded hover:scale-110 hover:bg-highlight transition";
    deleteButton.textContent = "Delete";
    deleteButton.addEventListener("click", () => {
        row.remove();
        updateDropdownOptions();
        updateTotalAmountDisplay();
    });
    deleteCell.appendChild(deleteButton);
    row.appendChild(deleteCell);

    sizeDropdown.addEventListener("change", () => {
        const selectedSize = sizeDropdown.value;
        if (sizeMapping[selectedSize])
            priceCell.textContent = `₱${sizeMapping[selectedSize].price}`;
        updateDropdownOptions();
        updateTotalAmountDisplay();
    });

    quantityInput.addEventListener("input", updateTotalAmountDisplay);

    return row;
}

// Function to update dropdown options based on selected sizes
function updateDropdownOptions() {
    const selectedSizes = Array.from(document.querySelectorAll(".sizeDropdown"))
        .map((dropdown) => dropdown.value)
        .filter((value) => value !== "");
    document.querySelectorAll(".sizeDropdown").forEach((dropdown) => {
        const currentValue = dropdown.value;
        const options = Array.from(dropdown.querySelectorAll("option"));
        options.forEach((option) => {
            if (option.value !== "" && option.value !== currentValue)
                option.remove();
        });
        Object.keys(sizeMapping).forEach((key) => {
            const sizeName = key;
            if (!selectedSizes.includes(sizeName) || sizeName === currentValue) {
                if (!Array.from(dropdown.options).some((option) => option.value === sizeName)) {
                    const option = document.createElement("option");
                    option.value = sizeName;
                    option.textContent = sizeName;
                    option.dataset.id = sizeMapping[sizeName].id;
                    dropdown.appendChild(option);
                }
            }
        });
    });
}

// Function to calculate total price based on the selected sizes and quantities
function calculateTotal() {
    let totalAmount = 0;
    const sizeDropdowns = document.querySelectorAll(".sizeDropdown");
    const quantityInputs = document.querySelectorAll(".quantityInput");
    sizeDropdowns.forEach((dropdown, index) => {
        const selectedSize = dropdown.value;
        const quantity = parseInt(quantityInputs[index].value) || 0;
        if (sizeMapping[selectedSize])
            totalAmount += sizeMapping[selectedSize].price * quantity;
    });
    return totalAmount;
}

// Function to update the total amount display
function updateTotalAmountDisplay() {
    const totalAmount = calculateTotal();
    document.getElementById("totalAmountDisplay").innerText =
        `Total Amount: ₱${totalAmount.toLocaleString("en-US", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        })}`;
}

// Function to handle the size selection process
async function handleSizeSelection() {
    const sizes = await fetchSizes();
    const modalContent = document.createElement("div");
    modalContent.id = "modalSizeInputsContainer";
    const table = document.createElement("table");
    table.className = "min-w-full bg-primary border-collapse";
    table.innerHTML = `
        <thead>
            <tr class="border-b border-gray-200">
                <th class="py-2 px-4 bg-accent text-center text-xs font-semibold text-white uppercase tracking-wider border-b border-gray-200">Size</th>
                <th class="py-2 px-4 bg-accent text-center text-xs font-semibold text-white uppercase tracking-wider price-column border-b border-gray-200" style="width: 200px;">Price</th>
                <th class="py-2 px-4 bg-accent text-center text-xs font-semibold text-white uppercase tracking-wider border-b border-gray-200">Quantity</th>
                <th class="py-2 px-4 bg-accent text-center text-xs font-semibold text-white uppercase tracking-wider border-b border-gray-200">Action</th>
            </tr>
        </thead>
        <tbody id="sizeTableBody"></tbody>
    `;
    const tbody = table.querySelector("#sizeTableBody");
    tbody.appendChild(createSizeRow(sizes));
    modalContent.appendChild(table);

    const totalAmountDisplay = document.createElement("div");
    totalAmountDisplay.id = "totalAmountDisplay";
    totalAmountDisplay.className = "text-right font-semibold mt-4";
    totalAmountDisplay.innerText = `Total Amount: ₱${calculateTotal().toLocaleString(
        "en-US",
        { minimumFractionDigits: 2, maximumFractionDigits: 2 }
    )}`;
    modalContent.appendChild(totalAmountDisplay);

    const addSizeButton = document.createElement("button");
    addSizeButton.type = "button";
    addSizeButton.classList.add(
        "bg-secondary",
        "text-white",
        "py-2",
        "px-4",
        "rounded",
        "hover:bg-highlight",
        "hover:scale-110",
        "transition",
        "mt-2"
    );
    addSizeButton.textContent = "Add Size";
    addSizeButton.addEventListener("click", () => {
        tbody.appendChild(createSizeRow(sizes));
        updateDropdownOptions();
        updateTotalAmountDisplay();
    });
    modalContent.appendChild(addSizeButton);

    return Swal.fire({
        title: "Select Sizes and Quantities",
        html: modalContent,
        customClass: {
            popup: "bg-primary text-white shadow-lg rounded-lg w-96",
            title: "text-xl font-semibold mb-4",
            content: "text-lg",
            closeButton: "transition-transform transform hover:scale-125 hover:text-danger",
            confirmButton: "bg-secondary text-white py-2 px-4 rounded hover:bg-highlight hover:scale-110 transition",
        },
        showCloseButton: true,
        showConfirmButton: true,
        confirmButtonText: "Confirm Order",
        preConfirm: () => {
            const sizeDropdowns = document.querySelectorAll(".sizeDropdown");
            const quantityInputs = document.querySelectorAll(".quantityInput");

            // Extract data-id for each selected size
            const sizeIds = Array.from(sizeDropdowns).map(dropdown => {
                const selectedOption = dropdown.options[dropdown.selectedIndex];
                return selectedOption ? selectedOption.dataset.id : "";
            });

            const quantities = Array.from(quantityInputs).map(input => input.value || "");

            if (sizeIds.some(id => !id)) {
                Swal.showValidationMessage("Please select a valid size for each row");
                return false;
            }
            if (quantities.some(qty => qty === "")) {
                Swal.showValidationMessage("Please enter a quantity for each size");
                return false;
            }
            if (quantities.some(qty => isNaN(qty) || parseInt(qty, 10) <= 0)) {
                Swal.showValidationMessage("Quantities must be positive numbers");
                return false;
            }
            return { sizes: sizeIds, quantities: quantities };
        },
        width: "800px",
    }).then((result) => (result.isConfirmed ? result.value : null));
}

// Function to handle the collection order process
async function handleCollectionOrder(collection) {
    const sizeSelectionResult = await handleSizeSelection();
    if (sizeSelectionResult) {
        const { sizes, quantities } = sizeSelectionResult;
        // Log the collected data for debugging
        console.log("Collection:", collection);
        console.log("Sizes (IDs):", sizes);
        console.log("Quantities:", quantities);

        // Send the order data to the backend
        const response = await fetch("/collection-order", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            },
            body: JSON.stringify({
                collectID: collection.collectID,
                sizes: sizes,
                quantities: quantities,
            }),
        });

        if (response.redirected) {
            window.location.href = response.url;
        } else {
            const result = await response.json();
            if (result.success) {
                window.location.href = `/chat?convoID=${result.convoID}`;
            } else {
                Swal.fire({
                    title: "Error",
                    text: result.message,
                    icon: "error",
                    confirmButtonText: "OK",
                    customClass: {
                        popup: "bg-primary text-white shadow-lg rounded-lg w-96",
                        title: "text-xl font-semibold mb-4",
                        content: "text-lg",
                        closeButton: "transition-transform transform hover:scale-125 hover:text-danger",
                        confirmButton: "bg-secondary text-white py-2 px-4 rounded hover:bg-highlight hover:scale-110 transition",
                    },
                });
            }
        }
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const collectionsContainer = document.getElementById("collectionsContainer");
    collectionsContainer.addEventListener("click", (event) => {
        const collectionItem = event.target.closest(".collection-item");
        if (collectionItem) {
            const collection = JSON.parse(collectionItem.getAttribute("data-collection"));
            handleCollectionOrder(collection);
        }
    });
});

export { handleCollectionOrder };
