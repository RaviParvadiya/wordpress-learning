# WooCommerce Customization Setup Guide

This guide explains how to set up and use the custom WooCommerce functionality in the Weave theme.

## Features Added

### 1. Custom Product Fields

- **Design Number**: Unique identifier for each fabric design
- **Designer Name**: Brand or designer name
- **Fabric Type**: Dropdown with options (Cotton, Polyester, Silk, Linen, Wool, Blend)
- **Color Options**: Text area for listing available colors
- **Pattern Repeat**: Numeric field for pattern repeat size in inches
- **Featured Product**: Checkbox to mark products as featured
- **Customer Rating**: Numeric field for custom rating (0-5)

### 2. Dynamic Product Loading

- AJAX-powered product filtering and loading
- Category filtering
- Featured product filtering
- Search functionality
- Product sorting (Latest, Name A-Z, Price Low/High, Rating)
- Load more products functionality

### 3. Enhanced Product Display

- Custom product cards with hover effects
- Quick view modal
- AJAX add to cart
- Responsive design
- Custom styling matching your theme

## Setup Instructions

### Step 1: Activate WooCommerce

1. Go to WordPress Admin → Plugins
2. Install and activate WooCommerce if not already done
3. Complete the WooCommerce setup wizard

### Step 2: Add Products

1. Go to WordPress Admin → Products → Add New
2. Fill in the basic product information:
   - Product name
   - Description
   - Price
   - Product image
   - Category

### Step 3: Add Custom Fields

In the product edit page, scroll down to find the "Product Data" section. You'll see new custom fields:

1. **Design Number**: Enter the design number (e.g., "DESIGN No. 292")
2. **Designer Name**: Enter the designer or brand name
3. **Fabric Type**: Select from the dropdown
4. **Color Options**: List available colors (one per line)
5. **Pattern Repeat**: Enter the pattern repeat size in inches
6. **Featured Product**: Check this to mark as featured
7. **Customer Rating**: Enter a rating between 0-5

### Step 4: View Products

The products will automatically appear on your front page in the "Our Products" section with:

- Product filtering options
- Search functionality
- Dynamic loading
- Custom styling

## Usage Guide

### For Administrators

#### Adding Products

1. Create a new product in WooCommerce
2. Fill in the custom fields in the "Product Data" section
3. Set a featured image
4. Publish the product

#### Managing Featured Products

- Check the "Featured Product" checkbox to highlight products
- Featured products can be filtered separately on the front end

#### Product Categories

- Create product categories in WooCommerce → Products → Categories
- Categories will appear in the filter dropdown

### For Users

#### Browsing Products

- Use the category filter to browse by product type
- Use the search box to find specific products
- Sort products by different criteria
- Check "Featured Only" to see highlighted products

#### Product Interaction

- Click "Quick View" for a popup with product details
- Click "View Details" to go to the full product page
- Click "Add to Cart" to add products to cart
- Use "Load More" to see additional products

## Customization Options

### Adding More Custom Fields

To add more custom fields, edit `functions.php` and add to the `weave_add_custom_product_fields()` function:

```php
woocommerce_wp_text_input(
    array(
        'id' => '_your_field_name',
        'label' => 'Your Field Label',
        'placeholder' => 'Placeholder text',
        'desc_tip' => true,
        'description' => 'Field description'
    )
);
```

### Modifying Product Display

Edit `template-parts/woocommerce-products.php` to change how products are displayed.

### Styling Changes

Edit `assets/css/woocommerce.css` to modify the appearance of product cards and other elements.

## AJAX Functions

The theme includes several AJAX functions for dynamic functionality:

- `weave_load_products_ajax()`: Load products with filters
- `weave_search_products_ajax()`: Search products
- `weave_quick_view_ajax()`: Show quick view modal
- `weave_add_to_cart_ajax()`: Add products to cart
- `weave_sort_products_ajax()`: Sort products

## File Structure

```
wp-content/themes/weave/
├── functions.php (WooCommerce functions and AJAX)
├── woocommerce.php (WooCommerce template)
├── template-parts/
│   └── woocommerce-products.php (Product display template)
├── assets/
│   ├── css/
│   │   └── woocommerce.css (WooCommerce styles)
│   └── js/
│       └── weave-ajax.js (AJAX functionality)
└── WOOCOMMERCE_SETUP.md (This file)
```

## Troubleshooting

### Products Not Showing

1. Check if WooCommerce is activated
2. Ensure products are published
3. Check if products have images
4. Verify custom fields are filled

### AJAX Not Working

1. Check browser console for JavaScript errors
2. Ensure jQuery is loaded
3. Check if the AJAX script is enqueued properly

### Styling Issues

1. Clear browser cache
2. Check if CSS file is loading
3. Verify CSS selectors match HTML structure

## Support

For issues or questions:

1. Check the browser console for errors
2. Verify all files are in the correct locations
3. Ensure WooCommerce is properly configured
4. Test with a default theme to isolate issues

## Performance Tips

1. **Optimize Images**: Use appropriately sized product images
2. **Limit Products**: Don't load too many products at once
3. **Caching**: Use a caching plugin for better performance
4. **CDN**: Consider using a CDN for images and assets

## Future Enhancements

Potential improvements to consider:

- Product comparison functionality
- Wishlist feature
- Advanced filtering (price range, fabric type, etc.)
- Product reviews integration
- Social sharing buttons
- Related products display
