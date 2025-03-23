import Swal from "sweetalert2";

window.addEventListener("showCreateCollectionPopup", function () {
    Swal.fire({
        title: "<span style='color: #fff;'>Create Collection</span>",
        html: `
            <input type="text" id="collectName" class="swal2-input" placeholder="Collection Name" style="color: #fff; background-color: #2d3748; border: 1px solid #4a5568;">
            <input type="number" id="collectPrice" class="swal2-input" placeholder="Collection Price" style="color: #fff; background-color: #2d3748; border: 1px solid #4a5568;">
            <input type="file" id="collectImage" class="swal2-input" style="color: #fff; background-color: #2d3748; border: 1px solid #4a5568;">
        `,
        showCancelButton: true,
        confirmButtonText: "Create",
        cancelButtonText: "Cancel",
        background: "#171c2f", // Using Tailwind color directly (primary color)
        color: "#fff", // White text color
        confirmButtonColor: "#b2192b", // Tailwind danger color (bg-danger)
        cancelButtonColor: "#6b7280", // Tailwind gray color
        preConfirm: () => {
            const collectName =
                Swal.getPopup().querySelector("#collectName").value;
            const collectPrice =
                Swal.getPopup().querySelector("#collectPrice").value;
            const collectImage =
                Swal.getPopup().querySelector("#collectImage").files[0];
            if (!collectName || !collectPrice || !collectImage) {
                Swal.showValidationMessage(`Please enter all fields`);
            }
            return {
                collectName: collectName,
                collectPrice: collectPrice,
                collectImage: collectImage,
            };
        },
    }).then((result) => {
        if (result.isConfirmed) {
            console.log("Creating collection with data:", result.value);
            const formData = new FormData();
            formData.append("collectName", result.value.collectName);
            formData.append("collectPrice", result.value.collectPrice);
            formData.append("image", result.value.collectImage);

            Livewire.emit("createCollection", {
                collectName: result.value.collectName,
                collectPrice: result.value.collectPrice,
                image: result.value.collectImage,
            });
        }
    });
});

window.addEventListener("showEditCollectionPopup", function (event) {
    const { collectID, collectName, collectPrice, collectFilePath } =
        event.detail;
    Swal.fire({
        title: "<span style='color: #fff;'>Edit Collection</span>",
        html: `
            <input type="text" id="collectName" class="swal2-input" placeholder="Collection Name" value="${collectName}" style="color: #fff; background-color: #2d3748; border: 1px solid #4a5568;">
            <input type="number" id="collectPrice" class="swal2-input" placeholder="Collection Price" value="${collectPrice}" style="color: #fff; background-color: #2d3748; border: 1px solid #4a5568;">
        `,
        showCancelButton: true,
        confirmButtonText: "Save",
        cancelButtonText: "Cancel",
        background: "#171c2f", // Using Tailwind color directly (primary color)
        color: "#fff", // White text color
        confirmButtonColor: "#b2192b", // Tailwind danger color (bg-danger)
        cancelButtonColor: "#6b7280", // Tailwind gray color
        preConfirm: () => {
            const collectName =
                Swal.getPopup().querySelector("#collectName").value;
            const collectPrice =
                Swal.getPopup().querySelector("#collectPrice").value;
            if (!collectName || !collectPrice) {
                Swal.showValidationMessage(`Please enter all fields`);
            }
            return {
                collectID: collectID,
                collectName: collectName,
                collectPrice: collectPrice,
            };
        },
    }).then((result) => {
        if (result.isConfirmed) {
            console.log("Updating collection with data:", result.value);
            Livewire.emit("updateCollection", {
                collectID: result.value.collectID,
                collectName: result.value.collectName,
                collectPrice: result.value.collectPrice,
            });
            showAlert(
                "Saved!",
                "The collection has been updated successfully.",
                "success",
                "animate-[slide-in-toast]"
            );
        }
    });
});

window.addEventListener("showDeleteCollectionPopup", function (event) {
    const { collectID } = event.detail;
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            console.log("Deleting collection with ID:", collectID);
            Livewire.emit("deleteCollection", collectID);
            showAlert(
                "Deleted!",
                "The collection has been deleted.",
                "success",
                "animate-[slide-in-toast]"
            );
        }
    });
});
