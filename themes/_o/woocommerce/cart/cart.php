<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();
$i = 0;
foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	$trip_uuid = $cart_item['trip_uuid'];

	$i++;
	if($i==1) break;
}
do_action( 'woocommerce_before_cart' );

$ob_budget = new online_booking_budget;
?>
<div class="breadcrumb">
	<a href="">Mes projets</a> <span>></span><span><?php echo $ob_budget->get_trip_informations('booking-name',$trip_uuid); ?></span>
</div>
<div class="activity-budget-user">
<div class="pure-g">
	<div class="pure-u-1-4">
		<i class="fa fa-map-marker" aria-hidden="true"></i>
		Lieu: <?php echo $ob_budget->get_trip_informations('place',$trip_uuid); ?>
	</div>
	<div class="pure-u-1-4">
		<i class="fa fa-users" aria-hidden="true"></i>
		Participants: <?php echo $ob_budget->get_trip_informations('participants',$trip_uuid); ?> personne(s)
	</div>
	<div class="pure-u-1-4">
		<i class="fa fa-clock-o" aria-hidden="true"></i>
		Durée: <?php echo $ob_budget->get_trip_informations('duree',$trip_uuid); ?>
	</div>
	<div class="pure-u-1-4">
		<i class="fa fa-calendar-o" aria-hidden="true"></i>
		Date: <?php echo $ob_budget->get_trip_informations('dates',$trip_uuid); ?>
	</div>
</div>
</div>


<form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<div class="shop_table shop_table_responsive cart " cellspacing="0">

	<div class="table-header brown-head">
	<div class="pure-g">
			<div class="product-name pure-u-16-24">Prestation</div>
			<div class="product-price pure-u-3-24">Prix/participant</div>
			<div class="product-quantity pure-u-3-24">Nombre de participants</div>
			<div class="product-subtotal pure-u-2-24">-</div>
	</div>
	</div>

		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			?>
	<div class="event-body">
		<div class="pure-g">
			<?php
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			//var_dump($_product);
			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> pure-u-1">
					<div class="pure-g">



					<div class="product-thumbnail pure-u-4-24">
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $product_permalink ) {
								echo $thumbnail;
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
							}
						?>

					</div>
						<div class="pure-u-3-24">
Activité
						</div>
						<div class="pure-u-9-24" data-title="<?php _e( 'Product', 'woocommerce' ); ?>">
							<h3>
							<?php
							if ( ! $product_permalink ) {
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
							} else {
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_title() ), $cart_item, $cart_item_key );
							}
							?>
							</h3>
							<?php
							echo $_product->post->post_excerpt; ?>

<?php
							// Backorder notification
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
							}
							?>
							<?php
							// Meta data
							echo WC()->cart->get_item_data( $cart_item );
							?>
							<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
							?>
						</div>





						<div class="product-subtotal pure-u-3-24" data-title="<?php _e( 'Total', 'woocommerce' ); ?>">
							<?php
							//echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
							echo $_product->get_price().' €';
							?>
						</div>




					<div class="product-quantity pure-u-3-24" data-title="<?php _e( 'Quantity', 'woocommerce' ); ?>">
						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  => "cart[{$cart_item_key}][qty]",
									'input_value' => $cart_item['quantity'],
									'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
									'min_value'   => '0'
								), $_product, false );
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
						?>
					</div>



						<div class="product-remove pure-u-2-24">
							<?php
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s"><i class="fa fa-trash-o" aria-hidden="true"></i> </a>',
								esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
								__( 'Remove this item', 'woocommerce' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() )
							), $cart_item_key );
							?>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
			<?php
		}

		do_action( 'woocommerce_cart_contents' );
		?>
		<div class="pure-u-1">
			<div class=" actions">

				<?php if ( wc_coupons_enabled() ) { ?>
					<div class="coupon">

						<label for="coupon_code"><?php _e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'woocommerce' ); ?>" />

						<?php do_action( 'woocommerce_cart_coupon' ); ?>
					</div>
				<?php } ?>

				<input type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update Cart', 'woocommerce' ); ?>" />

				<?php do_action( 'woocommerce_cart_actions' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</div>


<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">

	<?php do_action( 'woocommerce_cart_collaterals' ); ?>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
