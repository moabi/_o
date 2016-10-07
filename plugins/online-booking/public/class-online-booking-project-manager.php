<?php
class OnlineBookingProjectManager {


	public  $pm_id;

	/**
	 * OnlineBookingProjectManager constructor.
	 *
	 * @param $user_id
	 */
	public function __construct(  ) {
		$this->pm_id = get_current_user_id();
	}


	/**
	 * get the templates parts according to URI
	 * @param $content
	 *
	 * @return mixed
	 */
	public function get_pm_templates($content){
		global $post,$wp_query;
		// assuming you have created a page/post entitled 'debug'
		$uri = get_page_uri($post->ID);
		$page_path = get_page_by_path('prestations');
		$query_vars = $wp_query->query;
		$is_capable = (current_user_can('project_manager') || current_user_can('administrator')) ? true : false;

		if ($uri == 'dashboard-manager/prestations' && $is_capable) {
			include get_wp_attachment_filter_plugin_dir().'public/partials/dashboard-manager-prestations.php';
		} elseif ($uri == 'dashboard-manager/prestataires' && $is_capable) {
			include get_wp_attachment_filter_plugin_dir().'public/partials/dashboard-manager-prestataires.php';
		} elseif ($uri == 'dashboard-manager/reservations' && $is_capable) {
			include get_wp_attachment_filter_plugin_dir().'public/partials/dashboard-manager-reservations.php';
		}

		// otherwise returns the database content
		return $content;
	}

	/**
	 * retrieve vendors to PM id
	 */
	public function get_vendors_affiliated(){
		$output = '';
		$vendors = $this->get_vendors_affiliated_id();

		//var_dump($vendors);
		$output .= '<div class="pure-g">';
		$output .= '<div class="pure-u-1-4"><i class="fa fa-user" aria-hidden="true"></i> Nom du prestataire</div>';
		$output .= '<div class="pure-u-1-4"><i class="fa fa-calendar" aria-hidden="true"></i> Enregistré le</div>';
		$output .= '<div class="pure-u-1-4"><i class="fa fa-user" aria-hidden="true"></i> Etat</div>';
		$output .= '<div class="pure-u-1-4"><i class="fa fa-internet-explorer" aria-hidden="true"></i> Site internet</div>';
		$output .= '</div>';
		$output .='<div class="pure-g">';
		foreach ($vendors as $vendor_id){
			$vendor = get_user_by('ID',$vendor_id);
			$id = $vendor->ID;
			$data = $vendor->data;
			$first_name = get_the_author_meta('first_name',$id);
			$last_name = get_the_author_meta('last_name',$id);
			//$registered = date('d/m/Y',$data->user_registered);
			$website = (!empty($data->user_url)) ? $data->user_url : '-';

			$display_name = (!empty($first_name.$last_name)) ? $first_name : $data->display_name;
			$output .= '<div class="pure-u-1-4"><a href="#" title="Infos utilisateurs">'.$display_name.'</a></div>';
			$output .= '<div class="pure-u-1-4">'.$data->user_registered.'</div>';
			$output .= '<div class="pure-u-1-4"><i class="fa fa-check" aria-hidden="true"></i></div>';
			$output .= '<div class="pure-u-1-4">'.$website.'</div>';
		}
		$output .='</div>';

		return $output;
	}

	/**
	 * get_vendors_affiliated_id
	 * TODO: query by meta key 'manager'
	 * @return array
	 */
	public function get_vendors_affiliated_id(){
		$current_user = get_current_user_id();
		$vendors = get_users(array(
			'role'  => 'vendor'
		));
		$ids = array();
		foreach ($vendors as $vendor) {
			$vendor_id_acf = 'user_'.$vendor->ID;
			$pm_id = get_field('manager',$vendor_id_acf);
			$final_pm_id = (isset($pm_id['ID'])) ? $pm_id['ID'] : null;
			if($final_pm_id == $current_user){
				$ids[] = $vendor->ID;
			}
		}

		return $ids;
	}

	/**
	 * get_activities
	 * retrieve all activites according to PM id
	 */
	public function get_activities(){
		global $wp_query,$wpdb;
		wp_reset_postdata();
		wp_reset_query();
		$output = '';
		$args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => 20,
			'author__in'    => $this->get_vendors_affiliated_id(),
			'orderby'       => 'author'
		);

		$manager_products = new WP_Query($args);


		// The Loop
		if ($manager_products->have_posts()) {
			$count_post = 0;
			$output .= '<div class="pure-g">';
			$output .= '<div class="pure-u-1-4"><i class="fa fa-user" aria-hidden="true"></i> Prestation</div>';
			$output .= '<div class="pure-u-1-4"><i class="fa fa-calendar" aria-hidden="true"></i> Prestataire</div>';
			$output .= '<div class="pure-u-1-4"><i class="fa fa-user" aria-hidden="true"></i> Date</div>';
			$output .= '<div class="pure-u-1-4"><i class="fa fa-internet-explorer" aria-hidden="true"></i> Etat</div>';
			while ( $manager_products->have_posts() ) {
				$manager_products->the_post();
				$first_name = get_the_author_meta('first_name');
				$last_name = get_the_author_meta('last_name');
				$display_name = (!empty($first_name.$last_name)) ? $first_name : get_the_author();

				$output .= '<div class="pure-u-1-4"><a href="#" title="Infos utilisateurs">'.get_the_title().'</a></div>';
				$output .= '<div class="pure-u-1-4">'.$display_name.'</div>';
				$output .= '<div class="pure-u-1-4"><i class="fa fa-check" aria-hidden="true"></i></div>';
				$output .= '<div class="pure-u-1-4"></div>';

			}
			$output .='</div>';

		}
		return $output;
	}

	/**
	 * Sejours
	 */
	public function get_activites_pack(){
		$output = '';

		return $output;
	}

	/**
	 * get_user_booking
	 * TODO: put the right $status where client has done validation (step 1)
	 *
	 * @param $validation integer status of the global trip
	 * @param $status integer||array status of each activity
	 * @param $pm integer project manager ID, view data as PM
	 * 0 : trip is not visible, no user validation
	 * 1 : user has validated and ask for validation (can't edit anymore)
	 * 2 : trip is refused
	 * 3 : trip is validated by vendors
	 * 4 : trip is validated by project manager && vendors
	 * 5 : trip is validated by project manager && vendors && client
	 * 6 : trip has not been done by vendor (problems...)
	 * 7 : trip is done
	 * 8 : trip is archived
	 *
	 * @return string
	 */
	public function get_booking_by_user_id( $validation, $status = 0,$pm = 0 ) {
		$vendor = new online_booking_vendor();
		global $wpdb;
		$user_id = ($pm == 0) ? get_current_user_id() : $pm;

		$status = esc_sql( $status );
		//If its an array, convert to string
		if( is_array( $status ) ){
			$status = implode( ', ', $status ); //e.g. "publish, draft"
		}

		$table = $wpdb->prefix . 'online_booking_orders';
		$sql   = $wpdb->prepare( " 
						SELECT *
						FROM $table a	
						WHERE a.vendor = %d
						AND a.status IN( {$status} )  
						ORDER BY a.trip_id DESC
						", $user_id );

		$results = $wpdb->get_results( $sql );

		//get unique trip ID to order results
		if ( ! empty( $results ) ) {
			$trip_ids = array();
			foreach ( $results as $result ) {
				array_push( $trip_ids, $result->trip_id );
			}
			$unique_trip_ids = array_unique( $trip_ids );

			//var_dump( $unique_trip_ids );
		} else {
			$unique_trip_ids = array();
		}

		$output = '<div id="vendor-bookings" class="bk-listing pure-table">';
		//loop through trips to find vendors activities sold
		foreach ( $unique_trip_ids as $unique_trip_id ) {
			//var_dump($unique_trip_id);
			//get general trip infos
			$general_infos = $vendor->get_general_trip_infos( $unique_trip_id, $validation );
			//var_dump($unique_trip_id);
			//get detailed events
			$user_name    = (isset($general_infos['user_ID'])) ? $general_infos['user_ID'] : false;
			$booking_name = ( isset( $general_infos['booking_ID'] ) && ! empty( $general_infos['booking_ID']) ) ? $general_infos['booking_ID'] : 'Séjour du client';
			$booking_id = (isset($general_infos['trip_id'])) ? $general_infos['trip_id'] : false;
			//check for an existing trip...
			if($booking_id){

				//booking header
				$output .= '<div class="table-header brown-head"><div class="pure-g">';
				$output .= '<div class="pure-u-1-3">Projets en cours</div>';
				$output .= '<div class="pure-u-1-3">Client</div>';
				$output .= '<div class="pure-u-1-3">Actions</div>';
				$output .= '</div></div>';

				$output .= '<div class="event-body"><div class="pure-g">';
				$output .= '<div class="pure-u-1"><div class="pure-g">';

				$output .= '<div class="pure-u-1-3">';
				$output .= '<span class="ttrip-title js-toggle-next" data-target="target-'.$booking_id.'"><i class="fa fa-chevron-right" aria-hidden="true"></i>' . $booking_name . '</span>';
				$output .= '</div>';


				$output .= '<div class="pure-u-1-3">';
				if ( get_avatar( $user_name ) && $user_name ) {
					$output .= '<span class="ttrip-avatar">';
					$output .= get_avatar( $user_name, 48 );
					$output .= '</span>';
				}
				$output .= '<span class="ttrip-client">';
				if ( get_the_author_meta( 'first_name', $user_name ) ) {
					$output .= get_the_author_meta( 'first_name', $user_name );
					$output .= ' ' . get_the_author_meta( 'last_name', $user_name );
				} else {
					$output .= get_the_author_meta( 'nicename', $user_name );
				}
				$output .= '</span>';
				$output .= '</div>';

				$output .= '<div class="pure-u-1-3">';
				$output .= '<span class="ttrip-action"><a href="" class="btn btn-reg">VALIDER l\'évènement</a></span>';
				$output .= '</div>';

				$output .= '</div></div></div></div>';


				$output .= '<div id="target-'.$booking_id.'" class="table-body hidden">';
				//TABLE EVENTS HEADER
				$output .= '<div class="events-header brown-head"><div class="pure-g">';
				$output .= '<div class="pure-u-4-24"><i class="fa fa-flag"></i> Prestation</div>';
				$output .= '<div class="pure-u-4-24"><i class="fa fa-users"></i> Vendeur</div>';
				$output .= '<div class="pure-u-4-24"><i class="fa fa-calendar"></i> Date</div>';
				$output .= '<div class="pure-u-4-24"><i class="fa fa-euro"></i> Prix</div>';
				$output .= '<div class="pure-u-4-24"><i class="fa fa-bullseye" aria-hidden="true"></i> Statut</div>';
				$output .= '<div class="pure-u-4-24"><i class="fa fa-flag"></i> Action</div>';
				$output .= '</div></div>';

				//SUB TR - display events
				//display each event
				$i = 0;
				foreach ( $results as $result ) {

					if($result->trip_id == $booking_id && $result->vendor == $user_id){
						$i++;
						$even_class = ($i%2 == 0)? 'row-even': 'row-odd';
						$status = (isset($result->status)) ? $result->status : 0;
						$post_author_id = get_post_field( 'post_author', $result->activity_id );
						$first_name = get_the_author_meta('first_name',$post_author_id);
						$last_name = get_the_author_meta('last_name',$post_author_id);
						$display_name = (!empty($first_name.$last_name)) ? $first_name : get_the_author_meta('display_name',$post_author_id);

						$output .= '<div class="pure-u-1 '.$even_class.'"><div class="pure-g">';

						$output .= '<div class="pure-u-4-24">';
						$output .= '<span class="ttrip-title">' . get_the_title($result->activity_id).'<br />';
						$output .= '<i class="fa fa-users" aria-hidden="true"></i>'.$result->quantity . ' participants';
						$output .= '</span>';
						$output .= '</div>';

						$output .= '<div class="pure-u-4-24">';
						$output .= '<span class="ttrip-participants">'.$display_name.'</span>';
						$output .= '</div>';

						$output .= '<div class="pure-u-4-24">';
						$output .= '<span class="ttrip-date">' . $result->activity_date.'</span>';
						$output .= '</div>';

						$output .= '<div class="pure-u-4-24">';
						$output .= '<span class="ttrip-price">';
						$output .= $result->price.' <i class="fa fa-euro"></i>';
						$output .= '</span>';
						$output .= '</div>';

						$output .= '<div class="pure-u-4-24">';
						$output .= '<span class="ttrip-status">';
						$output .= $vendor->get_activity_status_wording($status);
						$output .= '</span>';
						$output .= '</div>';

						$output .= '<div class="pure-u-4-24">';
						$output .= '<a title="En validant cette réservation vous vous engagez à sa bonne réalisation le Jour J" 
class="btn btn-reg ttrip-btn" href="#" onclick="setActivityStatus(3,'.$result->activity_uuid.');"><i class="fa fa-check"></i></a>';
						$output .= '<a class="btn btn-reg ttrip-btn" href="#" onclick="setActivityStatus(2,'.$result->activity_uuid.');"><i class="fa fa-times"></i></a>';
						$output .= '</div>';

						$output .= '</div></div>';
					}


				}
				$output .= '</div>';
			}
		}
		$output .= '</div>';


		return $output;
	}

}