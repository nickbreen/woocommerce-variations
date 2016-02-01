Display WooCommerce variations in a table layout.

# WooCommerce Product Variations Table

This display of variations as a table is controlled with the
```variations_table``` option. If set to ```yes``` (the default).

Overloads the ```woocommerce/single-product/add-to-cart/variable.php```
template and replaces the built-in CSS with ```woocommerce/assets/css/frontend/add-to-cart-variation.css```.

The variations table is too large to display in the typical position, it is
registered as a _tab_ instead.

# Tabs Suck

Do not hide important content from the user. Pages expand to as as long as they
need to be, don't mess with that.

The ```tabs_suck``` option controls the display of tabs. If set to ```yes```
(the default) the tab control is hidden with CSS and all tab panels are
displayed as ordinary block elements.

# Remove Unwanted Tabs

The ```tabs_to_remove``` option is a comma-delimited list of tab panels to
remove from the tab set.

# Options UI

No UI is provided to set these options.
