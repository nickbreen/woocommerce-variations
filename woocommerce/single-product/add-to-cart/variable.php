<?php
/**
 * Variable product add to cart.
 *
 * Available variables:
 * @see https://github.com/woothemes/woocommerce/blob/2.3.8/includes/wc-template-functions.php#L810-L830
 *
 * @author 		nickbreen
 * @package 	WooCommerce/Templates
 * @version     2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $post;

$visible_active_variations = array_filter($available_variations, function ($v) {
    return $v['variation_is_active'] && $v['variation_is_visible'];
});

?>

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<pre style="height: 15em; overflow:auto">

<?php print_r($attributes); ?>

<?php print_r($visible_active_variations[0]['attributes']); ?>
</pre>

<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
    <?php if ( ! empty( $visible_active_variations ) ) : ?>
        <table class="variations" summary="Product variations">
            <thead>
                <tr>
                    <th></th>
                    <?php foreach ($attributes as $i => $v) : ?>
                        <th><?php echo wc_attribute_label($i); ?></th>
                    <?php endforeach; ?>
                    <th>SKU</th>
                    <th>Price</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($visible_active_variations as $i => $v) : ?>
                    <tr>
                        <td>
                            <input type="radio"
                                   xid="" 
                                   name=""
                                   value=""/>
                        </td>
                        <?php foreach ($attributes as $j => $a) : ?>
                            <td>
                                <?php foreach ($a as $o) : ?>
                                    <?php if (in_array( sanitize_title( $o ), $v['attributes'])) : ?>
                                        <?php echo esc_html( apply_filters( 'woocommerce_variation_option_name', $o ) ); ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>
                        <?php endforeach; ?>
                        <td class="sku"><?php echo $v['sku']; ?></td>
                        <td class="currency"><?php echo $v['price_html']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table class="variations">
            <tbody>
                <?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
                    <tr>
                        <td class="label"><label for="<?php echo sanitize_title( $name ); ?>"><?php echo wc_attribute_label( $name ); ?></label></td>
                        <td class="value"><select id="<?php echo esc_attr( sanitize_title( $name ) ); ?>" name="attribute_<?php echo sanitize_title( $name ); ?>" data-attribute_name="attribute_<?php echo sanitize_title( $name ); ?>">
                            <option value=""><?php echo __( 'Choose an option', 'woocommerce' ) ?>&hellip;</option>
                            <?php
                            if ( is_array( $options ) ) {

                                if ( isset( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ) {
                                    $selected_value = $_REQUEST[ 'attribute_' . sanitize_title( $name ) ];
                                } elseif ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) {
                                    $selected_value = $selected_attributes[ sanitize_title( $name ) ];
                                } else {
                                    $selected_value = '';
                                }

                                // Get terms if this is a taxonomy - ordered
                                if ( taxonomy_exists( $name ) ) {

                                    $terms = wc_get_product_terms( $post->ID, $name, array( 'fields' => 'all' ) );

                                    foreach ( $terms as $term ) {
                                        if ( ! in_array( $term->slug, $options ) ) {
                                            continue;
                                        }
                                        echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
                                    }

                                } else {

                                    foreach ( $options as $option ) {
                                        echo '<option value="' . esc_attr( sanitize_title( $option ) ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
                                    }

                                }
                            }
                            ?>
                        </select> <?php
                        if ( sizeof( $attributes ) === $loop ) {
                            echo '<a class="reset_variations" href="#reset">' . __( 'Clear selection', 'woocommerce' ) . '</a>';
                        }
                        ?></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>

        <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

        <div class="single_variation_wrap" style="display:none;">
            <?php do_action( 'woocommerce_before_single_variation' ); ?>

            <div class="single_variation"></div>

            <div class="variations_button">
                <?php woocommerce_quantity_input(); ?>
                <button type="submit" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>
            </div>

            <input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
            <input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
            <input type="hidden" name="variation_id" class="variation_id" value="" />

            <?php do_action( 'woocommerce_after_single_variation' ); ?>
        </div>

        <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

    <?php else : ?>

        <p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>

    <?php endif; ?>

</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
