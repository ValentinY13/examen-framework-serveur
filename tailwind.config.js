/** @type {import('tailwindcss').Config} */
module.exports = {
  // met le darkmode en fonction des params du navigateur
  // darkMode: 'class',
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js",
    "./vendor/tales-from-a-dev/flowbite-bundle/templates/**/*.html.twig"
  ],
  theme: {
    extend: {
      colors: {
        success: {
          50: '#dcfce7',
          700: '#4d7c0f'
        },
        error: {
          50: '#fee2e2',
          700: '#b91c1c'
        }
      }
    },
  },

  safelist: [
    'text-success-700',
    'bg-success-50',
    'text-error-700',
    'bg-error-50',
  ],

  plugins: [
    require('flowbite/plugin'),
  ],
}
