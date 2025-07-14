# Responsive Design Implementation for Weave Theme

## Overview

This document explains how the Weave WordPress theme has been made responsive across all device sizes, from mobile phones to large desktop screens.

## Breakpoints

The theme uses a **desktop-first** approach with the following breakpoints:

- **Large Desktop**: 1400px and up
- **Desktop**: 1200px to 1399px
- **Tablet Landscape**: 992px to 1199px
- **Tablet Portrait**: 768px to 991px
- **Mobile Large**: 576px to 767px
- **Mobile Small**: Up to 575px

## Files Added/Modified

### New Files Created:

1. `assets/css/responsive.css` - Main responsive stylesheet
2. `assets/js/mobile-menu.js` - Mobile menu functionality
3. `RESPONSIVE_SETUP.md` - This documentation

### Modified Files:

1. `functions.php` - Added responsive CSS and JS enqueuing
2. `header.php` - Added mobile menu toggle button
3. `style.css` - Updated container padding

## Key Features

### 1. Responsive Container

- Automatically adjusts max-width and padding based on screen size
- Maintains consistent spacing across all devices

### 2. Mobile Menu

- Hamburger menu for tablets and mobile devices
- Smooth animations and transitions
- Prevents body scroll when menu is open
- Auto-closes on window resize

### 3. Responsive Typography

- Font sizes scale appropriately for different screen sizes
- Maintains readability across all devices

### 4. Touch-Friendly Interactions

- Minimum 44px touch targets on mobile
- Proper spacing for finger navigation

### 5. Flexible Layouts

- Header elements stack vertically on smaller screens
- Search box becomes full-width on mobile
- Navigation converts to dropdown menu

## Usage

### Utility Classes

The responsive CSS includes utility classes for showing/hiding elements:

```css
.hide-mobile    /* Hidden on mobile devices */
/* Hidden on mobile devices */
.hide-tablet    /* Hidden on tablet devices */
.hide-desktop   /* Hidden on desktop devices */
.show-mobile    /* Visible only on mobile */
.show-tablet    /* Visible only on tablet */
.show-desktop; /* Visible only on desktop */
```

### Example Usage:

```html
<!-- Show different content for mobile vs desktop -->
<div class="hide-mobile">Desktop-only content</div>
<div class="show-mobile">Mobile-only content</div>
```

## Testing

### Recommended Testing Tools:

1. **Browser DevTools** - Use responsive design mode
2. **Real Devices** - Test on actual phones and tablets
3. **Online Tools**:
   - [Responsive Design Checker](https://responsivedesignchecker.com/)
   - [Google Mobile-Friendly Test](https://search.google.com/test/mobile-friendly)

### Key Test Scenarios:

1. **Navigation** - Mobile menu opens/closes properly
2. **Search** - Search box is usable on all devices
3. **Cart** - Shopping cart is accessible
4. **Typography** - Text is readable on all screen sizes
5. **Touch Targets** - Buttons and links are easy to tap

## Customization

### Adding Custom Responsive Styles

To add custom responsive styles, you can:

1. **Modify `responsive.css`** - Add your custom media queries
2. **Create new CSS files** - Add them to `functions.php`
3. **Use existing breakpoints** - Follow the established pattern

### Example Custom Responsive Style:

```css
/* Custom responsive style */
@media (max-width: 767px) {
  .my-custom-element {
    font-size: 14px;
    padding: 10px;
  }
}
```

## Performance Considerations

1. **CSS Loading** - Responsive CSS loads last to override other styles
2. **JavaScript** - Mobile menu JS loads in footer for better performance
3. **Images** - Use responsive images with `srcset` and `sizes` attributes
4. **Caching** - File modification timestamps used for cache busting

## Browser Support

The responsive design supports:

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Troubleshooting

### Common Issues:

1. **Mobile menu not working**

   - Check if JavaScript is loading properly
   - Verify CSS classes are applied correctly

2. **Styles not applying**

   - Clear browser cache
   - Check if responsive CSS is loading after other styles

3. **Layout breaking on specific devices**
   - Test on actual device
   - Check for conflicting CSS rules

### Debug Tips:

1. **Use browser dev tools** to inspect elements
2. **Check console** for JavaScript errors
3. **Verify file paths** in `functions.php`
4. **Test with different screen sizes**

## Future Enhancements

Potential improvements for future versions:

1. **CSS Grid** - For more complex layouts
2. **Container Queries** - For component-level responsiveness
3. **Progressive Web App** - For better mobile experience
4. **Accessibility** - Enhanced keyboard navigation
5. **Performance** - CSS-in-JS or critical CSS extraction

## Support

For questions or issues with the responsive implementation:

1. Check this documentation first
2. Review the CSS and JavaScript files
3. Test on multiple devices and browsers
4. Consider browser compatibility requirements

---

**Last Updated**: January 2025
**Version**: 1.0.0
