/** @type {import('tailwindcss').Config} */
const config = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.{js,jsx,ts,tsx}',
    './resources/sass/**/*.scss',
    './app/View/Components/**/*.php',
  ],
  darkMode: ['selector', '[data-theme="dark"]'],
  theme: {
    extend: {
      // MYDS Color System (Official Specification)
      colors: {
        // Primary palette (MYDS Blue - Official)
        primary: {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6', // Dark mode primary
          600: '#2563eb', // Main MYDS Blue
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
        },

        // Danger palette (Official MYDS)
        danger: {
          400: '#ef4444', // Dark mode
          500: '#d32f2f', // Main danger - Official MYDS
          600: '#c62828',
        },

        // Success palette (Official MYDS)
        success: {
          400: '#10b981', // Dark mode
          500: '#388e3c', // Main success - Official MYDS
          600: '#2e7d32',
        },

        // Warning palette (Official MYDS)
        warning: {
          400: '#f59e0b', // Dark mode
          500: '#ffa000', // Main warning - Official MYDS
          600: '#ff8f00',
        },

        // Gray scale following MYDS specification
        gray: {
          50: '#f9fafb',   // txt-primary dark
          100: '#f3f4f6',  // bg-muted light
          200: '#e5e7eb',  // bg-subtle light
          300: '#d1d5db',
          400: '#9ca3af',  // txt-disabled light
          500: '#6b7280',  // txt-muted light
          600: '#4b5563',  // bg-subtle dark
          700: '#374151',  // bg-muted dark / txt-secondary light
          800: '#1f2937',  // bg-surface dark
          900: '#111827',  // bg-page dark / txt-primary light
        },
      },

      // MYDS Typography System
      fontFamily: {
        heading: ['Poppins', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
        body: ['Inter', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
      },

      fontSize: {
        // MYDS Heading sizes
        'heading-xl': ['60px', { lineHeight: '72px', fontWeight: '400' }],    // Heading Extra Large
        'heading-lg': ['48px', { lineHeight: '60px', fontWeight: '400' }],    // Heading Large
        'heading-md': ['36px', { lineHeight: '44px', fontWeight: '400' }],    // Heading Medium (h1)
        'heading-sm': ['30px', { lineHeight: '38px', fontWeight: '400' }],    // Heading Small (h2)
        'heading-xs': ['24px', { lineHeight: '32px', fontWeight: '400' }],    // Heading Extra Small (h3)
        'heading-2xs': ['20px', { lineHeight: '28px', fontWeight: '400' }],   // Heading 2X Small (h4)
        'heading-3xs': ['16px', { lineHeight: '24px', fontWeight: '400' }],   // Heading 3X Small (h5)
        'heading-4xs': ['14px', { lineHeight: '20px', fontWeight: '400' }],   // Heading 4X Small (h6)

        // MYDS Body text sizes
        'body-6xl': ['60px', { lineHeight: '72px' }],
        'body-5xl': ['48px', { lineHeight: '60px' }],
        'body-4xl': ['36px', { lineHeight: '44px' }],
        'body-3xl': ['30px', { lineHeight: '38px' }],
        'body-2xl': ['24px', { lineHeight: '32px' }],
        'body-xl': ['20px', { lineHeight: '28px' }],
        'body-lg': ['18px', { lineHeight: '26px' }],
        'body-base': ['16px', { lineHeight: '24px' }],
        'body-sm': ['14px', { lineHeight: '20px' }],
        'body-xs': ['12px', { lineHeight: '18px' }],
        'body-2xs': ['10px', { lineHeight: '12px' }],
      },

      // MYDS Spacing System
      spacing: {
        '4.5': '18px', // Mobile gap
        '6': '24px',   // Desktop gap/padding
      },

      // MYDS Border Radius
      borderRadius: {
        'xs': '4px',
        's': '6px',
        'm': '8px',
        'l': '12px',
        'xl': '14px',
      },

      // MYDS 12-8-4 Grid System
      screens: {
        'mobile': { 'max': '767px' },     // 4-column grid
        'tablet': { 'min': '768px', 'max': '1023px' },  // 8-column grid
        'desktop': { 'min': '1024px' },   // 12-column grid
      },

      // MYDS Maximum widths
      maxWidth: {
        'content': '1280px',     // Max content width
        'article': '640px',      // Article container
        'media-wide': '740px',   // Images and interactive charts
      },

      // MYDS Motion/Animation durations
      transitionDuration: {
        'short': '200ms',
        'medium': '400ms',
        'long': '600ms',
      },

      // MYDS Grid columns
      gridTemplateColumns: {
        'mobile': 'repeat(4, 1fr)',
        'tablet': 'repeat(8, 1fr)',
        'desktop': 'repeat(12, 1fr)',
      },

      // MYDS Box shadows
      boxShadow: {
        'focus': '0 0 0 4px var(--fr-shadow)',
        'focus-ring': '0 0 0 2px var(--fr-shadow)',
      },
    },
  },
  plugins: [],
}

export default config;
