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
                sans: ['\"Open Sans\"', ...defaultTheme.fontFamily.sans],
            },
            maxWidth: {
                '7xl': '1400px',
            },
            colors: {
                brand: {
                    light: '#004aac',
                    DEFAULT: '#004aac',
                    dark: '#003580',
                    headline: '#1a202c',
                    btn: '#d30000',
                    'btn-hover': '#a90000',
                    footer: '#111827',
                    'footer-border': '#374151',
                    navy: '#004aac',
                    'navy-light': '#336fcc',
                    'logo-light': '#5b8fb9',
                    trust: '#004aac',
                },
            },
        },
    },

    plugins: [forms],
};
