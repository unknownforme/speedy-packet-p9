import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                autumn: {
                    orange: '#a04000',
                    brown: '#2d1b18',
                    green: '#1b3022',
                    cream: '#dcd3c0',
                    bg: '#1a1210',
                    surface: '#241a17',
                },
            },
        },
    },

    plugins: [forms],
};