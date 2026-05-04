import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                heading: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
                body: ['Manrope', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                neon: {
                    cyan: '#72DCFF',
                    blue: '#3b82f6',
                    violet: '#8b5cf6',
                },
                dark: {
                    bg: '#0f172a',
                    card: '#1e293b',
                    border: '#334155',
                },
            },
        },
    },

    plugins: [forms],
};
