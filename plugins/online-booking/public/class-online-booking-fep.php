<?php
class online_booking_fep{


	//remove_fep_stylesheet
	public function remove_fep_stylesheet() {
		wp_dequeue_style( 'fep-common-style' );
		wp_dequeue_style( 'fep-style' );
	}

	/**
	 *
	 */
	public function remove_my_class_action(){
		$fep_main_class = new fep_main_class();
		//remove_action( 'Header', array( 'fep_main_class', 'Header' ),2 );
		remove_action('fep_menu_button', array($fep_main_class::init(), 'settings'));
	}

	/**
	 * fep_main_shortcode_output
	 */
	public function output_fep(){

		global $user_ID;
		$fep_main_class = new fep_main_class();
		if ($user_ID)
		{

			if ( ! fep_current_user_can('access_message') ){

				return "<div class='fep-error'>".__("You do not have permission to access message system", 'front-end-pm')."</div>";
			}

			$fep_main_class->Posted();
			//Add header
			$out = '<div id="fep-wrapper">';

			//Add Menu
			$out .= '<div id="fep-content">';

			//Start the guts of the display
			$switch = ( isset($_GET['fepaction'] ) && $_GET['fepaction'] ) ? $_GET['fepaction'] : 'messagebox';

			switch ($switch)
			{
				case has_action("fep_switch_{$switch}"):
					ob_start();
					do_action("fep_switch_{$switch}");
					$out .= ob_get_contents();
					ob_end_clean();
					break;
				case 'newmessage':
					$out .= $fep_main_class->new_message();
					break;
				case 'viewmessage':
					$out .= $fep_main_class->view_message();
					break;
				case 'settings':
					$out .= $fep_main_class->user_settings();
					break;
				case 'announcements':
					$out .= Fep_Announcement::init()->announcement_box();
					break;
				case 'view_announcement':
					$out .= Fep_Announcement::init()->view_announcement();
					break;
				//case 'directory': // See Fep_Directory Class
				//$out .= $fep_main_class->directory();
				// break;
				case 'messagebox':
				default: //Message box is shown by Default
					$out .= $fep_main_class->fep_message_box();
					break;
			}

			//Add footer
			$out .= $fep_main_class->Footer();
		}
		else
		{
			$out = "<div class='fep-error'>".sprintf(__("You must <a href='%s'>login</a> to view your message.", 'front-end-pm'), wp_login_url( get_permalink() ) )."</div>";
		}

		return $out;
		
	}

	/**
	 * fep_message_form_before_content
	 */
	public function get_vendor_manager(){

		$output = '<div class="ob-vendor-manager-list">';
		$output .= '<strong>Cliquez sur le destinaitaire de votre choix:</strong><br />';
		$output .= '<div class="inline-recipients">';
		$output .= '<div>Responsable: <span class="btn js-fill-input-to">Sebastien</span></div>';
		$output .= '<div>Chef de projet: <span class="btn js-fill-input-to">Mike</span></div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= "<script>jQuery('.js-fill-input-to').click(function(){
    var fepnameTo = $(this).html();
    $('#fep-message-top').val(fepnameTo);
    
});</script>";

		echo $output;
	}


}