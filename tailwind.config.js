module.exports = {
  content: [
    "./templates/**/*.php",
    "./public/**/*.php",
    "./src/**/*.php"
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Manrope', 'sans-serif'],
        serif: ['Cormorant Garamond', 'serif'],
        devanagariSans: ['Noto Sans Devanagari', 'sans-serif'],
        devanagariSerif: ['Noto Serif Devanagari', 'serif'],
      },
      colors: {
        brand: {
          bg: '#FAF5E9',
          elevated: '#FFFFFF',
          card: '#FFFFFF',
          pink: '#D32F2F', /* Keeping pink key but mapping to Red for quick replacement */
          purple: '#00695C', /* Keeping purple key but mapping to Teal for quick replacement */
          red: '#D32F2F',
          teal: '#00695C',
          blue: '#3B82F6',
          gold: '#C89B3C',
          text: '#3E2723',
        }
      }
    }
  },
  plugins: [],
}
