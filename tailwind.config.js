const defaultTheme = require('tailwindcss/defaultTheme');
module.exports = {
  purge: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],

  safelist: [
      'sm:max-w-xs',
      'sm:max-w-sm',
      'sm:max-w-md',
      'sm:max-w-lg',
      'sm:max-w-xl',
      'sm:max-w-2xl',
      'sm:max-w-3xl',
      'sm:max-w-4xl',
      'sm:max-w-5xl',
      'sm:max-w-6xl',
      'sm:max-w-7xl',
      'sm:max-w-full',
      'sm:max-w-min',
      'sm:max-w-max',
      'sm:max-w-prose',
      'sm:max-w-screen-sm',
      'sm:max-w-screen-md',
      'sm:max-w-screen-lg',
      'sm:max-w-screen-xl',
      'sm:max-w-screen-2xl',
  ],

  theme: {
      extend: {
          fontFamily: {
            sans: ['Nunito', ...defaultTheme.fontFamily.sans],
          },
      },
      scrollbar: ['dark', 'rounded', 'hover'],
  },

  darkMode: false, // or 'media' or 'class'

  variants: {
    extend: {
      backgroundColor: ['odd', 'even', 'active', 'disabled'],
      borderColor: ['disabled'],
      borderWidth: ['first', 'last', 'odd', 'even'],
      opacity: ['disabled'],
      cursor: ['disabled'],
      textColor: ['disabled'],
    },
    scrollbar: ['dark', 'rounded', 'hover'],
  },
  
  plugins: [
    require('tailwind-scrollbar'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/line-clamp'),
  ],
}
