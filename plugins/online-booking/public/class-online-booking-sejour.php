<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 21/01/17
 * Time: 09:50
 */
class Online_Booking_Sejour{
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * the_sejours
	 * INVITE YOU
	 * displays packages filtered by place
	 *
	 * @param int $nb
	 * @param bool $goto_booking_page
	 * @param bool $lieu
	 */
	public function the_sejours($nb = 5, $goto_booking_page = false, $lieu = false,$slider = false)
	{

		if ($lieu == false) {
			$terms = get_terms('lieu', array(
				'orderby' => 'count',
				'hide_empty' => 1,
				'parent' => 0,
			));
		} else {
			$terms = get_terms('lieu', array(
				'orderby' => 'count',
				'hide_empty' => 1,
				'parent' => 0,
				'name' => $lieu
			));
		}

		//var_dump($terms);
		foreach ($terms as $term) {
			$goToBookingPage = $goto_booking_page ? 'true' : 'false';
			$slider_class = ($slider) ? 'slick-multi' : 'grid-style' ;
			// The Loop

			$args = array(
				'post_type' => 'sejour',
				'posts_per_page' => $nb,
				'post_status' => 'publish',
				'lieu' => $term->slug
			);


			$the_query = new WP_Query($args);

			if ($the_query->have_posts()) {
				$sejour = '<h3 class="related-place"><i class="fa fa-map-marker"></i>' . $term->name . '</h3>';
				$sejour .= '<div class="blocks sejour-content pure-g"><div class="' . $slider_class . '">';

				while ($the_query->have_posts()) {
					$the_query->the_post();
					global $post,$product;
					$postID = $the_query->post->ID;
					$sejour .= $this->get_sejour_card($postID,$nb, $goto_booking_page);
				}
				wp_reset_postdata();
				$sejour .= '</div></div>';
			} else {
				$sejour = "";
			}

			echo $sejour;
		}

	}

	public function get_rand_sejour($nb = 3){

		$output = '<div class="blocks sejour-content pure-g"><div class="grid-style">';
		$args = array(
			'post_type' => 'sejour',
			'posts_per_page' => $nb,
			'post_status' => 'publish',
			'orderby'       => 'rand'
		);


		$the_query = new WP_Query($args);
		if ($the_query->have_posts()) {

			while ($the_query->have_posts()) {
				$the_query->the_post();
				global $post,$product;
				$post_id = $the_query->post->ID;
				$output .= $this->get_sejour_card($post_id,$nb);
			}
			wp_reset_postdata();
		}

		$output .= '</div></div>';

		return $output;
	}

	/**
	 * get_sejour_card
	 *
	 * @param $post_id
	 * @param int $nb
	 * @param bool $goToBookingPage
	 *
	 * @return string
	 */
	public function  get_sejour_card($post_id, $nb = 3,$goToBookingPage = false){
		$class_ux = new online_booking_ux();

		$sejour = '';
		$term_lieu = wp_get_post_terms($post_id, 'lieu');
		foreach ($term_lieu as $key => $value) {
			//echo '<span>'.$value->name.'</span> ';
		}
		$goTopage = 'true';

		$colgrid = ($nb == 3) ? 'pure-u-1 pure-u-md-1-3' : 'pure-u-1 pure-u-md-1-4';
		$personnes = get_field('personnes');
		$budget_min = get_field('budget_min');
		$budget_max = get_field('budget_max');
		$budgMin = $budget_min * $personnes;
		$budgMax = $budget_max * $personnes;
		$theme = get_field('theme');
		$lieu = get_field('lieu');
		$rows = get_field('votre_sejour');
		$row_count = count($rows);
		$lastDay = 86400 * $row_count;
		$departure_date = date("d/m/Y", time() + $lastDay);
		$arrival_date = date("d/m/Y", time() + 86400);
		//author
		$manager = get_field('manager',$post_id);
		$first_name = get_the_author_meta('first_name',$post_id);
		$last_name = get_the_author_meta('last_name',$post_id);
		$author_email = get_the_author_meta('user_email',$post_id);
		$display_name = (!empty($first_name.$last_name)) ? $first_name : get_the_author();
		$author_id = get_the_author_meta( 'ID' );
		$avatar = $class_ux->get_custom_avatar($author_id,70);
		//filters arrays to list...
		//$filter_place = (isset($term_lieu[0]))? 'data-lieu="'.$term_lieu[0].'"' : 0;
		//$filter_theme = (!empty($theme))? 'data-theme="'.$theme.'"' : 0;


		$activityObj = 1;


		$sejour .= '<div id="post-' . $post_id . '" class="block block-trip-container ' . $colgrid . '">';
		$sejour .= '<div class="block-trip">';
		$sejour .= '<h4>' . get_the_title() . '</h4>';
		if(!empty($display_name)){
			$sejour .= '<div class="sejour-author">';
			$sejour .= $avatar;
			$sejour .= __('<span class="proposed-by">proposé par').' '.$display_name;
			$sejour .= '</span></div>';
		}
		if(has_post_thumbnail()){
			$sejour .= get_the_post_thumbnail($post_id, 'square');
		} else {
			$sejour .= '<img src="'.get_wp_attachment_filter_plugin_uri().'/public/img/sejour-placeholder.gif" alt="onlyoo" />';
		}

		if(!empty(get_the_excerpt($post_id))){
			$sejour .= '<div class="presta">' . get_the_excerpt($post_id) . '</div>';
		}

		$sejour .= $this->get_sejour_json($post_id);
		$sejour .= '<a href="javascript:void(0)" class="loadit" onclick="loadTrip(us' . $post_id . ',' . $goTopage . ');">' . __('Charger ce séjour', 'online-booking') . '<i class="fa fa-plus" aria-hidden="true"></i></a>';
		$sejour .= '<a href="' . get_permalink() . '" class="seeit">Plus de détails<span class="fa 
                    fa-search" aria-hidden="true"></span></a>';
		$sejour .= '</div></div>';

		return $sejour;
	}

	/**
	 * Build the sejout json object
	 * @param $postid
	 *
	 * @return string
	 */
	public function get_sejour_json($sejour_id){

		$personnes = get_field('personnes',$sejour_id);
		$budget_min = get_field('budget_min',$sejour_id);
		$budget_max = get_field('budget_max',$sejour_id);
		$budgMin = $budget_min * $personnes;
		$budgMax = $budget_max * $personnes;
		$theme = get_field('theme',$sejour_id);
		$lieu = get_field('lieu',$sejour_id);
		$rows = get_field('votre_sejour',$sejour_id);
		$row_count = count($rows);
		$lastDay = 86400 * $row_count;
		$departure_date = date("d/m/Y", time() + $lastDay);
		$arrival_date = date("d/m/Y", time() + 86400);
		$activity_days = [];
		$activityObj = 1;

		if (have_rows('votre_sejour',$sejour_id)):
			while (have_rows('votre_sejour')) : the_row();
				$calcDay = 86400 * $activityObj;
				$actual_date = date("d/m/Y", time() + $calcDay);
				$activity_days[$actual_date] = [];
				if (have_rows('activites')):
					$i = 0;

					while (have_rows('activites')) : the_row();
						$activityArr = get_sub_field('activite');
						$post_id = (isset($activityArr->ID)) ? $activityArr->ID : false;
						if($post_id){
							$_product = wc_get_product( $post_id );
							$price = $_product->get_price();
							$term_list = wp_get_post_terms($post_id, 'reservation_type');
							$type = json_decode(json_encode($term_list), true);
							$price = (!empty($price)) ? $price : 0;
							if (isset($type[0])):
								$type_slug = $type[0]['slug'];
							else:
								$type_slug = (isset($type_slug)) ? $type_slug : "undefined var";

							endif;
							$activity_days[$actual_date][$post_id] = [
								'name'  =>  get_the_title($post_id),
								'price' => $price,
								'type'  =>  $type[0]['slug']
							];

						}
						$i++;
					endwhile;
				endif;
				$activityObj++;
			endwhile;
		endif;

		$sejour = '';

		$sejour_array = [
			"sejour"            => get_the_title(),
			"theme"             => $theme[0],
			"lieu"              => $lieu[0],
			"arrival"           => $arrival_date,
			"departure"         => $departure_date,
			"days"              => $row_count,
			"participants"      => $personnes,
			"budgetPerMin"      => $budget_min,
			"budgetPerMax"      => $budget_max,
			"globalBudgetMin"   => $budgMin,
			"globalBudgetMax"   => $budgMax,
			"currentBudget"     => $activityObj,
			"currentDay"        => $arrival_date,
			"tripObject"        => $activity_days
		];

		$sejour .= '<script>';
		$sejour .= 'var us' . $sejour_id . ' = '. json_encode($sejour_array,JSON_UNESCAPED_SLASHES);
		$sejour .= '</script>';

		return $sejour;
	}

	/**
	 * the_sejour
	 * add a button and load var reservation object
	 * @param $postid
	 * @param bool|false $single_btn
	 */
	public function the_sejour_btn($sejour_id, $single_btn = false) {

		$sejour = '';
		if ($single_btn == false):
			$sejour .= $this->get_sejour_json($sejour_id);
		endif;
		$sejour .= '<a id="CTA" href="javascript:void(0)" class="loadit" onclick="loadTrip(us' . $sejour_id . ',true);">' . __('Sélectionnez cet évènement', 'online-booking') . '</a>';
		if ($single_btn == false):
			$sejour .= '<a class="btn btn-reg grey" href="' . get_site_url() . '/' . SEJOUR_URL . '">' . __('Voir toutes nos activités', 'online-booking') . '</a>';
		endif;

		echo $sejour;

	}


	/**
	 * @param $post_id
	 *
	 * @return string
	 */
	public function get_sejour_map($post_id){
		$activities_id = $this->get_sejour_activities_ids($post_id);

		$output = '<div id="map-sejour" class="acf-map lieu-map map-marker" style="width: 100%;background: #ededed;min-height: 
		380px;">';
		$i = 0;
		foreach ($activities_id as $activity_id){
			$i++;
			$map = get_field('gps',$activity_id);
			$lat = (isset($map['lat'])) ? $map['lat'] : false;
			$lng = (isset($map['lng'])) ? $map['lng'] : false;
			$polygon = get_field('gps_polygon',$activity_id);
			$output .= '<div id="m'.$i.'" class="marker circle-type" data-lat="'.$lat.'" data-lng="'.$lng.'">'
			           .get_the_title
				($activity_id).'</div>';
		}
		$output .= '</div>';

		return $output;
	}

	public function get_sejour_activities_ids($sejour_id){
		$ids = [];
		if (have_rows('votre_sejour',$sejour_id)):
			while (have_rows('votre_sejour')) : the_row();

				if (have_rows('activites')):


					while (have_rows('activites')) : the_row();
						$activityArr = get_sub_field('activite');
						$post_id = (isset($activityArr->ID)) ? $activityArr->ID : false;
						array_push($ids, $post_id);
					endwhile;
				endif;

			endwhile;
		endif;
		return $ids;
	}

	/**
	 * Build a slider with the featured image and all post thumbnails
	 * @param $sejour_id
	 *
	 * @return string
	 */
	public function get_sejour_slider($sejour_id) {

		$post_thumbnail_id  = get_post_thumbnail_id($sejour_id);
		$post_thumbnail_url = wp_get_attachment_image_src( $post_thumbnail_id, 'full-size' );
		$post_ids = $this->get_sejour_activities_ids($sejour_id);
		$slider             = '';
		if (  $post_thumbnail_id ):
			$slider .= '<ul class="slickReservation img-gallery sejour-gallery">';

			if ( $post_thumbnail_url ) {
				$slider .= '<li style="background: url(' . $post_thumbnail_url[0] . ');">';
			}

			if(!empty($post_ids)){
				foreach ($post_ids as $post_id){
					$post_thumbnail_id  = get_post_thumbnail_id($post_id);
					$post_thumbnail_url = wp_get_attachment_image_src( $post_thumbnail_id, 'full-size' );
					$slider .= '<li style="background: url(' . $post_thumbnail_url[0] . ');">';
				}
			}


			$slider .= '</ul>';
		endif;

		return $slider;

	}

	/**
	 * @param $sejour_id
	 *
	 * @return int
	 */
	public function get_sejour_price($sejour_id){
		$post_ids = $this->get_sejour_activities_ids($sejour_id);
		$price = 0;
		if(!empty($post_ids)){
			foreach ($post_ids as $product_id){

				$_product = wc_get_product( $product_id );
				//$_product->get_regular_price();
				//$prod_price = $_product->get_sale_price();
				if(is_object($_product)){
					$prod_price = $_product->get_price();
					$price += intval($prod_price);
				}


			}
		}

		return intval($price);

	}

	/**
	 * @param $sejour_id int
	 *
	 * @return string
	 */
	public function get_sejour_duration($sejour_id){
		$osu = new online_booking_ux();

		$post_ids = $this->get_sejour_activities_ids($sejour_id);
		$duration = '';
		$days = 0;
		$hours = 0;
		$minutes = 0;
		if(!empty($post_ids)){
			$i = 0;
			foreach ($post_ids as $product_id){
				//$_product = wc_get_product( $product_id );
				$product_duration = $osu->get_activity_time( $product_id,'array');
				//array_push($duration, $product_duration);
				$days += (isset($product_duration['days'])) ? $product_duration['days'] : 0;
				$hours += (isset($product_duration['hours'])) ? $product_duration['hours'] : 0;
				$minutes += (isset($product_duration['min'])) ? $product_duration['min'] : 0;

				$i++;
			}
		}
		//get labels
		$labels = $osu->get_time_labels($days,$hours,$minutes);
		$get_days = ($days > 0) ? $days . ' ' . $labels['days'] : '';
		$get_hours = ($hours > 0) ? $hours . ' ' . $labels['hours'] : '';
		$get_minutes = ($minutes > 0) ? $minutes . ' ' . $labels['min'] : '';

		$duree = $get_days.$get_hours.$get_minutes;

		return $duree;
	}

	/**
	 * get the sejour uri to modify
	 * @param bool $sejour_id int
	 *
	 * @return string|void
	 */
	public function get_sejour_modify_uri($sejour_id = false){
		if(!$sejour_id){
			$sejour_id = get_the_ID();
		}
		$link = home_url('dashboard/ajouter-un-programme?edit='.$sejour_id);

		return $link;
	}


}