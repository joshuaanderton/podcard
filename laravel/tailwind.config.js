const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './app/View/Components/**/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.{js,jsx,ts,tsx,vue}',
    './vendor/joshuaanderton/livewire/**/*.{php,js,ts}',
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['ModernEra', '-apple-system', 'BlinkMacSystemFont', '"Helvetica Neue"', '"Calibri Light"', 'Roboto', 'sans-serif'],
        'mono': ['ModernEraMono', 'ui-monospace', 'SFMono-Regular', 'monospace'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ]
}
