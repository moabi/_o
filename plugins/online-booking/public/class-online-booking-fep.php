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
	public function remove_fep_menu(){
		$fep_main_class = new Fep_Menu();
		remove_action('fep_menu_button', array($fep_main_class::init(), 'menu'));
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
					$out .= $this->add_new_msg();
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
					$out .= $this->add_new_msg();
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
	 * fep_messagebox
	 * Output a custom Message box HTML Listing
	 *
	 * @param string $action
	 * @param bool $total_message
	 * @param bool $messages
	 */
	public  function output_mesg_box($action = '', $total_message = false, $messages = false){

		$classFep = new fep_main_class();

		if ( !$action ){
			$action = ( ! empty( $_GET['fepaction']) ) ? $_GET['fepaction']: 'messagebox';
		}
		$g_filter = ! empty( $_GET['fep-filter'] ) ? $_GET['fep-filter'] : '';

		$mess = '';
		if( $classFep->posted_bulk_actions ) {

			if( $classFep->message ) {
				$mess = $classFep->message;
			}
		}

		if( false === $total_message ) {
			$total_message = fep_get_user_message_count('total');
		}

		if( false === $messages ){
			$messages = Fep_Message::init()->user_messages( $action );
		}

		if( ! $total_message ) {
			return "<div class='fep-error'>".apply_filters('fep_filter_messagebox_empty', __("No messages found.", 'front-end-pm'), $action)."</div>";
		}
		ob_start();

		echo $mess;

		do_action('fep_display_before_messagebox', $action);

		?><form class="fep-message-table form" method="post" action="">
		<div class="fep-table fep-action-table">
			<div class="pure-g">
				<div class="pure-u-1-2">
					<div class="fep-bulk-action">
						<select name="fep-bulk-action">
							<option value=""><?php _e('Bulk action', 'front-end-pm'); ?></option>
							<?php foreach( Fep_Message::init()->get_table_bulk_actions() as $bulk_action => $bulk_action_display ) { ?>
								<option value="<?php echo $bulk_action; ?>"><?php echo $bulk_action_display; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="fep-bulk-action-submit">
						<input type="hidden" name="token"  value="<?php echo fep_create_nonce('bulk_action'); ?>"/>
						<button type="submit" class="fep-button" name="fep_action" value="bulk_action"><?php _e('Apply', 'front-end-pm'); ?></button>
					</div>
					<div class="fep-loading-gif-div"></div>
				</div>
				<div class="pure-u-1-2">
					<div class="fep-filter">
					<select onchange="if (this.value) window.location.href=this.value">
						<option value="<?php echo esc_url( remove_query_arg( array( 'feppage', 'fep-filter') ) ); ?>"><?php _e('Show all', 'front-end-pm'); ?></option>
						<?php foreach( Fep_Message::init()->get_table_filters() as $filter => $filter_display ) { ?>
							<option value="<?php echo esc_url( add_query_arg( array('fep-filter' => $filter, 'feppage' => false ) ) ); ?>" <?php selected($g_filter, $filter);?>><?php echo $filter_display; ?></option>
						<?php } ?>
					</select>
				</div>
				</div>
			</div>
		</div>
		<?php if( $messages->have_posts() ) { ?>
			<div id="fep-table" class="fep-table fep-odd-even"><?php
			while ( $messages->have_posts() ) {
				$messages->the_post(); ?>
				<div id="fep-message-<?php echo get_the_ID(); ?>" class="fep-table-row"><?php
					foreach ( Fep_Message::init()->get_table_columns() as $column => $display ) { ?>
						<div class="fep-column fep-column-<?php echo $column; ?>"><?php Fep_Message::init()->get_column_content($column); ?></div>
					<?php } ?>
				</div>
				<?php
			} //endwhile
			?></div><?php
			echo fep_pagination();
		} else {
			?><div class="fep-error"><?php _e('No messages found. Try different filter.', 'front-end-pm'); ?></div><?php
		}
		?></form><?php
		wp_reset_postdata();

		//return apply_filters('fep_messagebox', ob_get_clean(), $action);
	}

	/**
	 * fep_message_form_before_content
	 * Automatically add some personn to contact for the vendor
	 */
	public function get_vendor_manager(){

		$user_info = get_userdata(13);
		if(isset($_GET['fepaction']) && $_GET['fepaction'] == 'newmessage'){
			$output = '<div class="ob-vendor-manager-list">';
			$output .= '<strong>Cliquez sur le destinataire de votre choix:</strong><br />';
			$output .= '<div class="inline-recipients">';
			$output .= '<div>Responsable: <span class="btn js-fill-input-to">'.$user_info->user_login.'</span></div>';
			//$output .= '<div>Chef de projet: <span class="btn js-fill-input-to">mamadou</span></div>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= "<script>jQuery('.js-fill-input-to').click(function(){
    var fepnameTo = $(this).html();
    $('#fep-message-top').val(fepnameTo);
    
});</script>";
			echo $output;
		} else {
			echo '';
		}
	}

	/**
	 * add_new_msg
	 * Add a button with New Message link
	 *
	 * @param bool $echo
	 */
	public function add_new_msg($echo = true){
		$output = '<div class="btn-reg-container"><a href="'.get_bloginfo('url').'/'.MESSENGER.'/?fepaction=newmessage" class="btn btn-reg">';
		$output .= __("Nouveau message","online-booking");
		$output .= '</a></div>';
		if($echo){
			echo $output;
		} else {
			return $output;
		}


	}




}