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

}