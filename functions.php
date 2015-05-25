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


if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) :
// function wooradio_plugin_path() {
//   // gets the absolute path to this plugin directory
//   return untrailingslashit( plugin_dir_path( __FILE__ ) );
// }
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
}, 10, 3 );

$register_scripts = function () {

  wp_deregister_script('wc-add-to-cart-variation');

  wp_dequeue_script('wc-add-to-cart-variation');

  wp_register_script( 'wc-add-to-cart-variation', plugins_url( 'woocommerce\assets\js\frontend\add-to-cart-variation.min.js', __FILE__ ), array( 'jquery'), false, true );

  wp_enqueue_script('wc-add-to-cart-variation');

};

// add_action( 'wp_enqueue_scripts', $register_scripts );
// add_action( 'wp_footer', $register_scripts);

$register_styles = function () {

    wp_deregister_style('wc-add-to-cart-variation');

    wp_dequeue_style('wc-add-to-cart-variation');

    wp_register_style( 'wc-add-to-cart-variation', plugins_url( 'woocommerce\assets\css\frontend\add-to-cart-variation.css', __FILE__ ), array('storefront-woocommerce-style'));

    wp_enqueue_style('wc-add-to-cart-variation');

};

add_action( 'wp_enqueue_scripts', $register_styles );

endif;

require_once 'tab.php';
