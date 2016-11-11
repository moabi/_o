<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 08/11/16
 * Time: 08:35
 */
global $product;
$variations = $product->get_available_variations();

echo '<select name="product-variation">';
foreach ($variations as $v){
	if($v['variation_is_visible'] && $v['variation_is_active'] ){
		foreach ($v['attributes'] as $a){
			echo '<option data-price="'.$v['display_price'].'" value="'.$v['variation_id'].'" >'.$a;
		}
	}
}
echo '</select>';