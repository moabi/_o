<?php
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Quotation_Table extends WP_List_Table
{
	
	
	/*
		$validation state 
		0 = nouveau devis
		1 = devis validé
		2 = payé
	*/
	var $validation_state;

   public function __construct($args) {
	   global $status, $page;
	   parent::__construct( [
			'singular' => __( 'Order', 'online-booking' ), //singular name of the listed records
			'plural'   => __( 'Orders', 'online-booking' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?

		] );
      
      if ( is_null($args) ) return false;
		if ( is_array($args) ) {
			foreach ($args as $var => $val) $this->{$var} = $val;
		}
      
   }
    
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'ID'          		=> 'ID',
            'user_ID'       	=> 'utilisateur',
            'booking_ID' 		=> 'Event name',
            'booking_date' 		=> 'Date',
            'booking_object'	=> 'Devis',
            'booking_validation'=> 'Validation'
        );

        return $columns;
    }
    
    
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }
    
    
    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
	    $sortable_columns = array(
		    'user_ID' => array('user_ID', false),
	    	'booking_date' => array('booking_date', false)
	    	);
        return $sortable_columns;

    }
    
    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
	    global $wpdb;
			//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID	
			/*
			$sql = $wpdb->prepare(" 
						SELECT *
						FROM ".$wpdb->prefix."online_booking a	
						WHERE a.validation = %d
						",$this->validation_state); */
			//4 should be a archived stuff
			$sql = $wpdb->prepare(" 
						SELECT *
						FROM ".$wpdb->prefix."online_booking a	
						WHERE a.validation != %d
						ORDER BY a.booking_date
						",4);
							
			$results = $wpdb->get_results($sql);
			$data = json_decode(json_encode($results), true);
			//var_dump($data);
			return $data;
    }
    
    

	/**
	 * column_id
	 * Used to display the value of the id column
	 *
	 * @param $item
	 * @return string
	 */
		public function column_id($item)
		{
		    //return $item['ID'];
		    /*
		    $del_input = '<input id="s-'.$this->validation_state.'-'.$item['ID'].'" type="checkbox" name="bulk-delete[]" value="'.$item['ID'].'" /> ';
            return $del_input;*/
            
            return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
        
		}

	/**
	 * column_booking_object
	 *
	 * @param $item
	 */
		public function column_booking_object($item){
			$json_obj = $item['booking_object'];
			$tripID =  $item['ID'];
			$budget = json_decode($json_obj, true);
			$budgetMaxTotal = $budget['participants'] * $budget['budgetPerMax'];

					//var_dump($budget);
					echo '<div class="mfp-hide hidden" id="tu-'.$tripID.'">';
					echo '<div class="trip-budget-user">';
					echo '<h3>Le budget de votre event</h3>';
					echo '<div class="excerpt-user pure-g">';
					echo '<div class="pure-u-1-3">'.$budget['days'].' jours</div>';
					echo '<div class="pure-u-1-3">'.$budget['participants'].' participants </div>';
					echo '<div class="pure-u-1-3">Buget Max Total : '.$budgetMaxTotal.' </div>';
					echo '</div>';
					echo '<div class="excerpt-budget">';
					
					echo '<table class="wp-list-table widefat  striped ">';
					echo '<tr><td>Budget Minimum par personne </td><td> '.$budget['budgetPerMin'].'</td></tr>';
					echo '<tr><td>Budget Minimum </td><td>'.$budget['budgetPerMin'] * $budget['participants'].'</td></tr>';
					echo '<tr><td>Budget Maximum par personne </td><td> '.$budget['budgetPerMax'].'</td></tr>';
					echo '<tr><td>Budget Maximum </td><td>'.$budget['budgetPerMax'] * $budget['participants'].'</td></tr>';
					echo '<tr><td>Budget global par personne </td><td>'.$budget['currentBudget'].'</td></tr>';
					echo '</table>';
					echo '</div>';
					echo 'Budget Total : '.$budget['currentBudget'] * $budget['participants'].'<br />';
					
					
					echo '<h4>Détails de votre event : </h4>';
					$trips = $budget['tripObject'];
					$budgetSingle = array();
					//var_dump(is_array($trips));
					echo '<div class="activity-budget-user pure-g">';
						        echo '<div class="pure-u-1-3">Activité</div>';
						        //echo $value['type'].'<br />';
					            echo '<div class="pure-u-1-3">prix/pers</div>';
					            echo '<div class="pure-u-1-3">prix total</div>';
					echo '</div>';
					foreach ($trips as $trip) {
					    //  Check type
					    if (is_array($trip)){
					        //  Scan through inner loop
					        
					        foreach ($trip as $value) {
						        //calculate 
						        array_push($budgetSingle, $value['price']);
						        //html
						        echo '<div class="activity-budget-user pure-g">';
						        echo '<div class="pure-u-1-3">'.$value['name'].'</div>';
						        //echo $value['type'].'<br />';
					            echo '<div class="pure-u-1-3">'.$value['price'].'</div>';
					            echo '<div class="pure-u-1-3">'.$value['price'] * $budget['participants'].'</div>';
					            echo '</div>';
					        }
					    }else{
					        // one, two, three
					        echo $trip;
					    }
					}
					$single_budg = array_sum($budgetSingle);
					$global_budg = $single_budg * $budget['participants'];
					echo '<div class="activity-budget-user pure-g">';
						        echo '<div class="pure-u-1-3">Budget Total</div>';
						        //echo $value['type'].'<br />';
					            echo '<div class="pure-u-1-3">'.$single_budg.'</div>';
					            echo '<div class="pure-u-1-3">'.$global_budg.'</div>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '<a href="#TB_inline?width=600&height=550&inlineId=tu-'.$tripID.'" class="thickbox">Devis</a>';



			//return $budgetMaxTotal;
		}
		
	/**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'ID':
            case 'user_ID':
            	$user_info = get_userdata( $item['user_ID'] );
            	//$data = $user_info->user_login.'</br >';
            	$data = $user_info->user_email.'</br >';
            	$data .= $user_info->user_firstname.' ';
            	$data .= $user_info->user_lastname.'</br >';
            	return $data;
            case 'booking_date':
            	$tripDate = $item['booking_date'];
				$newDate = date("d/m/y", strtotime($tripDate));
				return $newDate;
            case 'booking_object':
            	
            case 'booking_validation':
            	if($item['validation'] == 0){
	            	$state = 'Nouveau devis';
            	}elseif($item['validation'] == 1){
	            	$state = 'Devis validé';
            	}elseif($item['validation'] == 2){
	            	$state = 'Cloturé';
            	}else{
	            	$state = 'Facturation';
            	}
            	return $state;
            	
            case 'booking_ID':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }
    
    
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
		
		$this->process_bulk_action();
		
        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 25;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
		
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }
    
	
	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
	  $actions = [
		'bulk-validate' => "Valider Devis",
	    'bulk-delete' => 'SUPPRIMER',
	    'bulk-callback' => 'Mail rappel',
	    'bulk-terminate' => 'Cloturer',
	    
	  ];
	
	  return $actions;
	}

	/*
	 * process_bulk_action
	 * will process table actions
	*/
	public function process_bulk_action() {
	
	  //Detect when a bulk action is being triggered...
	  if ( 'delete' === $this->current_action() || 'validate' === $this->current_action() ) {
	
	    // In our file that handles the request, verify the nonce.
	    $nonce = esc_attr( $_REQUEST['_wpnonce'] );
	
	    if ( ! wp_verify_nonce( $nonce, 'ob_delete_customer' ) ) {
	      die( 'Go get a life script kiddies' );
	    }
	    else {
	      //self::delete_customer( absint( $_GET['customer'] ) );
	
	       wp_die('Items deleted (or they would be if we had items to delete)!');
	    }
	
	  }
	  //$admin_action = new Online_Booking_Admin;
	  //$mailer = new Online_Booking_Mailer;
	  // If the delete bulk action is triggered
	  if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
	       || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
	  ) {
	
	    $order_ids = esc_sql( $_POST['order'] );
	
	    // loop over the array of record IDs and delete them
	    foreach ( $order_ids as $id ) {
	      //self::delete_customer( $id );
	      $sender = new Online_Booking_Mailer;
	      $sender->send_mail('confirmation','moabi31@gmail.com','Suppresion du séjour :'. $id);
	      $this::deleteItems($id);
	
	    }
	
	     wp_die('Items deleted');
	    
	  } elseif( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-validate' )
	  		|| ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-validate') 
	  ){
		  
			$order_ids = esc_sql( $_POST['order'] );
			global $wpdb;

		    // loop over the array of record IDs and delete them
		    foreach ( $order_ids as $id ) {
		      $table = $wpdb->prefix.'online_booking';
				$rowToEstimate = $wpdb->update( 
						$table, 
						array(
							'validation'	=> 1
						),
						array( 
							'ID' 			=> $id,
						),
						array(
							'%d'
						),
						array( '%d' ) 
				 );
				$sender = new Online_Booking_Mailer;
	      $sender->send_mail('confirmation','moabi31@gmail.com','Hello world'. $id);
		
		    }
	
		     wp_die('Settings updated');
	  } elseif( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-terminate' )
	  		|| ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-terminate') 
	  ){
		  
			$order_ids = esc_sql( $_POST['order'] );
			global $wpdb;

		    // loop over the array of record IDs and delete them
		    foreach ( $order_ids as $id ) {
		      $table = $wpdb->prefix.'online_booking';
				$rowToEstimate = $wpdb->update( 
						$table, 
						array(
							'validation'	=> 2
						),
						array( 
							'ID' 			=> $id,
						),
						array(
							'%d'
						),
						array( '%d' ) 
				 );
				$sender = new Online_Booking_Mailer;
				//change mail status
				//$sender->send_mail('confirmation','moabi31@gmail.com','Hello world'. $id);
		
		    }
	
		     wp_die('Settings updated');
	  }
	}

	/**
	 * deleteItems
	 *
	 * @param $id
	 */
	public function deleteItems($id){
	  global $wpdb;
      $table = $wpdb->prefix.'online_booking';
		$rowToEstimate = $wpdb->delete( 
				$table, 
				array( 
					'ID' 			=> $id,
				),
				array(
					'%d'
				)
		);
	}
		
	

}