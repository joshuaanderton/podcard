// tailwind.config.js
module.exports = {
    theme: {
        fontFamily: {
            'sans': ['-apple-system', 'BlinkMacSystemFont'],
            'serif': ['Permanent Marker'],
        },
        borderRadius: {
            'none': '0',
            'sm': '.125rem',
            default: '8px',
            'lg': '.5rem',
            'full': '9999px',
            'large': '12px',
        },
        gradients: theme => ({
            'body-gradient': ['to bottom', '#381205', '#712d14'],
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