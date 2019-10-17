// tailwind.config.js
module.exports = {
    theme: {
        fontFamily: {
            'sans': ['Barlow', '-apple-system', 'BlinkMacSystemFont'],
            'serif': ['Permanent Marker'],
            //'heading': ['Barlow', '-apple-system', 'BlinkMacSystemFont']
        },
        borderRadius: {
            'none': '0',
            'sm': '.125rem',
            default: '8px',
            'lg': '.5rem',
            'full': '9999px',
            'large': '12px',
        },
        extend: {
            colors: {
              'white': '#fff',
              'black': '#000',
              'yellow': '#f1e5b3'
            },
        },
        gradients: theme => ({
            'body-gradient': ['to bottom', '#fffaea', '#fff', '#fff'],
            'mono-circle': {
                type: 'radial',
                colors: ['circle', '#CCC', '#000']
            },
        }),
    },
    variants: {
        gradients: ['responsive', 'hover'],
    },
    plugins: [
        require('tailwindcss-plugins/pagination'),
        require('tailwindcss-plugins/gradients')
    ],
}