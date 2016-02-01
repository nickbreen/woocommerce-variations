Plugin Name: Woo Variations

# What?

Light-weight plugin that displays your WooCommerce variations in a table layout.

# How?

Overloads the ```woocommerce/single-product/add-to-cart/variable.php``` template
and replaces the built-in CSS with ```woocommerce/assets/css/frontend/add-to-cart-variation.css```.

The variations table is too large to display in the typical position and registers the variations table as a _tab_ instead.

# Tabs Suck

Do not hide important content from the user. Pages expand to as as long as they need to be, don't mess with that.

So, for better or worse, this plugin also changes the display of tabs. The tab control is hidden with CSS
and all tab panels are displayed as block elements.
