<?php

class pure_walker_nav_menu extends Walker_Nav_Menu {

	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output )
	{
		$id_field = $this->db_fields['id'];
		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
		}
		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}


	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );

// Select a CSS class for this `<ul>` based on $depth
		switch ( $depth ) {
			case 0:
				// Top-level submenus get the 'nav-main-sub-list' class
				$class = 'pure-menu-children';
				break;
			case 1:
				$class = 'pure-menu-children';
				break;
			case 2:
				$class = 'pure-menu-children';
				break;
			case 3:
				// Submenus nested 1-3 levels deep get the 'nav-other-sub-list' class
				$class = 'pure-menu-children';
				break;
			default:
				// All other submenu `<ul>`s receive no class
				break;
		}

		// Only print out the 'class' attribute if a class has been assigned
		if ( isset( $class ) ) {
			$output .= "\n$indent<ul class=\"$class\">\n";
		} else {
			$output .= "\n$indent<ul>\n";
		}
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent     = str_repeat( "\t", $depth );
		$attributes = '';

		! empty ( $item->attr_title )
		// Avoid redundant titles
		and $item->attr_title !== $item->title
		    and $attributes .= ' title="' . esc_attr( $item->attr_title ) . '"';

		! empty ( $item->url )
		and $attributes .= ' href="' . esc_attr( $item->url ) . '"';

		$attributes  = trim( $attributes );
		$title       = apply_filters( 'the_title', $item->title, $item->ID );
		$item_output = "$args->before<a class='pure-menu-link' $attributes>$args->link_before$title</a>"
		               . "$args->link_after$args->after";

		if ( $args->has_children ) {
			$parent_class= "  pure-menu-has-children pure-menu-allow-hover";
		} else {
			$parent_class= " no-has-children";
		}

		// Select a CSS class for this `<li>` based on $depth
		switch ( $depth ) {
			case 0:
				// Top-level `<li>`s get the 'nav-main-item' class
				$class = 'pure-menu-item'.$parent_class;
				break;
			case 1:
				// Top-level `<li>`s get the 'nav-main-item' class
				$class = 'pure-menu-item'.$parent_class;
				break;
			case 2:
				// Top-level `<li>`s get the 'nav-main-item' class
				$class = 'pure-menu-item';
				break;
			default:
				// All other `<li>`s receive no class
				break;
		}


		// Only print out the 'class' attribute if a class has been assigned
		if ( isset( $class ) ) {
			$output .= $indent . '<li class="' . $class . '">';
		} else {
			$output .= $indent . '<li>';
		}

		$output .= apply_filters(
			'walker_nav_menu_start_el',
			$item_output,
			$item,
			$depth,
			$args
		);
	}


}
