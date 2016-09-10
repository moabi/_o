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
			$output .= '<ul id="typeterms" class="sf-menu">';
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

		//$images = get_field('gallerie');
		$slider = '';
		if ( $attachment_ids || $post_thumbnail_id ):
			$slider .= '<ul class="slickReservation img-gallery product-gallery">';
			if ( $post_thumbnail_url ) {
				$slider .= '<li style="background: url(' . $post_thumbnail_url[0] . ');">';
			}
			if ( $attachment_ids ) {
				foreach ( $attachment_ids as $attachment_id ):
					$image_link = wp_get_attachment_url( $attachment_id );
					$slider .= '<li style="background: url(' . $image_link . ');">';
					$slider .= '</li>';
				endforeach;
			}
			$slider .= '</ul>';
		endif;

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
	 * @return string
	 */
	public function get_activity_time() {
		$days   = (get_field( 'duree-j' )) ? get_field( 'duree-j' ) : '';
		$hours   = (get_field( 'duree' )) ? get_field( 'duree' ) : '';
		$minutes   = (get_field( 'duree-m' )) ? get_field( 'duree-m' ) : '';

		if($days > 1){
			$days_label = 'jours';
		} elseif ($days == 1){
			$days_label = 'jour';
		} else {
			$days_label = '';
		}

		if($hours > 1){
			$hours_label = 'heures';
		} elseif ($hours == 1){
			$hours_label = 'heure';
		} else {
			$hours_label = '';
		}

		if($minutes > 1){
			$minutes_label = 'minutes';
		} elseif ($minutes == 1){
			$minutes_label = 'minute';
		} else {
			$minutes_label = '';
		}

		$duree = $days.' '.$days_label.' '.$hours.' '.$hours_label.' '.$minutes.' '.$minutes_label;

		return $duree;
	}


	/**
	 * get_reservation_type()
	 * get the location for an activity
	 *
	 * @param $id integer post->ID
	 * @param bool|false $class if true, output only class
	 *
	 * @return string
	 */
	public function get_reservation_type( $id, $class = false ) {
		$term_reservation_type = wp_get_post_terms( $id, 'reservation_type' );
		$data                  = '';

		if ( ! empty( $term_reservation_type ) && $id ):
			$i = 0;
			foreach ( $term_reservation_type as $key => $value ) {
//get only top taxonomy
				if ( intval( $value->parent ) == 0 && $i == 0 ):
					$i ++;
//get fa icon linked to taxonomy in the custom field
					$id_term = 'reservation_type_' . $value->term_id;
					$icon    = get_field( 'fa_icon', $id_term );
					if ( $class == false ) {
						$data .= '<i class="fa ' . $icon . '"></i>' . $value->name;
					} else {
						$data .= $icon;
					}

				endif;


			}
		endif;

		return $data;
	}

	/**
	 * get_place
	 * get the location for an activity - should be mixed with get_reservation_type
	 *
	 * @param $id
	 * @param bool|true $html
	 *
	 * @return string
	 */
	public function get_place( $id, $html = true ) {
		$term_lieu = wp_get_post_terms( $id, 'lieu' );
		$data      = '';
		if ( $html == true && ! empty( $term_lieu ) ) {
			if ( $id ):
				$data .= '<i class="fa fa-map-marker"></i>';
				$i = 0;
				foreach ( $term_lieu as $key => $value ) {
					$term_link = get_term_link( $value );
					if ( $i == 0 ) {
						$data .= '<span>Lieu : <a href="' . esc_url( $term_link ) . '">' . $value->name . '</a></span>';
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
			$data .= '<span class="fs1" aria-hidden="true" data-icon=""></span>';
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
		$object_name = 'Uniquesejour' . $post->ID;
		$data        = '<i title="Supprimer cette activité" onclick="deleteSejourActivity(' . $day_number . ',' . $id . ',' . $price . ',' . $object_name . ');" class="fa fa-trash-o"></i>';

		return $data;

	}

	/**
	 * get_sejour
	 *
	 * @return string
	 */
	public function get_sejour() {

// check for rows (parent repeater)
		if ( have_rows( 'votre_sejour' ) ):
			echo '<div id="event-trip-planning">';
			$i = 1;
			while ( have_rows( 'votre_sejour' ) ): the_row();

				echo '<div class="event-day day-content">';
				echo '<div class="etp-day">';
				echo '<div class="day-title">';
				echo '<i class="fa fa-calendar"></i><br />';
				_e( 'Journée', 'online-bookine' );

				echo ' ' . $i . '</div></div>';
				// check for rows (sub repeater)

				if ( have_rows( 'activites' ) ):
					echo '<div class="etp-days">';


					while ( have_rows( 'activites' ) ): the_row();


						// display each item as a list - with a class of completed ( if completed )

						$postActivity = get_sub_field( 'activite' );
						foreach ( $postActivity as $data ) {

							$post_status = get_post_status( $data->ID );

							if ( $post_status == "publish" ):


								echo '<div data-id="' . $data->ID . '" class="pure-u-1 single-activity-row">';
								echo '<span class="round"></span><span class="trait s-' . $i . '"></span>';

								echo '<div class="pure-g"><div class="pure-u-1 head">';
								echo '<div class="tags">' . $this->get_reservation_type( $data->ID ) . '</div>';
								echo $this->get_trash_btn( $i - 1, $data->ID );
								echo '</div></div>';

								echo '<div class="pure-g">';
								echo '<div class="pure-u-1 pure-u-md-7-24">';
								echo '<a href="' . get_permalink( $data->ID ) . '">';
								echo get_the_post_thumbnail( $data->ID, array( 250, 180 ) );
								echo '</a>';
								echo '</div>';

								echo '<div class="pure-u-1 pure-u-md-17-24">';
								echo '<h3>';
								echo '<a href="' . get_permalink( $data->ID ) . '"><i class="fa fa-search"></i>';
								echo $data->post_title;
								echo '</a></h3>';
								echo get_field( 'la_prestation_comprend', $data->ID );


								echo '</div>';
								echo '</div>';

								echo '</div>';
							endif;
						}

					endwhile;
					echo '</div>';
				endif; //if( get_sub_field('items') ):
				echo '</div>';
				$i ++;
			endwhile; // while( has_sub_field('to-do_lists') ):
			echo '</div>';
		endif; // if( get_field('to-do_lists') ):


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
		$logout = $items['customer-logout'];
		unset( $items['customer-logout'] );

// vendors only
		if ( current_user_can( 'vendor' ) || current_user_can( 'pending_vendor' ) ) {
// Insert your custom endpoint.
//$items['mes-prestations']         = __( 'Mes prestations', 'online-booking' );
//$items['proposer-votre-activite'] = __( 'Ajouter activité', 'online-booking' );

//remove clients links
			unset( $items['downloads'] );
			unset( $items['orders'] );
			$items['dashboard/product'] = __( 'Prestations', 'online-booking' );
			$items['product']           = __( 'Mes prestations', 'online-booking' );
		}

//particulier, entreprise ONLY
		if ( current_user_can( 'entreprise' ) || current_user_can( 'particulier' ) || current_user_can( 'administrator' ) ) {
			$items['mes-devis'] = __( 'Mes devis', 'online-booking' );
//$items['test']         = __( 'Mes devis', 'online-booking' );
		}

// Insert back the logout item.
		$items['customer-logout'] = $logout;

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
	}


	public function tsm_acf_profile_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
// Get user by id or email
		if ( is_numeric( $id_or_email ) ) {
			$id   = (int) $id_or_email;
			$user = get_user_by( 'id', $id );
		} elseif ( is_object( $id_or_email ) ) {
			if ( ! empty( $id_or_email->user_id ) ) {
				$id   = (int) $id_or_email->user_id;
				$user = get_user_by( 'id', $id );
			}
		} else {
			$user = get_user_by( 'email', $id_or_email );
		}
		if ( ! $user ) {
			return $avatar;
		}
// Get the user id
		$user_id = $user->ID;
// Get the file id
		$image_id = get_user_meta( $user_id, 'tsm_local_avatar', true ); // CHANGE TO YOUR FIELD NAME
// Bail if we don't have a local avatar
		if ( ! $image_id ) {
			return $avatar;
		}
// Get the file size
		$image_url = wp_get_attachment_image_src( $image_id, 'thumbnail' ); // Set image size by name
// Get the file url
		$avatar_url = $image_url[0];
// Get the img markup
		$avatar = '<img alt="' . $alt . '" src="' . $avatar_url . '" class="avatar avatar-' . $size . '" height="' . $size . '" width="' . $size . '"/>';

// Return our new avatar
		return $avatar;
	}


}