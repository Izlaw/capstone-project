import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/customize.js",
                "resources/js/manageorder.js",
                "resources/js/chat.js",
                "resources/js/notification.js",
                "resources/js/manageemployee.js",
            ],
            refresh: true,
        }),
    ],
    build: {
        chunkSizeWarningLimit: 1000, // Set to 1000 KB (1 MB) or your desired limit
    },
    server: {
        host: "192.168.68.105", // Make Vite server accessible on all network interfaces
        port: 5173, // Make sure the port is correct
    },
});
