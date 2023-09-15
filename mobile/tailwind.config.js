const defaultTheme = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './index.html',
        './src/*/**.tsx'
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#f1641e',
                    '50': '#fef6ee',
                    '100': '#feead6',
                    '200': '#fbd1ad',
                    '300': '#f8b079',
                    '400': '#f48543',
                    '500': '#f1641e',
                    '600': '#e24a14',
                    '700': '#bc3712',
                    '800': '#952c17',
                    '900': '#782716',
                    '950': '#411109',
                },
                chrome: {
                    DEFAULT: '#917f78',
                    '50': '#f8f7f6',
                    '100': '#f7f5f3',
                    '200': '#e3ddd9',
                    '300': '#cbc1b9',
                    '400': '#b2a299',
                    '500': '#a08d83',
                    '600': '#917f78',
                    '700': '#7b6a65',
                    '800': '#6b5a57',
                    '900': '#5a4c49',
                    '950': '#352d2c',
                },
            }
        },
    },

    plugins: [
        // require('@tailwindcss/forms'),
        // require('@tailwindcss/typography'),
    ],
}
