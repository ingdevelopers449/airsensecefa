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
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
                heading: ['Outfit', 'sans-serif'],
            },
            colors: {
                sena: {
                    green: '#39A900',
                    'green-hover': '#2E8700',
                    dark: '#00324D',
                    darker: '#002235',
                    header: '#001A29',
                    border: '#00476E',
                    light: '#EBF7E6',
                },
                agro: {
                    50: '#F4FBF0',
                    100: '#E2F6D8',
                    200: '#C2EDB2',
                    300: '#94E07B',
                    400: '#5EC935',
                    500: '#39A900',
                    600: '#2E8700',
                    700: '#226500',
                    800: '#1B4E03',
                    900: '#164005',
                },
            },
        },
    },

    plugins: [forms],
};
