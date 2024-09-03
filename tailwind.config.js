import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    presets: [require("./vendor/tallstackui/tallstackui/tailwind.config.js")],
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/tallstackui/tallstackui/src/**/*.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        extend: {
            fontSize: {
                "3xs": ["0.5rem", "0.5rem"],
                "2xs": ["0.625rem", "0.75rem"],
            },
            colors: {
                indigo: {
                    50: "#F7F8FC",
                    100: "#E8EAF6",
                    200: "#C5CAE9",
                    300: "#9FA8DA",
                    400: "#7986CB",
                    500: "#5C6BC0",
                    600: "#3F51B5",
                    700: "#3949AB",
                    800: "#303F9F",
                    900: "#283593",
                    1000: "#1A237E",
                    1100: "#0D1131",
                },
                blue: {
                    50: "#F5FAFE",
                    100: "#E3F2FD",
                    200: "#BBDEFB",
                    300: "#90CAF9",
                    400: "#64B5F6",
                    500: "#42A5F5",
                    600: "#2196F3",
                    700: "#1E88E5",
                    800: "#1976D2",
                    900: "#1565C0",
                    1000: "#0D47A1",
                    1100: "#072140",
                },
                green: {
                    50: "#FAFCF7",
                    100: "#F1F8E9",
                    200: "#DCEDC8",
                    300: "#C5E1A5",
                    400: "#AED581",
                    500: "#9CCC65",
                    600: "#8BC34A",
                    700: "#7CB342",
                    800: "#689F38",
                    900: "#558B2F",
                    1000: "#33691E",
                    1100: "#1C2E0F",
                },
            },
        },
    },

    plugins: [
        forms,
        require("tailwind-scrollbar")({ nocompatible: true }),
        require("flowbite/plugin")({
            charts: true,
        }),
    ],
};
