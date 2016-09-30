<?php
/**
 * Template Name: booking
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header();

//Class
$ux = new online_booking_ux;
$public = new Online_Booking_Public('online-booking','v1');
$utils = new online_booking_utils();
echo $ux->get_onlyoo_admin_trip_manager();
?>



<?php

	/**
	 * validateDate
	 * Validate if comes from front form
	 *
	 * @param $date
	 * @param string $format
	 * @return bool
	 */
	function validateDate($date, $format = 'Y-m-d H:i:s'){
		    $d = DateTime::createFromFormat($format, $date);
		    return $d && $d->format($format) == $date;
	}

	$sel_participants = (isset($_POST["participants"])) ? intval($_POST["participants"]) : 5;
	$sel_theme = (isset($_POST["cat"])) ? intval($_POST["cat"]) : false;
	$sel_lieu = (isset($_POST["categories"])) ? intval($_POST["categories"]) : false;
	if(isset($_POST["formdate"])){
		$form_date = validateDate($_POST["formdate"], 'd/m/Y');
	} else{
		$form_date = false;
	}
	
	if($form_date == true){
		$sel_date = (isset($_POST["formdate"])) ? $_POST["formdate"] : date("d/m/Y");
	} else {
		$sel_date =  date("d/m/Y");
	}
	
	$date = explode('/', $sel_date); 
	$date = $date[0] . '-' . $date[1] . '-' . $date[2]; 
	$dateN1 = date('d/m/Y', strtotime("$date +1 day"));
	
				
?>
	
<div id="daysSelector"></div>			
				
<div id="content-wrap">
<div class="pure-g form-booking" id="booking-wrapper">
	<div id="primary-b" class="booking pure-u-1 pure-u-md-17-24">
	
		<div class="padd-l">
		
<! -- SETTINGS -->
<div id="on-settings">
		
	<?php

	$args = array(
		'show_option_all'    => '',
		'show_option_none'   => '',
		'option_none_value'  => '-1',
		'orderby'            => 'NAME',
		'order'              => 'ASC',
		'show_count'         => 0,
		'hide_empty'         => true,
		'child_of'           => 0,
		'exclude'            => '',
		'echo'               => 1,
		'selected'           => $sel_theme,
		'hierarchical'       => 0,
		'name'               => 'cat',
		'id'                 => 'theme',
		'class'              => 'postform terms-change form-control',
		'depth'              => 0,
		'tab_index'          => 0,
		'taxonomy'           => 'theme',
		'hide_if_empty'      => true,
		'value_field'	     => 'term_id',
	);

	$argsLieux = array(
		'show_option_all'    => '',
		'show_option_none'   => '',
		'option_none_value'  => '-1',
		'orderby'            => 'NAME',
		'order'              => 'ASC',
		'show_count'         => 0,
		'hide_empty'         => true,
		'child_of'           => 0,
		'exclude'            => '',
		'echo'               => 1,
		'selected'           => $sel_lieu,
		'hierarchical'       => 1,
		'name'               => 'categories',
		'id'                 => 'lieu',
		'class'              => 'postform terms-change form-control',
		'depth'              => 0,
		'tab_index'          => 0,
		'taxonomy'           => 'lieu',
		'hide_if_empty'      => true,
		'value_field'	     => 'term_id',
	);
?>
		


<div class="pure-g">

	<div class="pure-u-1">
		<div class="title-lead">
		<span class="blue-letter">1</span> Paramétrez votre évèvement
		</div>
	</div>

	<div class="pure-u-1">
		<div class="pure-g">
			<div class="pure-u-1-4">
				<div class="filter-selector js-toggle-next">
					<i class="fa fa-users filter-icon" aria-hidden="true"></i>
					<i class="fa fa-angle-down" aria-hidden="true"></i>
					<span class="filter-text">Choisissez votre <br>type d'évènement</span>
				</div>
				<div class="filter-view hidden">
					<div class="pure-u-1 pure-u-xl-16-24">
						<?php wp_dropdown_categories( $args ); ?>
					</div>
				</div>
			</div>
			<div class="pure-u-1-4">
				<div class="filter-selector js-toggle-next">
					<i class="fa fa-compass filter-icon" aria-hidden="true"></i>
					<i class="fa fa-angle-down" aria-hidden="true"></i>
					<span class="filter-text">Lieu de l'activité</span>
				</div>
				<div class="filter-view hidden">
					<?php wp_dropdown_categories( $argsLieux ); ?>
				</div>
			</div>
			<div class="pure-u-1-4">
				<div class="filter-selector js-toggle-next">
					<i class="fa fa-calendar filter-icon" aria-hidden="true"></i>
					<i class="fa fa-angle-down" aria-hidden="true"></i>
					<span class="filter-text">Date de l'activité</span>
				</div>
				<div class="filter-view hidden">
					<div class="xs-field">
						<div class="fa fa-calendar input-box">
							<input data-value="" value="<?php echo $sel_date; ?>" class="datepicker bk-form form-control" id="arrival">
						</div>
					</div>
					<!-- Number of days -->
					<div class="pure-u-1 pure-u-md-8-24 on-field">
						<div class="pure-g-r">
							<div class="pure-u-1 pure-u-xl-12-24">
								<div class="xs-field">
									<label class="floating-label" for="days">
										<?php _e('Nombre de jours',''); ?>
									</label>
								</div>
							</div>
							<div data-max="<?php echo esc_attr( get_option('ob_max_days',4) ); ?>" id="days-modifier" class="pure-u-1 pure-u-xl-12-24">
								<div class="xs-field">
									<button onclick="removeLastDay();">-</button>
									<input id="daysCount" readonly name="daysCount" type="text" value="2" />
									<button onclick="addADay();">+</button>
								</div>
							</div>
						</div>
					</div>
					<!-- #Number of days -->
				</div>
			</div>
			<div class="pure-u-1-4">
				<div class="filter-selector js-toggle-next">
					<i class="fa fa-users filter-icon" aria-hidden="true"></i>
					<i class="fa fa-angle-down" aria-hidden="true"></i>
					<span class="filter-text">Nombre<br>de participants</span>
				</div>
				<div class="filter-view hidden">
					<div class="fa fa-users input-box">
						<input type="number" id="participants" value="<?php echo $sel_participants; ?>" class="bk-form form-control" />
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="on-field hidden">
			<input data-value="" value="<?php echo $dateN1; ?>" class="datepicker bk-form form-control" id="departure">
	</div>




</div>

	<div class="clearfix"></div>
</div>
<!-- #SETTING -->


			<!-- budget -->
			<div class="pure-g">
			<?php
			//defined option in admin plugin
			$min_defined_budget =  esc_attr( get_option('ob_min_budget',50) );
			$max_defined_budget =  esc_attr( get_option('ob_max_budget',600) );
			?>
				<div class="pure-u-14-24">
					<label for="">
						<i id="budget-icon" class="fa fa-euro" data-exceeded="budget dépassé !"></i>
						<?php _e('Budget par participant','online-booking'); ?><em>
							(entre <span id="st"><?php echo $min_defined_budget; ?></span> <?php _e('et','online-booking'); ?> <span id="end"><?php echo $max_defined_budget; ?></span> €)</em>
					</label>
				</div>
			<div  class="pure-u-10-24">
				<div id="slider-field" class=" on-field">
						<div data-min="<?php echo $min_defined_budget; ?>" data-max="<?php echo $max_defined_budget; ?>" id="slider-range"></div>
						<input type="hidden" id="budget" value="<?php echo $min_defined_budget; ?>/<?php echo $max_defined_budget; ?>" class="bk-form form-control"  />
				</div>

			</div>
			</div>
			<!-- #budget -->


<!-- ACTIVITES -->
	<h2 class="upptitle">
		<?php _e('Votre évènement sur mesure','online-booking'); ?>
		<span><?php _e('Sélectionnez vos activités à la carte','online-booking'); ?></span>
	</h2>
	<div class="clearfix"></div>
	
							<div class="pure-g">

<div class="pure-u-1 pure-u-md-24-24">

	<?php
	// DISPLAY FILTERS
	echo $ux->get_filters();
	?>
</div>
</div>

	<?php echo $public->wp_query_thumbnail_posts(); ?>
	
	<?php
		//START POST LISTING
		 echo '<div id="activities-content" class="blocks">';
		 echo '</div>';
	?>

		    <h2 class="related-title">
        <i class="fa fa-heart"></i>
        <?php _e('Vous aimerez également','online-booking'); ?>
        </h2>

		<?php $public->the_sejours(8,true,false,true); ?>
		</div>
		</div><!-- #content -->





<!-- SIDEBAR -->
<div id="sidebar-booking-b" class="pure-u-1 pure-u-md-7-24">
	<div id="sidebar-sticky">
		
		 
    <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">

	    

<?php if ( is_active_sidebar( 'right_sidebar' ) ) : ?>
      <?php dynamic_sidebar( 'right_sidebar' ); ?>
<?php endif; ?>
	    <div id="caller-side" class="pure-g-r">
		    <div class="pure-u-1">
				    <div class="title-lead">
					    <span class="blue-letter">2</span> Ajoutez des activités <br>et créez votre évènement
				    </div>
		    </div>
	    </div>
    </div><!-- #primary-sidebar -->
 
  
<!-- JOURNEES -->
<div id="side-stick">
		<input maxlength="20" id="tripName" type="text" value="" placeholder="Nom de votre reservation" />
		<a class="reset-resa" href="#" onclick="resetReservation();">
			<?php echo __('Recommencer depuis le début.','online-booking'); ?>
		</a>
	<div id="daysTrip"></div>
	<div class="cleafix"></div>
	<span onclick="addADay();">Ajouter une journée <i class="fa fa-plus" aria-hidden="true"></i></span>


<?php
/**
 * refuse SAVE to:
 * Vendors
 * ONLYOO TEAM in GET mod
 * ( !current_user_can('onlyoo_team') && !isset($_GET['mod'])) =>
 *
 * TODO: check the current state (0,1,2) of the event
 */
if ( !current_user_can( 'vendor' ) ): ?>

<div class="pure-g" id="user-actions">
		<?php $utils->the_save_btn(); ?>
</div>

<?php endif; ?>
</div>
<!-- #JOURNEES -->	




	
</div>
</div>
<!-- #SIDEBAR -->
</div><!-- pure-g -->
</div>
<?php get_footer(); ?>