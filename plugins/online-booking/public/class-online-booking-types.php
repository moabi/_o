<?php
class Online_Booking_Types{
	/**
	 * lieu
	 * Register Custom Taxonomy
	 * for reservation & sejour
	 */
	public function lieu()
	{

		$labels = array(
			'name' => _x('lieux', 'Taxonomy General Name', 'twentyfifteen'),
			'singular_name' => _x('lieu', 'Taxonomy Singular Name', 'twentyfifteen'),
			'menu_name' => __('lieux', 'twentyfifteen'),
			'all_items' => __('Tous les lieux', 'twentyfifteen'),
			'parent_item' => __('Parent', 'twentyfifteen'),
			'parent_item_colon' => __('Parent lieu', 'twentyfifteen'),
			'new_item_name' => __('Nouveau lieu', 'twentyfifteen'),
			'add_new_item' => __('Ajouter nouveau lieu', 'twentyfifteen'),
			'edit_item' => __('Editer lieu', 'twentyfifteen'),
			'update_item' => __('Mettre à jout ', 'twentyfifteen'),
			'view_item' => __('Voir lieu', 'twentyfifteen'),
			'separate_items_with_commas' => __('Separate items with commas', 'twentyfifteen'),
			'add_or_remove_items' => __('Add or remove items', 'twentyfifteen'),
			'choose_from_most_used' => __('Choose from the most used', 'twentyfifteen'),
			'popular_items' => __('Popular Items', 'twentyfifteen'),
			'search_items' => __('Search Items', 'twentyfifteen'),
			'not_found' => __('Not Found', 'twentyfifteen'),
		);
		$args = array(
			'labels' => $labels,
			'hierarchical' => true,
			'public' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => true,
		);
		register_taxonomy('lieu', array('reservation', 'sejour','product'), $args);

	}

	/**
	 * reservation_type
	 * Register Custom Taxonomy
	 * for reservation custom post type
	 */
	public function reservation_type()
	{

		$labels = array(
			'name' => _x('type', 'Taxonomy General Name', 'twentyfifteen'),
			'singular_name' => _x('type', 'Taxonomy Singular Name', 'twentyfifteen'),
			'menu_name' => __('types', 'twentyfifteen'),
			'all_items' => __('Tous les types', 'twentyfifteen'),
			'parent_item' => __('Parent', 'twentyfifteen'),
			'parent_item_colon' => __('Parent type', 'twentyfifteen'),
			'new_item_name' => __('Nouveau type', 'twentyfifteen'),
			'add_new_item' => __('Ajouter nouveau type', 'twentyfifteen'),
			'edit_item' => __('Editer type', 'twentyfifteen'),
			'update_item' => __('Mettre à jout ', 'twentyfifteen'),
			'view_item' => __('Voir type', 'twentyfifteen'),
			'separate_items_with_commas' => __('Separate items with commas', 'twentyfifteen'),
			'add_or_remove_items' => __('Add or remove items', 'twentyfifteen'),
			'choose_from_most_used' => __('Choose from the most used', 'twentyfifteen'),
			'popular_items' => __('Popular Items', 'twentyfifteen'),
			'search_items' => __('Search Items', 'twentyfifteen'),
			'not_found' => __('Not Found', 'twentyfifteen'),
		);
		$args = array(
			'labels' => $labels,
			'hierarchical' => true,
			'public' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => true,
		);
		register_taxonomy('reservation_type', array('reservation','product'), $args);

	}

	/**
	 * Register Custom Taxonomy
	 * secteur activité du séjour (particulier,séminaire,teamBuilding,...)
	 * for reservation custom post type
	 */
	public function theme()
	{

		$labels = array(
			'name' => _x('Secteur d\'activité', 'Taxonomy General Name', 'twentyfifteen'),
			'singular_name' => _x('Secteur d\'activité', 'Taxonomy Singular Name', 'twentyfifteen'),
			'menu_name' => __('Secteurs d\'activités', 'twentyfifteen'),
			'all_items' => __('Tous les Secteurs d\'activités', 'twentyfifteen'),
			'parent_item' => __('Parent', 'twentyfifteen'),
			'parent_item_colon' => __('Parent thème', 'twentyfifteen'),
			'new_item_name' => __('Nouveau thème', 'twentyfifteen'),
			'add_new_item' => __('Ajouter nouveau thème', 'twentyfifteen'),
			'edit_item' => __('Editer thème', 'twentyfifteen'),
			'update_item' => __('Mettre à jout ', 'twentyfifteen'),
			'view_item' => __('Voir thème', 'twentyfifteen'),
			'separate_items_with_commas' => __('Separate items with commas', 'twentyfifteen'),
			'add_or_remove_items' => __('Add or remove items', 'twentyfifteen'),
			'choose_from_most_used' => __('Choose from the most used', 'twentyfifteen'),
			'popular_items' => __('Popular Items', 'twentyfifteen'),
			'search_items' => __('Search Items', 'twentyfifteen'),
			'not_found' => __('Not Found', 'twentyfifteen'),
		);
		$args = array(
			'labels' => $labels,
			'hierarchical' => true,
			'public' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => true,
		);
		register_taxonomy('theme', array('reservation','product'), $args);

	}


	/**
	 * Register Custom Taxonomy
	 * theme_activity (activity,eat,party,...)
	 * for reservation custom post type
	 */
	public function theme_activity()
	{

		$labels = array(
			'name' => _x('Theme', 'Taxonomy General Name', 'twentyfifteen'),
			'singular_name' => _x('Theme', 'Taxonomy Singular Name', 'twentyfifteen'),
			'menu_name' => __('Theme', 'twentyfifteen'),
			'all_items' => __('Tous les Themes', 'twentyfifteen'),
			'parent_item' => __('Parent', 'twentyfifteen'),
			'parent_item_colon' => __('Parent thème', 'twentyfifteen'),
			'new_item_name' => __('Nouveau thème', 'twentyfifteen'),
			'add_new_item' => __('Ajouter nouveau thème', 'twentyfifteen'),
			'edit_item' => __('Editer thème', 'twentyfifteen'),
			'update_item' => __('Mettre à jout ', 'twentyfifteen'),
			'view_item' => __('Voir thème', 'twentyfifteen'),
			'separate_items_with_commas' => __('Separate items with commas', 'twentyfifteen'),
			'add_or_remove_items' => __('Add or remove items', 'twentyfifteen'),
			'choose_from_most_used' => __('Choose from the most used', 'twentyfifteen'),
			'popular_items' => __('Popular Items', 'twentyfifteen'),
			'search_items' => __('Search Items', 'twentyfifteen'),
			'not_found' => __('Not Found', 'twentyfifteen'),
		);
		$args = array(
			'labels' => $labels,
			'hierarchical' => true,
			'public' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => true,
		);
		register_taxonomy('theme_activity', array('reservation','product'), $args);

	}


	/**
	 * Register Custom Post Type reservation
	 *
	 */
	public function reservation_post_type()
	{

		$labels = array(
			'name' => _x('Activités', 'Post Type General Name', 'twentyfifteen'),
			'singular_name' => _x('Activité', 'Post Type Singular Name', 'twentyfifteen'),
			'menu_name' => __('Activité', 'twentyfifteen'),
			'name_admin_bar' => __('Activités', 'twentyfifteen'),
			'parent_item_colon' => __('Parent Activity:', 'twentyfifteen'),
			'all_items' => __('Toutes les Activités', 'twentyfifteen'),
			'add_new_item' => __('Ajouter Activité', 'twentyfifteen'),
			'add_new' => __('Ajouter nouvelle', 'twentyfifteen'),
			'new_item' => __('Nouvelle Activité', 'twentyfifteen'),
			'edit_item' => __('Editer Activité', 'twentyfifteen'),
			'update_item' => __('Mettre à jour Activité', 'twentyfifteen'),
			'view_item' => __('Voir Activité', 'twentyfifteen'),
			'search_items' => __('Chercher une reservation', 'twentyfifteen'),
			'not_found' => __('Non trouvée', 'twentyfifteen'),
			'not_found_in_trash' => __('Non trouvée dans la poubelle', 'twentyfifteen'),
		);
		$args = array(
			'label' => __('reservation', 'twentyfifteen'),
			'description' => __('Booking for SB', 'twentyfifteen'),
			'labels' => $labels,
			'supports' => array('title', 'editor', 'thumbnail', 'author'),
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 5,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'capability_type' => 'page',
		);
		register_post_type('reservation', $args);

	}

	/**
	 * sejour_post_type
	 * Register Custom Post Type: sejour
	 */
	public function sejour_post_type()
	{

		$labels = array(
			'name' => _x('sejours', 'Post Type General Name', 'twentyfifteen'),
			'singular_name' => _x('sejour', 'Post Type Singular Name', 'twentyfifteen'),
			'menu_name' => __('sejour (Pack)', 'twentyfifteen'),
			'name_admin_bar' => __('sejour', 'twentyfifteen'),
			'parent_item_colon' => __('Parent sejour:', 'twentyfifteen'),
			'all_items' => __('Tous les sejours', 'twentyfifteen'),
			'add_new_item' => __('Ajouter sejour', 'twentyfifteen'),
			'add_new' => __('Nouveau sejour', 'twentyfifteen'),
			'new_item' => __('Nouveau sejour', 'twentyfifteen'),
			'edit_item' => __('Editer sejour', 'twentyfifteen'),
			'update_item' => __('Mettre à jour sejour', 'twentyfifteen'),
			'view_item' => __('Voir sejour', 'twentyfifteen'),
			'search_items' => __('Chercher un sejour', 'twentyfifteen'),
			'not_found' => __('Non trouvé', 'twentyfifteen'),
			'not_found_in_trash' => __('Non trouvé dans la poubelle', 'twentyfifteen'),
		);
		$args = array(
			'label' => __('sejour', 'twentyfifteen'),
			'description' => __('sejour for SB', 'twentyfifteen'),
			'labels' => $labels,
			'supports' => array('title', 'editor', 'thumbnail', 'author'),
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 5,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'capability_type' => 'page',
		);
		register_post_type('sejour', $args);

	}


	/**
	 * partner_post_type
	 * Register Custom Post Type: partner
	 * UNUSED
	 */
	public function partner_post_type()
	{

		$labels = array(
			'name' => _x('Partenaires', 'Post Type General Name', 'twentyfifteen'),
			'singular_name' => _x('Partenaire', 'Post Type Singular Name', 'twentyfifteen'),
			'menu_name' => __('Partenaires', 'twentyfifteen'),
			'name_admin_bar' => __('Partenaire', 'twentyfifteen'),
			'parent_item_colon' => __('Partenaire supérieur:', 'twentyfifteen'),
			'all_items' => __('Tous les partenaires', 'twentyfifteen'),
			'add_new_item' => __('Ajouter partenaire', 'twentyfifteen'),
			'add_new' => __('Nouveau partenaire', 'twentyfifteen'),
			'new_item' => __('Nouveau partenaire', 'twentyfifteen'),
			'edit_item' => __('Editer partenaire', 'twentyfifteen'),
			'update_item' => __('Mettre à jour partenaire', 'twentyfifteen'),
			'view_item' => __('Voir partenaire', 'twentyfifteen'),
			'search_items' => __('Chercher un partenaire', 'twentyfifteen'),
			'not_found' => __('Non trouvé', 'twentyfifteen'),
			'not_found_in_trash' => __('Non trouvé dans la poubelle', 'twentyfifteen'),
		);
		$args = array(
			'label' => __('sejour', 'twentyfifteen'),
			'description' => __('sejour for SB', 'twentyfifteen'),
			'labels' => $labels,
			'supports' => array('title', 'editor', 'thumbnail', 'author'),
			'hierarchical' => false,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 5,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'capability_type' => 'page',
		);
		register_post_type('partner', $args);

	}


}