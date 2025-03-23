import Swal from "sweetalert2";

window.showAlert = function (title, text, icon, customClass = "") {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        background: "#171c2f", // Using Tailwind color directly (primary color)
        color: "#fff", // White text color
        confirmButtonColor: "#374256", // Tailwind danger color (bg-danger)
        cancelButtonColor: "#b2192b",
        toast: true, // Toast notification style
        position: "top-end", // Position it in the top-right corner
        showConfirmButton: false, // No confirm button
        timer: 3000, // Time before it auto-closes
        timerProgressBar: true, // Display a progress bar for the timer
        customClass: {
            popup: `${customClass} animate-[slide-in-toast]`, // Tailwind's animation class
        },
    });
};
