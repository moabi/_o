<?php
// include autoloader
require_once 'libs/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://little-dream.fr
 * @since      1.0.0
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/admin
 * @author     little-dream.fr <david@loading-data.com>
 */
class Online_Booking_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/online-booking-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/online-booking-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	/*
	 * ob_generate_pdf
	 * 
	 *
	 * @param $text string (html)
	 * @param $name string
	 * @return pdf
	*/
	public function ob_generate_pdf($text,$name){
		
		// instantiate and use the dompdf class
		$html_content = (!empty($text)) ? $text : 'Empty document - please refer to your webmaster';
		$dompdf = new Dompdf();
		$dompdf->loadHtml($text);
		$dompdf->setPaper('A4', 'landscape');
		$dompdf->render();
		$output = $dompdf->output();
		$path = __DIR__.'/pdf/';
		file_put_contents($path.$name.'.pdf', $output);
	    
		// Output the generated PDF to Browser
		//$dompdf->stream();
	
	}


	/**
	 * online_booking_menu
	 * Add option page
	 */
	public function online_booking_menu(){	
		add_menu_page( 'Online Booking', 'Online Booking', 'publish_pages', 'online-booking-orders', array( $this, 'helper' ) );
		
		add_submenu_page( 'online-booking-orders', 'Demandes','Demandes', 'publish_pages', 'online-booking-estimate', array( $this, 'estimate' ) );
		
		add_submenu_page( 'online-booking-orders', 'Emails','Emails', 'publish_pages', 'online-booking-emails', array( $this, 'emails' ) );
		
		add_submenu_page( 'online-booking-orders', 'Help','Help', 'publish_pages', 'online-booking-help', array( $this, 'ob_help' ) );
		
		add_action( 'admin_init', array($this, 'register_ob_settings') );
	}
	
	public function my_plugin_action_links( $links ) {
	  $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=online-booking-orders') ) .'">'. __('Configure','online-booking').'</a>';

	  return $links;
	}
	

	/**
	 * helper,estimate,emails,ob_help
	 * include partial admin display
	 */
	public function helper(){
		$admin_view = plugin_dir_path( __FILE__ ) . 'partials/online-booking-orders.php';
		include_once $admin_view;
	}
	public function estimate(){
		$admin_view = plugin_dir_path( __FILE__ ) . 'partials/online-booking-estimate.php';
		include_once $admin_view;
	}
	public function emails(){
		$admin_view = plugin_dir_path( __FILE__ ) . 'partials/online-booking-emails.php';
		include_once $admin_view;
	}
	public function ob_help(){
		$admin_view = plugin_dir_path( __FILE__ ) . 'partials/online-booking-help.php';
		include_once $admin_view;
	}

	/**
	 * register_ob_settings
	 * register admin wp settings
	 */
	public function register_ob_settings() {
		//register our settings
		register_setting( 'ob-settings-group', 'ob_admin_email' );
		register_setting( 'ob-settings-group', 'ob_confirmation_content' );
		register_setting( 'ob-settings-group', 'ob_annulation_content' );
		register_setting( 'ob-settings-group', 'ob_min_budget' );
		register_setting( 'ob-settings-group', 'ob_max_budget' );
		register_setting( 'ob-settings-group', 'ob_max_days' );
		
		

	}


	/**
	 * get_users_booking
	 * @param integer $validation (0- 4)
	 */
	public static function get_users_booking($validation = 0){
			global $wpdb;
			//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID	
			$sql = $wpdb->prepare(" 
						SELECT *
						FROM ".$wpdb->prefix."online_booking a	
						WHERE a.validation = %d
						",$validation); 
					
			$results = $wpdb->get_results($sql);
			//var_dump($results);
			echo '<table id="ut-onlinebook" class="wp-list-table widefat fixed striped posts">';
			echo '<thead><tr>';
			echo '<td>delete</td>';
			echo '<td>User Name</td>';
			echo '<td>Email</td>';
			echo '<td>see trip</td>';
			echo '<td>invoice date</td>';
			echo '</tr></thead>';
			foreach ( $results as $result ) 
				{
					$booking = $result->booking_object; 
					$bdate = $result->booking_date;
					$tripID = $result->ID;
					$tripName = $result->booking_ID;
					$tripDate = $result->booking_date;
					$newDate = date("d/m/y", strtotime($tripDate));
					$userID = $result->user_ID;
					$user_info = get_userdata( $userID );
					
					echo '<tr>';
					echo '<td><script>var trip'.$result->ID.' = '.$booking.'</script>';
					echo '<span class="fs1 js-delete-user-trip" aria-hidden="true" data-icon="" onclick="deleteUserTrip('.$tripID.')"></span>';
					echo '<input type="radio"" name="deleteSingleTrip" value="'.$result->ID.'">';
					echo '</td>';
					echo '<td>'.$user_info->user_login . "</td>";
					echo '<td>'.$user_info->user_email . "</td>";
					echo '<td><a title="Voir l\'event" onclick="loadTrip(trip'.$result->ID.',true)" href="#">'.$tripName.'</a></td>';
					echo '<td><span class="user-date-invoice">'.$newDate.'</span></td>';
					
					echo '<tr>';
				}
				echo '</table><br />';
				echo '<button>Effacer</button>';
				echo '<button>Valider</button>';

	}
	
	/**
     * Display the list table page
     *
     * @return Void
     */
    public function data_table_list($validation_state)
    {
        $quotationTable = new Quotation_Table($validation_state);
        $quotationTable->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
               
                <?php $quotationTable->display(); ?>
            </div>
        <?php
    }
    



}
