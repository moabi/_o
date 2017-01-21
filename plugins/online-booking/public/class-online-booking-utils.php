<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 06/09/16
 * Time: 20:40
 */
class online_booking_utils{

	/**
	 * @param $location
	 *
	 * @return string
	 */
	public function get_circle_gmap($location){


		$lat = (isset($location['lat'])) ? $location['lat'] : false;
		$lng = (isset($location['lng'])) ? $location['lng'] : false;
		$loc = (isset($location['address'])) ? $location['address'] : 'undefined-n';
		if($lat){
			$map = '<div id="map" data-loc="'.$loc.'" data-lat="'.$lat.'" data-lng="'.$lng.'" class="single-map" 
			style="width:100%;display: block;min-height: 350px;margin: 1em 0"></div>';
		} else {
			$map = false;
		}


		return $map;
	}

	/**
	 * Get an attachment ID given a URL.
	 * https://gist.github.com/wpscholar/3b00af01863c9dc562e5#file-get-attachment-id-php
	 * @param string $url
	 *
	 * @return int Attachment ID on success, 0 on failure
	 */
	public function get_attachment_id( $url ) {
		$attachment_id = 0;
		$dir = wp_upload_dir();
		if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
			$file = basename( $url );
			$query_args = array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'fields'      => 'ids',
				'meta_query'  => array(
					array(
						'value'   => $file,
						'compare' => 'LIKE',
						'key'     => '_wp_attachment_metadata',
					),
				)
			);
			$query = new WP_Query( $query_args );
			if ( $query->have_posts() ) {
				foreach ( $query->posts as $post_id ) {
					$meta = wp_get_attachment_metadata( $post_id );
					$original_file       = basename( $meta['file'] );
					$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
					if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
						$attachment_id = $post_id;
						break;
					}
				}
			}
		}
		return $attachment_id;
	}

	/**
	 * Deprecated ?
	 */
	public function the_save_btn(){

		$btn_Name = __('Enregistrer','onlyoo');
		$btn_attr = '';
		$href = '#';
		$btn_class = '';

		if(is_user_logged_in()){
			//event is known
			if(isset($_COOKIE[BOOKING_COOKIE]) ){
				$bookink_json = stripslashes( $_COOKIE[BOOKING_COOKIE] );
				$data = json_decode($bookink_json, true);
				$eventid = (isset($data['eventid'])) ? intval($data['eventid']) : 0;

					if($eventid != 0){
						$btn_Name = __('Mettre à jour','onlyoo');
					} else {
						$btn_Name = __('Enregistrer votre évènement','onlyoo');
					}

				$btn_attr = 'onclick="saveTrip('.$eventid.')"';
				$href = 'javascript:void(0)';


			} else{
				//event is unknown/not saved
				$btn_attr = 'onclick="saveTrip(0)"';
				$btn_Name = __('Enregistrer','onlyoo');
				$href = 'javascript:void(0)';
			}

		} elseif(!is_user_logged_in()) {
			$btn_Name = __('Se connecter <br />pour sauvegarder','onlyoo');
			$href = get_bloginfo('url').'/'.MY_ACCOUNT;
			$btn_class = 'two-lines';
		}


		$output = '<div id="savetrip" >';
		$output .= '<a id="ob-btn-re" href="'.$href.'" '.$btn_attr.' class="btn btn-reg '.$btn_class.'">';
		$output .= $btn_Name;
		$output .= '<!--<i class="fa fa-floppy-o"></i>--></a></div>';

		echo $output;
	}


	/**
	 * Registers the filter to handle a public preview.
	 *
	 * Filter will be set if it's the main query, a preview, a singular page
	 * and the query var `_ppp` exists.
	 *
	 * @since 2.0.0
	 *
	 * @param object $query The WP_Query object.
	 * @return object The WP_Query object, unchanged.
	 */
	public static function show_public_preview( $query ) {
		//should verify nonce also
		if ( $query->is_main_query() && $query->is_preview() && $query->is_singular() ) {
			add_filter( 'posts_results', array( __CLASS__, 'set_post_to_publish' ), 10, 2 );
		}

		return $query;
	}

	/**
	 * Sets the post status of the first post to publish, so we don't have to do anything
	 * *too* hacky to get it to load the preview.
	 *
	 * @since 2.0.0
	 *
	 * @param  array $posts The post to preview.
	 * @return mixed The post that is being previewed.
	 */
	public static function set_post_to_publish( $posts ) {
		// Remove the filter again, otherwise it will be applied to other queries too.
		remove_filter( 'posts_results', array( __CLASS__, 'set_post_to_publish' ), 10 );

		if ( empty( $posts ) ) {
			return false;
		}

		$post = (isset($posts[0])) ? $posts[0] : false;
		$post_id = ($post) ? $post->ID : false;
		$post_status = ($post)? $post->status : false;

		// If the post has gone live, redirect to it's proper permalink.
		if($post_status == 'publish'){
			wp_redirect( get_permalink( $post_id ), 301 );
		}


			// Set post status to publish so that it's visible.
			$posts[0]->post_status = 'publish';

			// Disable comments and pings for this post.
			add_filter( 'comments_open', '__return_false' );
			add_filter( 'pings_open', '__return_false' );
			add_filter( 'wp_link_pages_link', array( __CLASS__, 'filter_wp_link_pages_link' ), 10, 2 );


		return $posts;
	}



}