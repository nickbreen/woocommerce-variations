<?php
/**
 * Plugin Name: Woo Variations
 * Plugin URI: http://github/nickbreen/woocommerce-variations
 * Description: Light-weight plugin that displays your WooCommerce variations in a table layout.
 * Author: nickbreen
 * Version: 1.0.0
 * Author URI: http://github/nickbreen
 */

defined( 'ABSPATH' ) or die();

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    if ('yes' === get_option('variations_table')) {

        add_filter( 'woocommerce_locate_template', function ( $template, $template_name, $template_path ) {
            global $woocommerce;
            $_template = $template;
            if ( ! $template_path ) $template_path = $woocommerce->template_url;
            // $plugin_path  = wooradio_plugin_path() . '/woocommerce/';
            $plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/woocommerce/';
            // Look within passed path within the theme - this is priority
            $template = locate_template(
                array(
                    $template_path . $template_name,
                    $template_name
                )
            );
            // Modification: Get the template from this plugin, if it exists
            if ( ! $template && file_exists( $plugin_path . $template_name ) )
            $template = $plugin_path . $template_name;
            // Use default template
            if ( ! $template )
            $template = $_template;
            // Return what we found
            return $template;
        }, 10, 3);

        // Add the CSS required by the table, replace the built-in CSS.
        add_action( 'wp_enqueue_scripts', function () {
            wp_dequeue_style('wc-add-to-cart-variation');
            wp_deregister_style('wc-add-to-cart-variation');
            wp_register_style( 'wc-add-to-cart-variation', plugins_url( 'woocommerce\assets\css\frontend\add-to-cart-variation.css', __FILE__ ), array('storefront-woocommerce-style'));
            wp_enqueue_style('wc-add-to-cart-variation');
        });

        // Add the product variation tab
        add_filter('woocommerce_product_tabs', function ($tabs = array()) {
            global $product;
            if (is_product() and $product->product_type == 'variable')
                $tabs['variations_table'] = array(
                    'title' 	=> __( 'Product Variations', 'woocommerce' ),
                    'priority' 	=> 15,
                    'callback' 	=> function ($key, $tab) {
                        $heading = esc_html( apply_filters( 'woocommerce_product_variations_heading', __( $tab['title'], 'woocommerce' ) ) );
                        if ( $heading )
                            echo "<h2>$heading</h2>";
                        woocommerce_variable_add_to_cart();
                    }
                );
            return $tabs;
        });

        // Remove 'add to cart' from directly beneath the summary
        // As a table of variations it is far too big.
        // See woocommerce/includes/wc-template-hooks.php:146
        add_action('woocommerce_single_product_summary', function () {
          // remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
          remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
        }, 29);
    }

    if ('yes' === get_option('tabs_suck')) {
        // Add the CSS required by the table, replace the built-in CSS.
        add_action( 'wp_enqueue_scripts', function () {
            wp_register_style( 'tabs-suck', plugins_url( 'woocommerce\assets\css\frontend\tabs-suck.css', __FILE__ ), array('storefront-woocommerce-style'));
            wp_enqueue_style('tabs-suck');
        });
    }

    // Remove all tabs set in the 'tabs_to_remove' option, using clever array mangling
    add_filter('woocommerce_product_tabs', function ($tabs = array()) {
      return array_diff_key($tabs, array_flip(explode(',', get_option('tabs_to_remove'))));
    }, 90);

}

register_activation_hook( __FILE__, function () {
  // There is no UI for configuring these options
  add_option('variations_table', 'yes');
  add_option('tabs_suck', 'yes');
  add_option('tabs_to_remove', 'reviews,additional_information');
});
