/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: false,
  content: [
    './views/**/*.twig',
  ],
  theme: {
    extend: {},
  },
  daisyui: {
    themes: ["light"],
  },
  plugins: [
    require('daisyui'),
  ],
}

