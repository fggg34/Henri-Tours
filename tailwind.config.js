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
                brand: {
                    light: '#2c5282',
                    DEFAULT: '#1e3a5f',
                    dark: '#1a2d4a',
                    headline: '#1a202c',
                    btn: '#c0392b',
                    'btn-hover': '#a93226',
                    footer: '#111827',
                    'footer-border': '#374151',
                    navy: '#1e3760',
                    'navy-light': '#2c4a7c',
                    trust: '#1b2e4a',
                },
            },
        },
    },

    plugins: [forms],
};
