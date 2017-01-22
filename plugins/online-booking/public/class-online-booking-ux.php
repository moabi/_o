<?php

/**
 * Class online_booking_ux
 */
class online_booking_ux {

	/**
	 * get_onlyoo_admin_trip_manager
	 * allow admin to load a user trip
	 * modify it
	 * save it as a new invoice/quote
	 * Send email to user
	 * @return string
	 */
	public function get_onlyoo_admin_trip_manager() {
		$modify = ( isset( $_GET['mod'] ) ) ? true : false;
		if ( ( current_user_can( 'administrator' ) || current_user_can( 'onlyoo_team' ) ) && $modify == true ) {
			global $wpdb;
			$validation = 0;
			//Get 30 last - if not in, user should be able to request by ID
			$sql = $wpdb->prepare( " 
						SELECT *
						FROM " . $wpdb->prefix . "online_booking a	
						WHERE a.validation = %d
						LIMIT 30
						", $validation );

			$results = $wpdb->get_results( $sql );

			$output = '<div id="ob-trip-manager">';
			$output .= 'Load a trip :';
			$output .= '<ul>';
			$output .= '<li><i class="fa fa-chevron-down"></i>Select trip<ul class="dropdown">';
			foreach ( $results as $result ) {
				$booking   = $result->booking_object;
				$bdate     = $result->booking_date;
				$tripID    = $result->ID;
				$tripName  = $result->booking_ID;
				$tripDate  = $result->booking_date;
				$newDate   = date( "d/m/y", strtotime( $tripDate ) );
				$userID    = $result->user_ID;
				$user_info = get_userdata( $userID );

				$output .= '<li data-value="' . $result->ID . '">';
				$output .= '<script>var trip' . $result->ID . ' = ' . $booking . '</script>';
				$output .= $user_info->user_email;
				$output .= '<br /><a onclick="loadTrip(trip' . $result->ID . ',false)" href="#">' . $tripName . '</a>';
				$output .= ' (' . $newDate . ')';

				$output .= '</li>';
			}
			$output .= '</ul></li></ul>';

			$output .= '<a onclick="saveUserTripByAdmin()" href="#" class="btn btn-reg">SAVE</a>';

			$output .= '</div>';
		} else {
			$output = '';
		}

		return $output;
	}

	/**
	 * @return string
	 */
	public function get_filters() {

		$output = '';
		// no default values. using these as examples
		$taxonomies = array(
			'reservation_type'
		);

		$args = array(

			'hide_empty'        => true,
			'exclude'           => array(),
			'exclude_tree'      => array(),
			'include'           => array(),
			'number'            => '',
			'fields'            => 'all',
			'slug'              => '',
			'parent'            => 0,
			'hierarchical'      => true,
			'child_of'          => 0,
			'childless'         => false,
			'get'               => '',
			'name__like'        => '',
			'description__like' => '',
			'pad_counts'        => false,
			'offset'            => '',
			'search'            => '',
			'cache_domain'      => 'core',
			'order'             => 'ASC'
		);

		$terms = get_terms( $taxonomies, $args );

//var_dump($terms);
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$output .= '<ul id="typeterms" class="sf-menu hidden">';
			foreach ( $terms as $term ) {
				//var_dump($term);
				$fa_icon = get_field( 'fa_icon', $term->taxonomy . '_' . $term->term_id );
				$output .= '<li>';
				$output .= '<span><i class="fa ' . $fa_icon . '"></i><input id="term-' . $term->term_id . '" type="checkbox" name="typeactivite" value="' . $term->term_id . '" />';
				$output .= '<label for="term-' . $term->term_id . '">' . $term->name . '</label></span>';
				$args       = array(
					'hide_empty'   => true,
					'child_of'     => $term->term_id,
					'cache_domain' => 'core',
					'order'        => 'ASC'
				);
				$childTerms = get_terms( $taxonomies, $args );
				if ( ! empty( $childTerms ) && ! is_wp_error( $terms ) ) {
					$output .= '<ul class="sub">';
					foreach ( $childTerms as $childterm ) {

						$output .= '<li><span>';
						$output .= '<input id="term-' . $childterm->term_id . '" type="checkbox" name="typeactivite" value="' . $childterm->term_id . '" />';
						$output .= '<label for="term-' . $childterm->term_id . '">' . $childterm->name . '</label>';
						$output .= '</span></li>';
					}
					$output .= '</ul>';
				}
				$output .= '</li>';

			}
			$output .= '<li id="search-filter"><input name="ob_s" id="ob-s" type="text" value="" placeholder="Rechercher" /><i class="fa fa-search js-sub-s"></i></li>';
			$output .= '</ul>';
		}

		return $output;

	}


	/**
	 * get_checkbox_taxonomy
	 * display the taxonomy in list style with checkbox for forms
	 * @param $taxonomy
	 * @param array $args
	 * @param array $selected
	 *
	 * @return string
	 */
	public function get_checkbox_taxonomy($taxonomy, $args = array( 'hide_empty' => 0 ), $selected= []) {
		$output = '';

		//Set up the taxonomy object and get terms
		$tax   = get_taxonomy( $taxonomy );
		$terms = get_terms( $taxonomy, array( 'hide_empty' => 0 ) );

		//Name of the form
		$name = 'tax_filter[' . $taxonomy . ']';

		$output .= '<ul id="' . $taxonomy . '" class=" terms-change list-checkbox tax-'.$taxonomy .'">';
		foreach ( $terms as $term ) {
			$checked = (in_array($term->term_id, $selected)) ? 'checked': '';
			$id = $taxonomy . '-' . $term->term_id;
			$output .= '<li id="' . $id . '"><label class="selectit">';
			$output .= '<input '.$checked.' class="check-'.$taxonomy.'" type="checkbox" id="in-' . $id . '" name="'.$name.'" value="'.$term->term_id.'" />' . $term->name ;
			$output .= '</label></li>';
		}

		$output .= '</ul>';

		return $output;
	}

	/**
	 * slider
	 * provide a slider utility from acf image galery field (gallerie)
	 *
	 * @return string
	 */
	public function slider() {
		global $product;
		$attachment_ids     = $product->get_gallery_attachment_ids();
		$post_thumbnail_id  = get_post_thumbnail_id();
		$post_thumbnail_url = wp_get_attachment_image_src( $post_thumbnail_id, 'full-size' );
		$missing_img_uri = get_wp_attachment_filter_plugin_uri().'public/img/missing-image.gif';

		//$images = get_field('gallerie');
		$slider = '';

		$slider .= '<ul class="slickReservation img-gallery product-gallery">';
		if ( $post_thumbnail_url ) {
			$slider .= '<li style="background: url(' . $post_thumbnail_url[0] . ');">';
		} elseif (!$post_thumbnail_url && !$attachment_ids){
			$slider .= '<li style="background: url(' . $missing_img_uri . ');">';
		}
		if ( $attachment_ids ) {
			foreach ( $attachment_ids as $attachment_id ):
				$image_link = wp_get_attachment_url( $attachment_id );
				$slider .= '<li style="background: url(' . $image_link . ');">';
				$slider .= '</li>';
			endforeach;
		}
		$slider .= '</ul>';


		return $slider;

	}

	public function acf_img_slider() {
		$images             = get_field( 'gallerie' );
		$post_thumbnail_id  = get_post_thumbnail_id();
		$post_thumbnail_url = wp_get_attachment_image_src( $post_thumbnail_id, 'full-size' );
		$slider             = '';
		if ( $images || $post_thumbnail_id ):
			$slider .= '<ul class="slickReservation img-gallery sejour-gallery">';
			if ( $post_thumbnail_url ) {
				$slider .= '<li style="background: url(' . $post_thumbnail_url[0] . ');">';
			}
			if ( $images ) {
				foreach ( $images as $image ):
					$slider .= '<li style="background: url(' . $image['sizes']['full-size'] . ');">';
					$slider .= '</li>';
				endforeach;
			}

			$slider .= '</ul>';
		endif;

		return $slider;

	}

	/**
	 * socialShare
	 * Add some social share links
	 *
	 * @return string
	 */
	public function socialShare() {

		$shortURL   = get_permalink();
		$shortTitle = get_the_title();

		// Get URLS
		$twitterURL  = 'https://twitter.com/intent/tweet?text=' . $shortTitle . '&amp;url=' . $shortURL . '&amp;via=onlyoo';
		$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u=' . $shortURL;
		$googleURL   = 'https://plus.google.com/share?url=' . $shortURL;
		$linkedin    = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $shortURL . '&title=Onlyoo&summary=&source=' . $shortURL;
		$target      = 'target="_blank"';

		// Add sharing button at the end of page/page content
		$social_share = '<div class="crunchify-social">';

		$social_share .= '<span class="cr-txt">Partager</span>';
		$social_share .= '<a class="crunchify-link crunchify-twitter" href="' . $twitterURL . '" ' . $target . '><i class="fs1 fa fa-twitter" aria-hidden="true"></i></a>';
		$social_share .= '<a class="crunchify-link crunchify-facebook" href="' . $facebookURL . '" ' . $target . '><i class="fs1 fa fa-facebook" aria-hidden="true"></i></a>';
		$social_share .= '<a class="crunchify-link crunchify-googleplus" href="' . $googleURL . '" ' . $target . '><i class="fs1 fa fa-google-plus" aria-hidden="true"></i></a>';
		$social_share .= '<a class="crunchify-link crunchify-linkedin" href="' . $linkedin . '" ' . $target . '><i class="fs1 fa fa-linkedin" aria-hidden="true"></i></a>';

		$social_share .= '</div>';


		return $social_share;
	}

	/**
	 * get_activity_time
	 * return a correct format for activity duration
	 * @return string/array
	 */
	public function get_activity_time($post_id, $format = 'default') {

		$days    = ( get_field( 'duree-j',$post_id ) ) ? get_field( 'duree-j',$post_id ) : '';
		$hours   = ( get_field( 'duree',$post_id ) ) ? get_field( 'duree',$post_id ) : '';
		$minutes = ( get_field( 'duree-m',$post_id )) ? get_field( 'duree-m',$post_id ) : '';

		$labels = $this->get_time_labels($days,$hours,$minutes);

		if($format == 'default'){
			$duree = $days . ' ' . $labels['days'] . ' ' . $hours . ' ' . $labels['hours'] . ' ' . $minutes . ' ' . $labels['min'];
		} else{
			$duree = array(
				'days'  => 	intval($days),
				'hours' => intval($hours),
				'min'   => intval($minutes)
			);
		}


		if(empty($days) && empty($hours) && empty($minutes)){
			$duree = '';
		}

		return $duree;
	}

	/**
	 * @param $days
	 * @param $hours
	 * @param $min
	 *
	 * @return array
	 */
	public function get_time_labels($days,$hours,$minutes){
		if ( intval($days) > 1 ) {
			$days_label = 'jours';
		} elseif ( $days == 1 ) {
			$days_label = 'jour';
		} else {
			$days_label = '';
		}

		if ( intval($hours) > 1 ) {
			$hours_label = 'heures';
		} elseif ( $hours == 1 ) {
			$hours_label = 'heure';
		} else {
			$hours = '';
			$hours_label = '';
		}

		if ( intval($minutes) > 1 ) {
			$minutes_label = 'minutes';
		} elseif ( intval($minutes) == 1 ) {
			$minutes_label = 'minute';
		} else {
			$minutes = '';
			$minutes_label = '';
		}

		$labels = array(
			'days'  => $days_label,
			'hours' => $hours_label,
			'min'   => $minutes_label
		);

		return $labels;
	}
	/**
	 * get_reservation_type()
	 * get the type/category for an activity
	 *
	 * @param $id integer post->ID
	 * @param bool|false $class if true, output only class
	 *
	 * @return string
	 */
	public function get_reservation_type( $id, $class = false ) {
		$term_reservation_type = wp_get_post_terms( $id, 'reservation_type' );
		$data                  = '';

		if ( ! empty( $term_reservation_type ) && $id ) {
			$i = 0;
			foreach ( $term_reservation_type as $key => $value ) {
				//get only top taxonomy
				if ( intval( $value->parent ) == 0 && $i == 0 ):
					$i ++;
					//get fa icon linked to taxonomy in the custom field
					$id_term = 'reservation_type_' . $value->term_id;
					$icon    = ( get_field( 'fa_icon', $id_term ) ) ? get_field( 'fa_icon', $id_term ) : 'fa-trophy';
					if ( $class == false ) {
						$data .= '<i class="fa ' . $icon . '"></i>' . $value->name;
					} else {
						$data .= $icon;
					}

				endif;


			}
		}


		return $data;
	}

	/**
	 * get_place
	 * get the location for an activity - should be mixed with get_reservation_type
	 *
	 * @param $id
	 * @param bool|true $html
	 * @param bool|true $sejour
	 *
	 * @return string
	 */
	public function get_place( $id, $html = true, $sejour = false ) {
		$term_lieu = wp_get_post_terms( $id, 'lieu' );
		$data      = '';
		$break     = ( $sejour ) ? '<br />' : '';
		$str_start = ( $sejour ) ? '<strong>' : '';
		$str_end   = ( $sejour ) ? '</strong>' : '';
		if ( $html == true && ! empty( $term_lieu ) ) {
			if ( $id ):
				$data .= '<i class="fa fa-map-marker" aria-hidden="true"></i>';
				$i = 0;
				foreach ( $term_lieu as $key => $value ) {
					$term_link = get_term_link( $value );
					if ( $i == 0 ) {
						$data .= 'Lieu : ' . $break . $str_start . '<a href="' . esc_url( $term_link ) . '">' . $value->name . '</a>' . $str_end;
					} else {
						$data .= ', <a href="' . esc_url( $term_link ) . '">' . $value->name . '</a>';
					}

					$i ++;
				}
			endif;
		} else {
			if ( $id ):
				$i = 0;
				foreach ( $term_lieu as $key => $value ) {
					$term_link = get_term_link( $value );
					if ( $i == 0 ) {
						$data .= $value->name;
					}
					$i ++;
				}
			endif;
		}

		return $data;
	}


	/**
	 * get_theme_terms
	 * get the theme terms for a post
	 *
	 * @param $id integer post->ID
	 *
	 * @return string
	 */
	public function get_theme_terms( $id ) {
		$term_type = wp_get_post_terms( $id, 'theme' );
		$data      = '';
		if ( ! empty( $id ) ) {
			$data .= '<div class="tags-s pure-g">';
			$data .= '<i class="fs1 fa fa-tag" aria-hidden="true"></i>';
			foreach ( $term_type as $key => $value ) {
				$term_link = get_term_link( $value );
				$data .= '<span><a href="' . esc_url( $term_link ) . '">' . $value->name . '</a></span> ';
			}
			$data .= '</div>';
		}

		return $data;
	}


	/**
	 * single_reservation_btn
	 * get some buttons for a single activity
	 *
	 * @param $id integer $post->ID
	 *
	 * @return string
	 */
	public function single_reservation_btn( $id ) {

		$content = '<a id="CTA" class="btn btn-reg" href="' . site_url() . '/' . BOOKING_URL . '/?addId=' . $id . '">' . __( 'Ajouter cette activité', 'online-booking' ) . '</a>';
		$content .= '<a class="btn btn-reg grey" href="' . site_url() . '/' . SEJOUR_URL . '/">' . __( 'Consulter toutes nos activités', 'online-booking' ) . '</a>';

		return $content;
	}

	/**
	 * @param $post_id
	 * @param $data
	 *
	 * @return mixed
	 */
	public function get_single_product_people($post_id,$data = false){
		$max_people = get_field('nombre_de_personnes', $post_id);
		$min_people = get_field('minimum_people', $post_id);

		if(!$data){
			$output = '';
			if($max_people == 1){
				$output .= 'Pour : <strong>1</strong> <b>personne</b>';
			} elseif ($max_people > 1 && (!$min_people || $min_people == 1)){
				$output .= 'Jusqu’à : <strong>' . $max_people . '</strong> <b>personnes</b>';
			} elseif ($max_people > 1 && $min_people > 1){
				$output .= 'Entre : <strong>'.$min_people.' et ' . $max_people . '</strong> <b>personnes</b>';
			}

			return $output;

		} else {
			$output = array();
			$output['min'] = $min_people;
			$output['max'] = $max_people;
		}


		}

	/**
	 * trash an activity for a sejour
	 *
	 *
	 * @param $day_number
	 * @param $id $post->ID
	 *
	 * @return string
	 */
	public function get_trash_btn( $day_number, $id ) {
		global $post;
//$price = get_field('prix', $id);
		$_product    = wc_get_product( $id );
		$price       = $_product->get_price();
		$object_name = 'us' . $post->ID;
		$data        = '<i title="Supprimer cette activité" onclick="deleteSejourActivity(' . $day_number . ',' . $id . ',' . $price . ',' . $object_name . ');" class="fa fa-trash-o"></i>';

		return $data;

	}


	/**
	 * get_sejour
	 *
	 * @return string
	 */
	public function get_sejour() {
		$output = '';
// check for rows (parent repeater)
		if ( have_rows( 'votre_sejour' ) ):
			$output .= '<div id="event-trip-planning">';
			$i = 1;
			while ( have_rows( 'votre_sejour' ) ): the_row();

				$output .= '<div class="event-day day-content">';
				$output .= '<div class="etp-day">';
				$output .= '<div class="day-title">';
				$output .= '<i class="fa fa-calendar"></i><br />';
				$output .= __( 'Journée', 'online-bookine' );

				$output .= ' ' . $i . '</div></div>';
				// check for rows (sub repeater)

				if ( have_rows( 'activites' ) ):
					$output .= '<div class="etp-days">';

					$activity_count = 0;
					while ( have_rows( 'activites' ) ): the_row();
						// display each item as a list - with a class of completed ( if completed )
						$activity = get_sub_field( 'activite' );
						$post_id = (isset($activity->ID)) ? $activity->ID : false;

						$post_status = get_post_status( $post_id );

						if ( $post_status == "publish" && $post_id ):

							$exerpt = get_the_excerpt( $post_id );

							$output .= '<div data-id="' . $post_id . '" class="pure-u-1 single-activity-row">';
							$output .= '<span class="round"></span><span class="trait s-' . $i . '"></span>';

							$output .= '<div class="pure-g"><div class="pure-u-1 head">';
							$output .= '<div class="tags">' . $this->get_reservation_type( $post_id ) . '</div>';
							$output .= $this->get_trash_btn( $i - 1, $post_id );
							$output .= '</div></div>';

							$output .= '<div class="pure-g">';
							$output .= '<div class="pure-u-1 pure-u-md-7-24">';
							$output .= '<a href="' . get_permalink( $post_id ) . '">';
							$output .= get_the_post_thumbnail( $post_id, array( 250, 180 ) );
							$output .= '</a>';
							$output .= '</div>';

							$output .= '<div class="pure-u-1 pure-u-md-17-24">';
							$output .= '<div class="padd">';
							$output .= '<h3>';
							$output .= '<a href="' . get_permalink( $post_id ) . '">';
							$output .= $activity->post_title;
							$output .= '</a></h3>';
							$output .= $exerpt;
							$output .= '</div>';
							$output .= '</div>';

							$output .= '</div>';

							$output .= '</div>';
						endif;


					endwhile;
					$output .= '</div>';
				endif; //if( get_sub_field('items') ):
				$output .= '</div>';
				$i ++;
			endwhile; // while( has_sub_field('to-do_lists') ):
			$output .= '</div>';
		endif; // if( get_field('to-do_lists') ):

		return $output;
	}

	/**
	 * my_custom_my_account_menu_items
	 * Insert the new endpoint into the My Account menu.
	 * TODO: check if filter by user role works
	 * 3 roles: partner, entreprise,particulier
	 * partner :
	 *  - add activities & pack, no booking
	 *  - payment dashboard
	 * entreprise & particulier :
	 *  - booking only
	 *  - no right to add activity
	 *
	 * @param array $items
	 *
	 * @return array
	 */
	public function wcvendors_my_account_menu_items( $items ) {


// Remove the logout menu item.
		if ( isset( $items['customer-logout'] ) ) {
			$logout = $items['customer-logout'];
			unset( $items['customer-logout'] );
		}


// vendors only
		if ( current_user_can( 'vendor' ) || current_user_can( 'pending_vendor' ) ) {

			unset( $items['downloads'] );
			unset( $items['orders'] );
			unset( $items['settings'] );

			$items['dashboard/product'] = __( 'Prestations', 'online-booking' );
			$items['product']           = __( 'Mes prestations', 'online-booking' );
		}

		//PROJECT MANAGER
		if ( current_user_can( 'project_manager' ) ) {
			$items['mes-devis']  = __( 'Mes devis', 'online-booking' );
			$items['messagerie'] = __( 'Messagerie', 'online-booking' );
			$items['prestataires'] = __( 'Prestataires', 'online-booking' );

		}

		//PROJECT MANAGER
		if ( current_user_can( 'administrator' ) ) {
			$items['mes-devis']  = __( 'Mes devis', 'online-booking' );
			$items['messagerie'] = __( 'Messagerie', 'online-booking' );

		}


		if ( isset( $items['customer-logout'] ) ) {
			// Insert back the logout item.
			$items['customer-logout'] = $logout;
		}


		return $items;

	}

	/**
	 * online_booking_widgets_init
	 * register a new sidebar for the ACCOUNT in woocommerce
	 */
	public function online_booking_widgets_init() {
		register_sidebar(
			array(
				'name'          => __( 'Account Sidebar', 'online-booking' ),
				'id'            => 'sidebar-account',
				'description'   => __( 'Widgets in this area will be shown on all account pages.', 'online-booking' ),
				'before_widget' => '<div id="%1$s" class="widget-account %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-account-title">',
				'after_title'   => '</h2>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Vendor Sidebar', 'online-booking' ),
				'id'            => 'sidebar-vendor',
				'description'   => __( 'Widgets in this area will be shown on all vendor pages.', 'online-booking' ),
				'before_widget' => '<div id="%1$s" class="widget-vendor widget-account %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-account-title">',
				'after_title'   => '</h2>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Vendor Account', 'online-booking' ),
				'id'            => 'sidebar-vendor-account',
				'description'   => __( 'Widgets in this area will be shown on all vendor pages.', 'online-booking' ),
				'before_widget' => '<div id="%1$s" class="widget-vendor widget-account %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-account-title">',
				'after_title'   => '</h2>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Vendor Profile', 'online-booking' ),
				'id'            => 'sidebar-vendor-profile',
				'description'   => __( 'Widgets in this area will be shown on all vendor pages.', 'online-booking' ),
				'before_widget' => '<div id="%1$s" class="widget-vendor widget-account %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h2 class="widget-account-title">',
				'after_title'   => '</h2>',
			)
		);

	}

	/**
	 * get_custom_avatar
	 * @param int | object $user_id
	 * @param int $size
	 *
	 * @return string
	 */
	public function get_custom_avatar($user_id = 0,$size = 50, $class = 'avatar photo'){
		$output = '';
		$avatar_args = array(
			'class' => 'avatar photo'
		);
		//var_dump($user_id);
		global $current_user;
		wp_get_current_user();
		if(is_int($user_id)){
			$user_id = ($user_id == 0) ? $current_user->ID : intval($user_id);
		}elseif (is_object($user_id)){
			$user_id = (isset($user_id->DATA->ID)) ? $user_id->DATA->ID : $current_user->ID;
		}

		$image_url = get_user_meta($user_id, 'wp_user_avatar', true);
		if( is_array($image_url) ){
			foreach ($image_url as $img){
				$output .= '<img src="'.$img['file_url'].'" class="custom-avatar '.$class.'" style="width:'.$size.'px; height:'.$size.'px;"  alt=""  />';
			}


		} else {
			$output .= get_avatar( $user_id, $size,'default-avatar','avatar', $avatar_args );
		}

		return $output;

	}


	public function get_avatar_form($size = '92'){
		$user_id = get_current_user_id();
		$avatar_form = esc_attr( get_option('ob_avatar_shortcode') );

		$output = '<div class="avatar-change">';
		$output .= $this->get_custom_avatar($user_id,$size);
		if($avatar_form){
			$output .= '<a href="#set-avatar" class="js-change-avatar camera open-popup-link">';
			$output .= '<i class="fa fa-camera" aria-hidden="true"></i>';
			$output .= '</a>';
			$output .= '<div id="set-avatar" class="white-popup mfp-hide">';
			$output .= '<h3 class="brown-bg" style="color:#fff;position:absolute;top:0;left:0;width:100%;padding:1em 0;text-indent:2em;font-weight:300">Votre image de profil</h3>';
			$output .= '<div style="margin-top:4em;">';
			$output .= $this->get_custom_avatar($user_id,$size);
			$output .= do_shortcode($avatar_form);
			$output .= '</div>';
			$output .= '</div>';
		}
		$output .= '</div>';

		return $output;
	}
	/**
	 * @param int $user_id
	 *
	 * @return mixed
	 */
	public function get_custom_avatar_uri($user_id = 0){

		$output = '';
		$image_url = get_user_meta($user_id, 'wp_user_avatar', true);
		$args = get_avatar_data( 0 );
		$default_avatar_uri = (isset($args['url'])) ? $args['url'] : '';
		if( is_array($image_url) ){
			foreach ($image_url as $img){
				$output = (isset($img['file_url'])) ? $img['file_url'] : '';
			}
		}

		$avatar_uri = (!empty($output)) ? $output : $default_avatar_uri;

		return $avatar_uri;

	}


	/**
	 * filter_profile_avatar
	 * $this->loader->add_filter('get_avatar',$plugin_ux, 'filter_profile_avatar', 10, 3);
	 * @param $avatar
	 * @param $id_or_email
	 * @param $size
	 *
	 * @return string
	 */
	public function filter_profile_avatar( $avatar, $id_or_email, $size = 50 ) {
		$user = false;

		if ( is_numeric( $id_or_email ) ) {

			$id = (int) $id_or_email;
			$user = get_user_by( 'id' , $id );

		} elseif ( is_object( $id_or_email ) ) {

			if ( ! empty( $id_or_email->user_id ) ) {
				$id = (int) $id_or_email->user_id;
				$user = get_user_by( 'id' , $id );
			}

		} else {
			$user = get_user_by( 'email', $id_or_email );
		}


		if ( $user && is_object( $user ) ) {
				$user_id = (isset($id)) ? $id : 1;
				$avatar_uri = $this->get_custom_avatar_uri($user_id);
				$avatar = '<img src="'.$avatar_uri.'" style="width:'.$size.'px; height:'.$size.'px;  alt="" class="avatar photo"/>';
		}


		return $avatar;
	}

	public function fep_cus_fep_menu_buttons( $menu ) {
		unset( $menu['settings'] );
		unset( $menu['announcements'] );

		return $menu;
	}

	public function get_dahsboard_menu() {
		$output    = '';


		$output .= '<div class="ob-account-nav">';
		$output .= '<a href="#" class="js-toggle-dashboard-menu mobile-only"><i class="fa fa-bars"></i>MENU</a>';

		if ( current_user_can( 'vendor' ) ) {
			//echo do_shortcode('[wcv_pro_dashboard_nav]');
			$output .= wp_nav_menu( array(
				'theme_location'  => 'vendor',
				'menu_class'      => 'menu black pure-menu-list',
				'container_class' => 'wcv-navigation pure-menu pure-menu-horizontal',
				'echo'            => false,
				'walker'          => new pure_walker_nav_menu
			) );

		} elseif ( current_user_can( 'project_manager' ) ) {

			$output .= wp_nav_menu( array(
				'menu'  => 'Project Manager Menu',
				'menu_class'      => 'menu black pure-menu-list',
				'container_class' => 'wcv-navigation pure-menu pure-menu-horizontal',
				'echo'            => false,
				'walker'          => new pure_walker_nav_menu
			) );

		} elseif ( is_user_logged_in() && (!current_user_can( 'vendor' )  || !current_user_can( 'project_manager' )) ) {
			ob_start();
			do_action( 'woocommerce_account_navigation' );
			$nav = ob_get_contents();
			ob_end_clean();
			$output .= $nav;
		}
		$output .= '</div>';

		return $output;
	}

	public function get_unread_news() {

		$fep     = new Fep_Message();
		$user_id = get_current_user_id();

		$args = array(
			'post_type'      => 'fep_message',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'meta_query'     => array(
				array(
					'key'     => '_participants',
					'value'   => $user_id,
					'compare' => '='
				),
				array(
					'key'     => '_fep_delete_by_' . $user_id,
					'compare' => 'NOT EXISTS'
				),
				array(
					'key'     => '_fep_parent_read_by_' . $user_id,
					'compare' => 'NOT EXISTS'
				)

			)
		);

		// The Query
		$the_query = new WP_Query( $args );

		// The Loop
		if ( $the_query->have_posts() ) {
			$output = '';
			$output .= '<div class="pure-g">';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				global $post;
				$output .= '<div class="pure-u-1 message">';
				$output .= '<i class="fa fa-envelope" aria-hidden="true"></i> ';
				$output .= get_the_title();
				$output .= '<span class="push-right">' . get_post_time( 'l d F H:m', false, $post->ID, 'fr-FR' ) . '</span>';
				$output .= '</div>';
			}
			$output .= '<div class="pure-u-1">';
			$output .= '<a href="' . get_bloginfo( 'url' ) . '/' . MESSENGER . '" class="btn btn-reg push-right">Voir tous les messages</a>';
			$output .= '</div>';
			$output .= '</div>';


			/* Restore original Post Data */
			wp_reset_postdata();
		} else {
			// no posts found
			$output = '<div class="fep-error">' . __( 'Pas de message.', 'front-end-pm' ) . '</div>';
		}

		return $output;

	}

	/**
	 * display non publicy queryable news
	 *
	 * @param int $nb
	 *
	 * @return string
	 */
	public function get_private_news( $full = true, $nb = 2, $category = 'vendeurs' ) {

		$fep     = new Fep_Message();
		$user_id = get_current_user_id();

		$args       = array(
			'post_type'      => 'private_news',
			'post_status'    => 'publish',
			'posts_per_page' => $nb,
			'tax_query' => array(
				array(
					'taxonomy' => 'news_category',
					'field'    => 'slug',
					'terms'    => $category,
				),
			),
		);
		$item_class = ( $full ) ? 'pure-u-1' : 'pure-u-1-2';


		// The Query
		$the_query = new WP_Query( $args );

		// The Loop
		if ( $the_query->have_posts() ) {
			$output = '<div class="wcvendors-pro-dashboard-wrapper news-page">';
			$output .= '<h3 class="title-bordered">Nos dernières news</h3>';
			$output .= '<div class="pure-g">';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$content = ( $full ) ? get_the_content() : get_the_excerpt();

				$output .= '<div class="' . $item_class . '">';
				$output .= '<div class="news-wrapper">';
				$output .= '<h4>' . get_the_title() . '</h4>';
				$output .= $content;
				$output .= '</div>';
				$output .= '</div>';
			}
			$output .= '</div>';
			if ( $full == false ) {
				$output .= '<a href="' . get_bloginfo( 'url' ) . '/' . VENDOR_CUSTOM_NEWS . '" class="btn btn-reg push-right">Voir toutes nos news</a>';
			}

			$output .= '</div>';


			/* Restore original Post Data */
			wp_reset_postdata();
		} else {
			// no posts found
			$output = '';
		}

		return $output;

	}


}