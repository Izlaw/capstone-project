import Swal from "sweetalert2";
import "./swalpopup.js"; // Import the swalpopup.js file

document.addEventListener("DOMContentLoaded", function () {
    const showModalButton = document.getElementById("showAddEmployeeButton");

    if (!showModalButton) {
        console.error("Add New Employee button not found");
        return;
    }

    // Function to show modal using SweetAlert
    function openAddEmployeeModal() {
        // Create a custom HTML content for the form
        const modalContent = `
            <form id="addEmployeeForm">
                <div class="form-group">
                    <label for="first_name" class="block font-medium text-white">First Name</label>
                    <input type="text" id="first_name" class="block w-full p-2 text-black rounded border-2 border-secondary" placeholder="First Name" required>
                </div>
                <div class="form-group mt-4">
                    <label for="last_name" class="block font-medium text-white">Last Name</label>
                    <input type="text" id="last_name" class="block w-full p-2 text-black rounded border-2 border-secondary" placeholder="Last Name" required>
                </div>
                <div class="form-group mt-4">
                    <label for="email" class="block font-medium text-white">Email</label>
                    <input type="email" id="email" class="block w-full p-2 text-black rounded border-2 border-secondary" placeholder="Email" required>
                </div>
                <div class="form-group mt-4">
                    <label for="password" class="block font-medium text-white">Password</label>
                    <input type="password" id="password" class="block w-full p-2 text-black rounded border-2 border-secondary" placeholder="Password" required>
                </div>
                <div class="form-group mt-4">
                    <label for="password_confirmation" class="block font-medium text-white">Confirm Password</label>
                    <input type="password" id="password_confirmation" class="block w-full p-2 text-black rounded border-2 border-secondary" placeholder="Confirm Password" required>
                </div>
                <div class="form-group mt-4">
                    <label for="sex" class="block font-medium text-white">Sex</label>
                    <select id="sex" class="block w-full p-2 text-black rounded border-2 border-secondary" required>
                        <option value="">Select Sex</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group mt-4">
                    <label for="bday" class="block font-medium text-white">Birthday</label>
                    <input type="date" id="bday" class="block w-full p-2 text-black rounded border-2 border-secondary" required>
                </div>
                <div class="form-group mt-4">
                    <label for="contact" class="block font-medium text-white">Contact Number</label>
                    <input type="tel" id="contact" class="block w-full p-2 text-black rounded border-2 border-secondary" placeholder="Contact Number" required>
                </div>
                <div class="form-group mt-4">
                    <label for="address" class="block font-medium text-white">Address</label>
                    <input type="text" id="address" class="block w-full p-2 text-black rounded border-2 border-secondary" placeholder="Address" required>
                </div>
            </form>
        `;

        Swal.fire({
            title: "Add New Employee",
            html: modalContent,
            showCancelButton: true,
            confirmButtonText: "Register",
            cancelButtonText: "Cancel",
            customClass: {
                popup: "bg-primary max-h-[80vh] overflow-y-auto", // Tailwind classes added here
                title: "text-highlight font-bold",
                confirmButton:
                    "bg-accent text-white hover:bg-highlight/80 border-2 border-primary transition hover:scale-110",
                cancelButton:
                    "bg-danger text-white hover:bg-danger/80 border-2 border-danger transition hover:scale-110",
            },
            preConfirm: () => {
                const firstName = document
                    .getElementById("first_name")
                    .value.trim();
                const lastName = document
                    .getElementById("last_name")
                    .value.trim();
                const email = document.getElementById("email").value.trim();
                const password = document.getElementById("password").value;
                const confirmPassword = document.getElementById(
                    "password_confirmation"
                ).value;
                const sex = document.getElementById("sex").value;
                const bday = document.getElementById("bday").value;
                const contact = document.getElementById("contact").value.trim();
                const address = document.getElementById("address").value.trim();

                // Basic validation
                if (
                    !firstName ||
                    !lastName ||
                    !email ||
                    !password ||
                    !confirmPassword ||
                    !sex ||
                    !bday ||
                    !contact ||
                    !address
                ) {
                    Swal.showValidationMessage("All fields are required.");
                    return false;
                }

                if (password !== confirmPassword) {
                    Swal.showValidationMessage("Passwords do not match.");
                    return false;
                }

                // Calculate age
                const birthDate = new Date(bday);
                const today = new Date();
                const age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (
                    monthDiff < 0 ||
                    (monthDiff === 0 && today.getDate() < birthDate.getDate())
                ) {
                    age--;
                }

                // Check if date is in the future
                if (birthDate > today) {
                    Swal.showValidationMessage(
                        "Birthday cannot be set to a future date."
                    );
                    return false;
                }

                // Check if age is less than 18
                if (age < 18) {
                    Swal.showValidationMessage(
                        "Employee must be at least 18 years old."
                    );
                    return false;
                }

                // AJAX request to validate email and contact
                return fetch("/api/validateUser")
                    .then((response) => response.json())
                    .then((users) => {
                        const emailExists = users.some(
                            (user) => user.email === email
                        );
                        const contactExists = users.some(
                            (user) => user.contact === contact
                        );

                        if (emailExists) {
                            Swal.fire({
                                title: "Error!",
                                text: "Email is already in use.",
                                icon: "error",
                                customClass: {
                                    popup: "bg-primary animate-[slide-in-toast] text-white",
                                    title: "text-highlight font-bold",
                                    confirmButton:
                                        "bg-danger text-white hover:bg-danger/80 border-2 border-danger transition hover:scale-110",
                                },
                                confirmButtonText: "Okay",
                            });
                            return false;
                        }

                        if (contactExists) {
                            Swal.fire({
                                title: "Error!",
                                text: "Contact number is already in use.",
                                icon: "error",
                                customClass: {
                                    popup: "bg-primary animate-[slide-in-toast]",
                                    title: "text-highlight font-bold",
                                    confirmButton:
                                        "bg-danger text-white hover:bg-danger/80 border-2 border-danger transition hover:scale-110",
                                },
                                confirmButtonText: "Okay",
                            });
                            return false;
                        }

                        return {
                            first_name: firstName,
                            last_name: lastName,
                            email: email,
                            password: password,
                            password_confirmation: confirmPassword,
                            sex: sex,
                            bday: bday,
                            contact: contact,
                            address: address,
                        };
                    })
                    .catch((error) => {
                        Swal.fire({
                            title: "Error!",
                            text: `Request failed: ${error}`,
                            icon: "error",
                            customClass: {
                                popup: "bg-primary animate-[slide-in-toast]",
                                title: "text-highlight font-bold",
                                confirmButton:
                                    "bg-danger text-white hover:bg-danger/80 border-2 border-danger transition hover:scale-110",
                            },
                            confirmButtonText: "Okay",
                        });
                        return false;
                    });
            },
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                // Emit the addEmployee event with form data to Livewire
                window.Livewire.emit("addEmployee", result.value);
                showAlert(
                    "Success!",
                    "Employee added successfully!",
                    "success"
                );
            }
        });
    }

    // Function to show edit modal using SweetAlert
    // Function to show edit modal using SweetAlert
    function openEditEmployeeModal(employee) {
        console.log(employee); // Check if employee data is correct here

        // Create a custom HTML content for the form
        const modalContent = `
        <form id="editEmployeeForm">
            <div class="form-group">
                <label for="first_name" class="block font-medium text-white">First Name</label>
                <input type="text" id="first_name" class="block w-full p-2 text-black rounded border-2 border-secondary" value="${
                    employee.first_name || ""
                }" placeholder="First Name">
            </div>
            <div class="form-group mt-4">
                <label for="last_name" class="block font-medium text-white">Last Name</label>
                <input type="text" id="last_name" class="block w-full p-2 text-black rounded border-2 border-secondary" value="${
                    employee.last_name || ""
                }" placeholder="Last Name">
            </div>
            <div class="form-group mt-4">
                <label for="email" class="block font-medium text-white">Email</label>
                <input type="email" id="email" class="block w-full p-2 text-black rounded border-2 border-secondary" value="${
                    employee.email || ""
                }" placeholder="Email">
            </div>
            <div class="form-group mt-4">
                <label for="sex" class="block font-medium text-white">Gender</label>
                <select id="sex" class="block w-full p-2 text-black rounded border-2 border-secondary">
                    <option value="">Select Gender</option>
                    <option value="male" ${
                        employee.sex === "male" ? "selected" : ""
                    }>Male</option>
                    <option value="female" ${
                        employee.sex === "female" ? "selected" : ""
                    }>Female</option>
                    <option value="other" ${
                        employee.sex === "other" ? "selected" : ""
                    }>Other</option>
                </select>
            </div>
            <div class="form-group mt-4">
                <label for="bday" class="block font-medium text-white">Birthday</label>
                <input type="date" id="bday" class="block w-full p-2 text-black rounded border-2 border-secondary" value="${
                    employee.bday || ""
                }">
            </div>
            <div class="form-group mt-4">
                <label for="contact" class="block font-medium text-white">Contact Number</label>
                <input type="tel" id="contact" class="block w-full p-2 text-black rounded border-2 border-secondary" value="${
                    employee.contact || ""
                }" placeholder="Contact Number">
            </div>
            <div class="form-group mt-4">
                <label for="address" class="block font-medium text-white">Address</label>
                <input type="text" id="address" class="block w-full p-2 text-black rounded border-2 border-secondary" value="${
                    employee.address || ""
                }" placeholder="Address">
            </div>
        </form>
    `;

        Swal.fire({
            title: "Edit Employee Profile",
            html: modalContent,
            showCancelButton: true,
            confirmButtonText: "Update",
            cancelButtonText: "Cancel",
            customClass: {
                popup: "bg-primary max-h-[80vh] overflow-y-auto", // same tailwind classes
                title: "text-highlight font-bold",
                confirmButton:
                    "bg-accent text-white hover:bg-highlight/80 hover:scale-110 border-2 border-primary transition",
                cancelButton:
                    "bg-danger text-white hover:bg-danger/80 hover:scale-110 border-2 border-danger transition",
            },
            preConfirm: () => {
                const firstName = document
                    .getElementById("first_name")
                    .value.trim();
                const lastName = document
                    .getElementById("last_name")
                    .value.trim();
                const email = document.getElementById("email").value.trim();
                const sex = document.getElementById("sex").value;
                const bday = document.getElementById("bday").value;
                const contact = document.getElementById("contact").value.trim();
                const address = document.getElementById("address").value.trim();

                // Calculate age
                const birthDate = new Date(bday);
                const today = new Date();
                const age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (
                    monthDiff < 0 ||
                    (monthDiff === 0 && today.getDate() < birthDate.getDate())
                ) {
                    age--;
                }

                // Check if date is in the future
                if (birthDate > today) {
                    Swal.showValidationMessage(
                        "Birthday cannot be set to a future date."
                    );
                    return false;
                }

                // Check if age is less than 18
                if (age < 18) {
                    Swal.showValidationMessage(
                        "You must be at least 18 years old."
                    );
                    return false;
                }

                // AJAX request to validate email and contact
                return fetch("/api/validateUser")
                    .then((response) => response.json())
                    .then((users) => {
                        const emailExists = users.some(
                            (user) =>
                                user.email === email && user.id !== employee.id
                        );
                        const contactExists = users.some(
                            (user) =>
                                user.contact === contact &&
                                user.id !== employee.id
                        );

                        if (emailExists) {
                            Swal.fire({
                                title: "Error!",
                                text: "Email is already in use.",
                                icon: "error",
                                customClass: {
                                    popup: "bg-primary animate-[slide-in-toast]",
                                    title: "text-highlight font-bold",
                                    confirmButton:
                                        "bg-danger text-white hover:bg-danger/80 border-2 border-danger transition hover:scale-110",
                                },
                                confirmButtonText: "Okay",
                            });
                            return false;
                        }

                        if (contactExists) {
                            Swal.fire({
                                title: "Error!",
                                text: "Contact number is already in use.",
                                icon: "error",
                                customClass: {
                                    popup: "bg-primary animate-[slide-in-toast]",
                                    title: "text-highlight font-bold",
                                    confirmButton:
                                        "bg-danger text-white hover:bg-danger/80 border-2 border-danger transition hover:scale-110",
                                },
                                confirmButtonText: "Okay",
                            });
                            return false;
                        }

                        return {
                            first_name: firstName,
                            last_name: lastName,
                            email: email,
                            sex: sex,
                            bday: bday,
                            contact: contact,
                            address: address,
                        };
                    })
                    .catch((error) => {
                        Swal.fire({
                            title: "Error!",
                            text: `Request failed: ${error}`,
                            icon: "error",
                            customClass: {
                                popup: "bg-primary animate-[slide-in-toast]",
                                title: "text-highlight font-bold",
                                confirmButton:
                                    "bg-danger text-white hover:bg-danger/80 border-2 border-danger transition hover:scale-110",
                            },
                            confirmButtonText: "Okay",
                        });
                        return false;
                    });
            },
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                // Emit the editEmployee event with form data to Livewire
                window.Livewire.emit("editEmployee", result.value);
                showAlert(
                    "Success!",
                    "Employee updated successfully!",
                    "success"
                );
            }
        });
    }

    document.addEventListener("show-edit-employee-form", function (e) {
        openEditEmployeeModal(e.detail.employee);
    });

    showModalButton.addEventListener("click", openAddEmployeeModal);
});
