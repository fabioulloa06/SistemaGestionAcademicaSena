import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                sena: {
                    50: '#f0f9f0',
                    100: '#dcf2dc',
                    200: '#bce5bc',
                    300: '#8fd18f',
                    400: '#5ab35a',
                    500: '#4d8e37',
                    600: '#3d7230',
                    700: '#325b28',
                    800: '#2a4a23',
                    900: '#243e1f',
                },
            },
        },
    },

    plugins: [forms, typography],
};
