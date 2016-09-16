<?php
class online_booking_fep{

	public function remove_my_class_action(){
		$fep_main_class = new fep_main_class();
		//remove_action( 'Header', array( 'fep_main_class', 'Header' ),2 );
		remove_action('fep_menu_button', array($fep_main_class::init(), 'settings'));
	}
}