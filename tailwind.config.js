/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/**/*.{html,php,js}",
    "./app/Views/**/*.{php,html}",
    "./app/**/*.{php,html,js}"
  ],

  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: "#0f766e",   // hijau akademik
          dark: "#0d5d57",
          light: "#f0fdfa",
        },
        secondary: "#064e3b"
      },

      fontFamily: {
        sans: ["Inter", "ui-sans-serif", "system-ui"],
      }
    },
  },

  plugins: [],
};
