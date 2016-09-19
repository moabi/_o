<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 19/09/16
 * Time: 07:42
 */
global $wp_query;


$dashboard = (isset($wp_query->query_vars)) ? $wp_query->query_vars : '';

//var_dump($dashboard['pagename']);

if($dashboard['pagename'] == 'dashboard'){


	$wq = $wp_query->query_vars;
	$dash_obj = (isset($dashboard['object'])) ? $dashboard['object'] : '';
	$dash_action = (isset($dashboard['action'])) ? $dashboard['action'] : '';

	if($dash_obj == 'product' && $dash_action == 'edit'){
		$output = '<div class="sidebar-top"><div class="pure-g">';

		$output .= '<div class="pure-u-1 pure-u-md-1-3">';
		$output .= '<span class="topside-icon-number"></span>';
		$output .= '<i class="topside-icon fa fa-rocket" aria-hidden="true"></i>';
		$output .= '<span class="topside-text">';
		$output .= 'Proposez vos activités, <br /> Nous les validons ensemble.';
		$output .= '</span">';
		$output .= '</div>';

		$output .= '<div class="pure-u-1 pure-u-md-1-3">';
		$output .= '<span class="topside-icon-number"></span>';
		$output .= '<i class="topside-icon fa fa-users" aria-hidden="true"></i>';
		$output .= '<span class="topside-text">';
		$output .= 'Accueillez de nouveaux clients, <br /> selon vos disponibilités.';
		$output .= '</span">';
		$output .= '</div>';

		$output .= '<div class="pure-u-1 pure-u-md-1-3">';
		$output .= '<span class="topside-icon-number"></span>';
		$output .= '<i class="topside-icon fa fa-line-chart" aria-hidden="true"></i>';
		$output .= '<span class="topside-text">';
		$output .= 'Développez <br /> votre activité!';
		$output .= '</span">';
		$output .= '</div>';

		$output .= '</div></div>';
	}




	echo $output;
}

