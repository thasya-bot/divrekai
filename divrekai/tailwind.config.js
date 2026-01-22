import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
    "./resources/*/.vue",
    "./resources/*/.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
