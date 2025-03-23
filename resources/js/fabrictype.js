document.addEventListener("DOMContentLoaded", function () {
    const fabricTypeSelect = document.getElementById("fabric_type");
    const customFabricTypeInput = document.getElementById("custom_fabric_type");
    const sizeForm = document.getElementById("sizeForm");

    fabricTypeSelect.addEventListener("change", function () {
        if (fabricTypeSelect.value === "custom") {
            customFabricTypeInput.classList.remove("hidden");
        } else {
            customFabricTypeInput.classList.add("hidden");
            customFabricTypeInput.value = ""; // Clear custom input if not selected
        }
    });

    customFabricTypeInput.addEventListener("input", function () {
        const customValue = customFabricTypeInput.value.toLowerCase();
        const options = Array.from(fabricTypeSelect.options);

        const matchingOption = options.find(
            (option) => option.value.toLowerCase() === customValue
        );

        if (matchingOption) {
            fabricTypeSelect.value = matchingOption.value;
            customFabricTypeInput.classList.add("hidden");
            customFabricTypeInput.value = ""; // Clear custom input if matched
        }
    });

    // Ensure the custom fabric type is included in the form submission
    sizeForm.addEventListener("submit", function (event) {
        if (
            fabricTypeSelect.value === "custom" &&
            customFabricTypeInput.value.trim() !== ""
        ) {
            const customFabricTypeHiddenInput = document.createElement("input");
            customFabricTypeHiddenInput.type = "hidden";
            customFabricTypeHiddenInput.name = "custom_fabric_type";
            customFabricTypeHiddenInput.value =
                customFabricTypeInput.value.trim();
            sizeForm.appendChild(customFabricTypeHiddenInput);
        }
    });
});
