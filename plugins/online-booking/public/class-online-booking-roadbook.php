<?php
/**
 * create and manage a roadbook
 * allow to create, edit with rights managments
 */
class online_booking_roadbook{


	/**
	 * @param array $args
	 * $args = array(
	'trip_id'        => $session_id_trip,
	'user_ID'        => $userID,
	'booking_ID'     => $trip_name,
	'booking_date'   => $date,
	'booking_object' => $bookink_obj,
	'validation'     => 0
	);
	 */
	public function create_roadbook(array $args){

		$user = new online_booking_user();
		$bookink_obj = (isset($args['booking_object'])) ? $args['booking_object'] : false ;
		$bookink_trip_id = $user->generateTransId() ;
		$bookink_name = (isset($args['booking_ID'])) ? $args['booking_ID'] : '' ;
		$bookink_user_id = (isset($args['user_ID'])) ? intval($args['user_ID']) : get_current_user_id();


		// Create post object
		$my_post = array(
			'post_type'=>'private_roadbook',
			'post_title'    => wp_strip_all_tags( $bookink_name ),
			'post_status'   => 'publish',
			'post_author'   => $bookink_user_id,
			'post_name'          => $bookink_trip_id
		);

		// Insert the post into the database
		$post_id = wp_insert_post($my_post);

		$args = array(
			'booking_obj'   => $bookink_obj,
			'post_id'       => $post_id,
			'uuid'          =>  $bookink_trip_id
		);
		$this->update_roadbook_meta($args);
	}

	/**
	 * @param $args
	 */
	public function update_roadbook_meta($args){

		$bookink_obj = (isset($args['booking_obj'])) ? $args['booking_obj'] : false;
		$post_id = (isset($args['post_id'])) ? $args['post_id'] : false;
		$uuid = (isset($args['uuid'])) ? $args['uuid'] : false;
		$status = (isset($args['status'])) ? $args['status'] : 0;
		$manager = (isset($args['manager'])) ? $args['manager'] : 1;


		$participants = $this->get_user_trip_info($bookink_obj,'participants');
		$lieu = $this->get_user_trip_info($bookink_obj,'lieu');
		$theme = $this->get_user_trip_info($bookink_obj,'theme');
		$budget_min = $this->get_user_trip_info($bookink_obj,'budgetPerMin');
		$budget_max = $this->get_user_trip_info($bookink_obj,'budgetPerMax');

		$days = $this->get_booking_days($bookink_obj);

		$roadbook_array = array();
		//iterate through days
		foreach ($days as $key => $day) {
			//retrieve activities for the day
			$activities = $this->get_booking_actitivies_by_day($bookink_obj, $day);
			//format DATE FOR ACF INSERT
			$date = DateTime::createFromFormat('d/m/Y', $day);
			$date = $date->format('Ymd');

			$roadbook_array[] = array(
				'daytime' => $date,
				'products'  => $activities
			);
		}

		//Create trip meta
		update_field('trip_id', $uuid, $post_id);
		update_field('status', $status, $post_id);
		update_field('manager', $manager, $post_id);
		update_field('day', $roadbook_array, $post_id);
		update_field('participants', $participants, $post_id);
		update_field('lieu', $lieu, $post_id);
		update_field('theme', $theme, $post_id);
		update_field('budget_min', $budget_min, $post_id);
		update_field('budget_max', $budget_max, $post_id);
	}

	/**
	 * @param int $post_id
	 *
	 * @return array
	 */
	public function get_roadbook_meta($post_id = 0){
		$data = array();

		$data['trip_id'] = (get_field('trip_id',$post_id)) ? intval(get_field('trip_id',$post_id)) : 1;
		$data['participants'] = (get_field('participants',$post_id)) ? intval(get_field('participants',$post_id)) : 1;

		$data['lieu'] = (get_field('lieu',$post_id)) ? get_field('lieu',$post_id) : 1;
		$data['theme'] = (get_field('theme',$post_id)) ? get_field('theme',$post_id) : 1;
		//DAYS FIELDS
		$days_field = get_field('day',$post_id);
		$days_count = count($days_field);
		$first_day = (isset($days_field[0]))?$days_field[0]['daytime' ] : null; // get the sub field value
		$last_day_count = $days_count - 1;
		$last_day = (isset($days_field[$last_day_count])) ? $days_field[$last_day_count]['daytime' ] : null; // get the sub field value
		if($first_day == $last_day){
			$data['dates'] = 'Le '.$first_day;
		} else {
			$data['dates'] = 'Du '.$first_day.' au '.$last_day;
		}
		$data['first_day'] = $first_day;
		$data['last_day'] = $last_day;
		$data['days_count'] = $days_count;

		//BUDGET
		$budgetpermin = (get_field('budget_min',$post_id)) ? intval(get_field('budget_min',$post_id)) : 1;
		$budgetpermax = (get_field('budget_max',$post_id)) ? intval(get_field('budget_max',$post_id)) : 1;
		$data['budgetpermin'] = intval($budgetpermin);
		$data['budgetpermax'] = intval($budgetpermax);
		$data['globalBudgetMax'] = intval($budgetpermax*$days_count);
		$data['globalBudgetMin'] = intval($budgetpermin*$days_count);



		$manager_id = (get_field('manager',$post_id)) ? intval(get_field('manager',$post_id)) : 1;
		$manager_info = get_userdata($manager_id); //user_email
		$data['manager_id'] = $manager_id;
		$data['manager_email'] = (isset($manager_info->user_email))? $manager_info->user_email : '';
		$data['manager_phone'] = get_user_meta($manager_id,'billing_phone',true);
		$data['manager_name'] = get_user_meta($manager_id,'display_name',true);
		
		return $data;
		
	}

	/**
	 * @param array $args
	 */
	public function update_roadbook(array $args){

		$bookink_obj = (isset($args['booking_object'])) ? $args['booking_object'] : false ;
		$bookink_id = (isset($args['trip_id'])) ? intval($args['trip_id']) : 0 ;
		$bookink_name = (isset($args['booking_ID'])) ? $args['booking_ID'] : '' ;
		$bookink_user_id = (isset($args['user_ID'])) ? intval($args['user_ID']) : get_current_user_id();
		$participants = $this->get_user_trip_info($bookink_obj,'participants');
		$lieu = $this->get_user_trip_info($bookink_obj,'lieu');
		$theme = $this->get_user_trip_info($bookink_obj,'theme');
		$budget_min = $this->get_user_trip_info($bookink_obj,'budgetPerMin');
		$budget_max = $this->get_user_trip_info($bookink_obj,'budgetPerMax');
		$post_id = $this->is_trip($bookink_id);

		$my_post = array(
			'ID'            => $post_id,
			'post_type'     => 'private_roadbook',
			'post_title'    => wp_strip_all_tags( $bookink_name ),
			'post_status'   => 'publish'
		);


		wp_update_post( $my_post );
	}

	/**
	 * delete_roadbook by post ID or meta value trip_id
	 * @param $args
	 */
	public function delete_roadbook($args){
		$post_id = (isset($args['ID'])) ? intval($args['ID']) : false;
		$post_uuid = (isset($args['trip_id'])) ? intval($args['trip_id']) : false;
		if($post_id){
			wp_delete_post($post_id);
			return true;
		} elseif ($post_uuid){
			$post_id = $this->is_trip($post_uuid);
			wp_delete_post($post_id);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Build the full json object
	 * @param $args
	 *
	 * @return string
	 */
	public function get_roadbook_js($args){
		$ux = new online_booking_ux();
		$output = '';
		$post_id = (isset($args['ID'])) ? intval($args['ID']) : false;
		$post_uuid = (isset($args['trip_id'])) ? intval($args['trip_id']) : false;
		$var_name = $post_id;
		if(!$post_id){
			$post_id = $this->is_trip($post_uuid);
			$var_name = $post_uuid;
		}


		$args = array(
			'post_type' => 'private_roadbook',
			'p'         => $post_id,
			'posts_per_page' => 1
			//'post__in'  => $post_id //multiple post
		);
		$the_query = new WP_Query( $args );
		$output .= '<script>';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			global $post;

			$participants = (get_field('participants',$post->ID)) ? intval(get_field('participants',$post->ID)) : 1;
			$budgetpermin = (get_field('budget_min',$post->ID)) ? intval(get_field('budget_min',$post->ID)) : 1;
			$budgetpermax = (get_field('budget_max',$post->ID)) ? intval(get_field('budget_max',$post->ID)) : 1;
			$lieu = (get_field('lieu',$post->ID)) ? get_field('lieu',$post->ID) : 1;
			$theme = (get_field('theme',$post->ID)) ? get_field('theme',$post->ID) : 1;
			$days_field = get_field('day',$post->ID);
			$days_count = count($days_field);
			$first_day = (isset($days_field[0]))?$days_field[0]['daytime' ] : null; // get the sub field value
			$last_day_count = $days_count - 1;
			$last_day = (isset($days_field[$last_day_count])) ? $days_field[$last_day_count]['daytime' ] : null; // get the sub field value
			$globalBudgetMax = intval($budgetpermax*$days_count);
			$globalBudgetMin = intval($budgetpermin*$days_count);

			$days_array = array();
			if( have_rows('day') ){
				while( have_rows('day') ): the_row();
					$day = get_sub_field('daytime');
					$products = get_sub_field('products');
					$products_array = array();
					if( have_rows('products') ){
						while( have_rows('products') ): the_row();
							$id = (get_sub_field('id')) ? get_sub_field('id') : 0;
							$time = get_sub_field('time');
							$uuid = intval(get_sub_field('uuid'));
							$price = intval(get_sub_field('price'));
							$type_icon = $ux->get_reservation_type($id,true);

							$tmp = array(
								'id'    => $id,
								'uuid'  => $uuid,
								'price' => $price,
								'time'  => $time,
								'name'  => get_the_title($id),
								'type'  => $type_icon,
								'variation' => ''
							);
							$products_array[$id] = $tmp;
							//array_push($products_array, $tmp);

						endwhile;
					}

					$tmp_day = array(
						$day => $products_array
					);
					$days_array[$day] = $products_array;
					//array_push($days_array, $tmp_day);
				endwhile;
			}

			$output_array = array(
				'arrival' => $first_day,
				'departure'     =>  $last_day,
				'currentDay'    =>  $first_day,
				'days'          =>  $days_count,
				'budgetPerMax'  => $budgetpermin,
				'budgetPerMin'  =>  $budgetpermax,
				'globalBudgetMax'   =>  $globalBudgetMax,
				'globalBudgetMin'   =>  $globalBudgetMin,
				'lieu'              =>  $lieu,
				'theme'             =>  $theme,
				'currentBudget' =>  '',
				'participants'  =>  $participants,
				'sejour'        =>  '',
				'name'          => get_the_title($post_id),
				'tripObject'    =>  $days_array
			);

			$output .= 'var trip'.$var_name.' = '.json_encode($output_array);

		}
		$output .= '</script>';
		/* Restore original Post Data */
		wp_reset_postdata();

		return $output;


	}

	/**
	 * is_trip
	 * check if trip exist
	 * provide a way to get post ID from uuid
	 * @param $trip_id
	 */
	public function is_trip($trip_id){
		$args = array(
			'post_type' => 'private_roadbook',
			'meta_query' => array(
				array(
					'key'     => 'trip_id',
					'value'   => intval($trip_id),
					'compare' => '=',
				),
			)
		);
		$trip_exist = new WP_Query( $args );
		if ( $trip_exist->have_posts() ) {
			global $post;
			return $post->ID;
		} else {
			// no posts found
			return false;
		}
	}

	/**
	 * count user published trip (avoid archive)
	 *
	 * @param $user_id
	 *
	 * @return bool
	 */
	public function get_user_trips_count($user_id){
		$user = (!$user_id) ? get_current_user_id() : intval($user_id);
		$args = array(
			'post_type' => 'private_roadbook',
			'author' => $user,
			'post_status' => 'publish'
		);
		$trip_count = new WP_Query( $args );
		if ( $trip_count->have_posts() ) {
			return $trip_count->found_posts;
		} else {
			// no posts found
			return 0;
		}
	}


	/**
	 * get basic user trip infos
	 * @param $bookink_obj
	 *
	 * @return string
	 */
	public function get_user_trip_info($bookink_obj,$field){

		$booking_obj = json_decode($bookink_obj);

		$participants = (isset($booking_obj->participants)) ? intval($booking_obj->participants) : 1;
		$budgetpermin = (isset($booking_obj->budgetPerMin)) ? intval($booking_obj->budgetPerMin) : 1;
		$budgetpermax = (isset($booking_obj->budgetPerMax)) ? intval($booking_obj->budgetPerMax) : 1;
		$lieu = (isset($booking_obj->lieu)) ? $booking_obj->lieu : 1;
		$theme = (isset($booking_obj->budgetPerMax)) ? $booking_obj->budgetPerMax : 1;



		switch ($field){
			case 'participants' :
				return $participants;
				break;
			case 'budgetPerMin' :
				return $budgetpermin;
				break;
			case 'budgetPerMax':
				return $budgetpermax;
				break;
			case 'lieu':
				return $lieu;
				break;
			case 'theme':
				return $theme;
				break;
			default:
				return $participants;
		}

	}
	/**
	 *
	 */
	public function update_individual_activity(){
		//ACF UPDATE A SPECIFIC SUBFIELD
		//update_sub_field( array('day', 1, 'daytime'), $day );
	}
	/**
	 * get_individual_activities
	 *
	 * @param $bookink_obj
	 *
	 * @return array
	 */
	public  function get_individual_activities($bookink_obj){
		$activites = array();
		$booking_obj = json_decode($bookink_obj);
		$booking_dates = $booking_obj->tripObject;

		foreach ($booking_dates as $key => $booking_date){
			$date_tmp = $key;
			foreach ($booking_date as $post_id => $booking_trip){

				$post_tmp = get_post($post_id);
				$_product = wc_get_product( $post_id );
				$post_author = get_post_field( 'post_author', $post_id );
				$uuid = (isset($booking_trip->uuid)) ? intval($booking_trip->uuid) : 0;

				$activites[] = array(
					'id'        => $post_id,
					'uuid'      => $uuid,
					'price'     => $_product->get_price(),
					'date'      => $date_tmp,
					'vendor'    => $post_author,
					'sold_individually'    => 0
				);
			}
		}
		return $activites;
	}

	/**
	 * sort by time
	 * @param $a
	 * @param $b
	 *
	 * @return false|int
	 */
	public function sortFunction( $a, $b ) {
		return strtotime($a) - strtotime($b);
	}

	/**
	 * get_booking_days
	 * extract days
	 * return array
	 */
	public function get_booking_days($bookink_obj){
		$days = array();
		$booking_obj = json_decode($bookink_obj);
		$booking_dates = (isset($booking_obj->tripObject)) ? $booking_obj->tripObject : false;

		if($booking_dates){
			foreach ($booking_dates as $key => $booking_date){
				array_push($days, $key);
			}
		}

		//sort by day
		//usort($data, "sortFunction");
		return $days;
	}

	public function get_booking_actitivies_by_day($bookink_obj,$day){
		$activity_day = array();
		$booking_obj = json_decode($bookink_obj);
		$booking_dates = (isset($booking_obj->tripObject)) ? $booking_obj->tripObject : false;

		if($booking_dates){
			foreach ($booking_dates as $key => $booking_activities){
				if($key == $day){
					foreach ($booking_activities as $id => $booking_activity){
						$price = (isset($booking_activity->price)) ? intval($booking_activity->price): 0;
						$uuid = (isset($booking_activity->uuid))? $booking_activity->uuid: 0;
						$activity_day[] = array(
							'id'    => 	$id,
							'price' => $price,
							'uuid' => $uuid,
							'time'  => '12:00',
							'validation'  => 0
						);
					}

				}
			}
		}

		//sort by day
		//usort($data, "sortFunction");
		//var_dump($activity_day);
		return $activity_day;
	}

	/**
	 * extract IDS
	 * @param $activites
	 * return array
	 */
	public function get_individual_activities_id($activities){
		$activites_id = array();
		foreach ($activities as $activity){
			array_push($activites_id,$activity['uuid']);
		}
		return $activites_id;
	}



}