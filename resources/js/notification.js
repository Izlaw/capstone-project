// notification.js
export function initNotifications() {
    document.addEventListener("DOMContentLoaded", function () {
        const notificationBell = document.getElementById("notificationBell");
        const notificationDropdown = document.getElementById(
            "notificationDropdown"
        );
        const notificationBadge = document.getElementById("notificationBadge");

        // Track the dropdown state
        let isDropdownOpen = false;

        // Function to show dropdown
        function showDropdown() {
            if (!isDropdownOpen && notificationDropdown) {
                notificationDropdown.classList.remove("hidden");
                setTimeout(() => {
                    notificationDropdown.classList.remove(
                        "opacity-0",
                        "scale-95"
                    );
                    notificationDropdown.classList.add(
                        "opacity-100",
                        "scale-100"
                    );
                }, 10);
                isDropdownOpen = true;
            }
        }

        // Function to hide dropdown
        function hideDropdown() {
            if (isDropdownOpen && notificationDropdown) {
                notificationDropdown.classList.remove(
                    "opacity-100",
                    "scale-100"
                );
                notificationDropdown.classList.add("opacity-0", "scale-95");
                setTimeout(() => {
                    notificationDropdown.classList.add("hidden");
                }, 300);
                isDropdownOpen = false;
            }
        }

        // Check if notificationBell exists before adding event listener
        if (notificationBell) {
            notificationBell.addEventListener("click", function () {
                console.log("Notification bell clicked");

                // Store the read state
                sessionStorage.setItem("notificationsRead", "true");

                if (!isDropdownOpen) {
                    showDropdown();
                } else {
                    hideDropdown();
                }

                if (notificationBadge) {
                    notificationBadge.textContent = "";
                    notificationBadge.classList.add("hidden");
                }

                // Check if Livewire exists before using it
                if (typeof Livewire !== "undefined") {
                    Livewire.emit("markAsRead");
                }
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener("click", function (event) {
            if (isDropdownOpen && notificationBell && notificationDropdown) {
                // Check if click is outside the notification elements
                if (
                    !notificationBell.contains(event.target) &&
                    !notificationDropdown.contains(event.target)
                ) {
                    hideDropdown();
                }
            }
        });

        // Check if Livewire exists before setting up event listeners
        if (typeof Livewire !== "undefined") {
            Livewire.on("notificationUpdated", (unreadCount) => {
                const badge = document.getElementById("notificationBadge");
                if (badge) {
                    if (unreadCount > 0) {
                        badge.classList.remove("hidden");
                        badge.classList.add("block");
                        badge.textContent = unreadCount;
                    } else {
                        badge.classList.remove("block");
                        badge.classList.add("hidden");
                    }
                }

                // Important: We're NOT closing the dropdown when notifications update
                // This allows new notifications to appear while the dropdown is open
            });

            Livewire.on("clearNotifications", () => {
                sessionStorage.removeItem("notifications");
                Livewire.emit("loadFromStorage");
                const badge = document.getElementById("notificationBadge");
                if (badge) {
                    badge.textContent = "";
                    badge.classList.add("hidden");
                }
            });
        }

        // Set up Laravel Echo to listen for broadcast notifications
        const userIdMeta = document.head.querySelector('meta[name="user-id"]');
        const userId = userIdMeta ? userIdMeta.content : null;

        if (userId && typeof window.Echo !== "undefined") {
            // By default, Laravel broadcasts notifications on a private channel like "App.Models.User.{id}"
            window.Echo.private(`App.Models.User.${userId}`).notification(
                (notification) => {
                    console.log(
                        "Broadcast notification received:",
                        notification
                    );
                    const badge = document.getElementById("notificationBadge");
                    if (badge) {
                        let currentCount = parseInt(badge.textContent) || 0;
                        currentCount++;
                        badge.textContent = currentCount;
                        badge.classList.remove("hidden");
                        badge.classList.add("block");
                    }

                    // If notification dropdown is open, we should refresh the content
                    if (isDropdownOpen && typeof Livewire !== "undefined") {
                        Livewire.emit("loadNotifications");
                    }
                }
            );
        }
    });

    // Get fresh reference to notificationBadge outside the DOMContentLoaded event
    const notificationBadge = document.getElementById("notificationBadge");
    const hasReadNotifications = sessionStorage.getItem("notificationsRead");
    if (hasReadNotifications && notificationBadge) {
        notificationBadge.textContent = "";
        notificationBadge.classList.add("hidden");
    }
}
