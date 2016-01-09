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

<table class="variations variations-grid" cellspacing="0" summary="Variations">
    <thead>
        <tr>
            <?php foreach ($attributes as $i => $v) : ?>
                <th><?php echo wc_attribute_label($i); ?></th>
            <?php endforeach; ?>
            <th class="sku">SKU</th>
            <th class="currency">Price</th>
            <th class="form"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($visible_active_variations as $i => $variation) : ?>
            <tr>
                <?php foreach ($attributes as $j => $a) : ?>
                    <td class="attribute"><span><?php echo implode('</span><span>', array_map(function ($o) { return esc_html( apply_filters( 'woocommerce_variation_option_name', $o ) ); }, array_intersect($a, $variation['attributes']))); ?></span></td>
                <?php endforeach; ?>
                <td class="sku"><?php echo $variation['sku']; ?></td>
                <td class="currency"><?php echo $variation['price_html'];?></td>
                <td class="form">
                    <?php if( $variation['is_in_stock'] ) : ?>
                        <form class="cart" action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" method="post" enctype='multipart/form-data'>
                            <?php woocommerce_quantity_input(array('min_value' => 1)); ?>
                            <?php if(!empty($variation['attributes'])) : ?>
                                <?php foreach ($variation['attributes'] as $attr_key => $attr_value) : ?>
                                    <input type="hidden" name="<?php echo $attr_key?>" value="<?php echo $attr_value?>">
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <button type="submit" class="single_add_to_cart_button btn btn-primary alt"><span class="glyphicon glyphicon-tag"></span> Add to cart</button>
                            <input type="hidden" name="variation_id" value="<?php echo $variation['variation_id']?>" />
                            <input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
                            <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $post->ID ); ?>" />
                        </form>
                    <?php else: ?>
                        <p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="<?php echo 3 + count($attributes); ?>"></td>
        </tr>
    </tfoot>
</table>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
