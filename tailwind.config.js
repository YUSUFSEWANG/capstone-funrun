/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                navy: {
                    50: '#eef4ff',
                    100: '#dbe6ff',
                    500: '#1d4fa3',
                    600: '#173f85',
                    700: '#12397c',
                    800: '#0e2a5c',
                    900: '#091d40',
                },
                merah: {
                    400: '#f2564f',
                    500: '#e4322b',
                    600: '#c9241e',
                    700: '#a41b16',
                },
                langit: {
                    300: '#7cc8ef',
                    400: '#3fa9e0',
                    500: '#1e90cc',
                },
                krem: '#f4f7fb',
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                display: ['Poppins', 'Inter', 'ui-sans-serif', 'sans-serif'],
            },
            boxShadow: {
                card: '0 10px 30px -12px rgba(18, 57, 124, 0.25)',
            },
        },
    },
    plugins: [],
};
