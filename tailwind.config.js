/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/react/**/*.{js,jsx}",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        'indigo': {
          500: '#6366f1',
          600: '#4f46e5',
          700: '#4338ca',
          800: '#3730a3',
          900: '#312e81',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}