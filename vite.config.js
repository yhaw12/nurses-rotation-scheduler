const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    darkMode: 'class',
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
}