import Swal from "sweetalert2";

// Listen for success alert event
window.addEventListener("show-success-alert", (event) => {
    const { title, text, icon, customClass } = event.detail;
    showAlert(title, text, icon, customClass); // Call your custom showAlert function
});

// Centralized showAlert function using Tailwind's custom animation
function showAlert(title, text, icon, customClass = "") {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        background: "#171c2f", // Using Tailwind color directly (primary color)
        color: "#fff", // White text color
        confirmButtonColor: "#b2192b", // Tailwind danger color (bg-danger)
        toast: true, // Toast notification style
        position: "top-end", // Position it in the top-right corner
        showConfirmButton: false, // No confirm button
        timer: 3000, // Time before it auto-closes
        timerProgressBar: true, // Display a progress bar for the timer
        customClass: {
            popup: `${customClass} animate-[slide-in-toast]`, // Tailwind's animation class
        },
    });
}

// Inside manageorder.js or an appropriate JavaScript file
window.addEventListener("open-status-modal", (event) => {
    const { orderId, currentStatus } = event.detail;

    // Prompt user with SweetAlert to select a new status
    Swal.fire({
        title: "Update Order Status",
        input: "select",
        inputOptions: {
            Pending: "Pending",
            "In Progress": "In Progress",
            "Ready for Pickup": "Ready for Pickup",
            Completed: "Completed",
            Cancelled: "Cancelled",
        },
        inputValue: currentStatus,
        showCancelButton: true,
        confirmButtonText: "Update",
        cancelButtonText: "Cancel",

        // Using Tailwind's colors directly as CSS inline styles
        background: "#171c2f", // Primary color
        color: "#fff", // Text color (white)
        confirmButtonColor: "#232f49", // Danger button color (red)
        cancelButtonColor: "#b2192b", // Secondary color for cancel button

        customClass: {
            popup: "animate-[slide-in-toast]", // Tailwind's animation class for the popup
            input: "bg-secondary text-white", // Apply Tailwind class for dropdown background
            confirmButton: "bg-danger", // Customize confirm button if needed
            cancelButton: "bg-secondary", // Customize cancel button if needed
        },

        didOpen: () => {
            // Custom style for dropdown options
            const selectElement = Swal.getInput();

            // Accessing the options inside the select element
            const options = selectElement.querySelectorAll("option");

            // Apply custom styles for dropdown options
            options.forEach((option) => {
                option.style.backgroundColor = "#171c2f"; // Example background color
                option.style.color = "#fff"; // White text color for the options
            });

            // Apply custom style for selected option (checked option)
            const selectedOption =
                selectElement.querySelector("option:checked");
            if (selectedOption) {
                selectedOption.style.backgroundColor = "#374256"; // Danger color for selected option
                selectedOption.style.color = "#fff"; // White text color for the selected option
            }
        },
    }).then((result) => {
        if (result.isConfirmed && result.value !== currentStatus) {
            const newStatus = result.value;

            // Emit event to update the status in Livewire
            window.Livewire.emit("updateOrderStatus", orderId, newStatus);
        }
    });
});

// Listen for the 'order-received' event dispatched by Livewire
window.addEventListener("order-received", (event) => {
    const { orderId, message } = event.detail;

    // Show the success alert with SweetAlert
    Swal.fire({
        title: "Success",
        text: message,
        icon: "success",
        background: "#171c2f", // Primary background color
        color: "#fff", // Text color
        confirmButtonColor: "#b2192b", // Button color
        toast: true, // Toast notification style
        position: "top-end", // Position it at the top-right corner
        showConfirmButton: false, // No confirm button
        timer: 3000, // Time before it auto-closes
        timerProgressBar: true, // Show progress bar for the timer
    });

    // Update the UI if necessary (for example, by updating the order status visually in the table)
    // You can either trigger a full re-render or use Livewire methods to update just the affected order
    // Example: Update order row status manually (could be optimized with Livewire updates)
    const orderRow = document.querySelector(`#order-row-${orderId}`);
    if (orderRow) {
        const statusCell = orderRow.querySelector(".order-status");
        if (statusCell) {
            statusCell.textContent = "Received"; // Update the order status text
            orderRow.classList.add("bg-green-500"); // Optionally add a class for visual change
        }
    }
});
