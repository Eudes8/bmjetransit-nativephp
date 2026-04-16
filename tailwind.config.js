/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                bmje: {
                    50: '#f0f7ff',
                    100: '#e0efff',
                    200: '#b8dbff',
                    300: '#7ac0ff',
                    400: '#349fff',
                    500: '#0a7eff',
                    600: '#005edb',
                    700: '#004ab1',
                    800: '#004092',
                    900: '#003778',
                    950: '#002350',
                },
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
