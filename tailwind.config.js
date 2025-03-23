const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    mode: "jit", // Enable JIT mode
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js", // Include JavaScript files if you're dynamically adding classes
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: "#171c2f",
                secondary: "#232f49",
                accent: "#374256",
                highlight: "#faa183",
                danger: "#b2192b",
                deep: "#541b2e",
            },
            animation: {
                spin: "spin 2s linear infinite",
                "slide-in-toast": "slideInFromTopRight 0.5s ease-in-out",
            },
            keyframes: {
                spin: {
                    "0%": { transform: "rotate(0deg)" },
                    "100%": { transform: "rotate(-360deg)" },
                },
                slideInFromTopRight: {
                    "0%": {
                        transform: "translate(100%, -50%)",
                    },
                    "100%": {
                        transform: "translate(0, -50%)",
                    },
                },
            },
            textShadow: {
                default: "2px 2px 4px rgba(0, 0, 0, 0.7)",
                md: "3px 3px 6px rgba(0, 0, 0, 0.8)",
                lg: "4px 4px 8px rgba(0, 0, 0, 0.9)",
                xl: "5px 5px 10px rgba(0, 0, 0, 1.0)",
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
        require("tailwindcss-textshadow"),
    ],
};
