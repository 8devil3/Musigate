import forms from '@tailwindcss/forms';
const colors = require("tailwindcss/colors");

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                'sans': ['Poppins', 'sans-serif'],
                'lemon': ['Lemon Milk Regular', 'sans-serif'],
                'lemonLight': ['Lemon Milk Ultra Light', 'sans-serif'],
            },
            fontSize: {
                'xxs': '10px'
            },
            colors: {
                orange: {
                    '500': '#f60'
                },
                yellow: {
                    '500': '#DF9500'
                },
                telegram: '#30A3E6',
                whatsapp: '#25D366',
                messenger: '#037DFF',
                facebook: '#1877F2',
                instagram: '#E4405F',
                youtube: '#CD201F',
                soundcloud: '#e56431',
                spotify: '#25D865',
                itunes: '#8F60FF',
                linkedin: '#0A66C2',
                "vtd-primary": colors.orange, // Light mode Datepicker color
                "vtd-secondary": colors.orange, // Dark mode Datepicker color
            },
            boxShadow: {
                'inner': 'inset 0px 0px 4px rgba(0, 0, 0, 0.5);',
                'sm': '0px 0px 4px rgba(0, 0, 0, 0.5)',
                'md': '0px 0px 6px rgba(0, 0, 0, 0.5)',
                'lg': '0px 0px 12px rgba(0, 0, 0, 0.75)',
                'xl': '0px 0px 24px rgba(0, 0, 0, 0.75)',
            },
        },
    },

    plugins: [forms],
};
