const defaultTheme = require('tailwindcss/defaultTheme');
module.exports = {
  purge: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],

  theme: {
      extend: {
          fontFamily: {
              sans: ['Nunito', ...defaultTheme.fontFamily.sans],
          },
      },
  },

  darkMode: false, // or 'media' or 'class'

  variants: {
    extend: {},
  },
  plugins: [],
}
