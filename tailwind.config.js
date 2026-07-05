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
          bg: '#FCFAF7',
          elevated: '#FFFFFF',
          card: '#FFFFFF',
          pink: '#E25F8C',
          purple: '#8B5CF6',
          blue: '#3B82F6',
          gold: '#C5A059',
          text: '#3C3530',
        }
      }
    }
  },
  plugins: [],
}
