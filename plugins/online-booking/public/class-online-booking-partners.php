<?php


/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://little-dream.fr
 * @since      1.0.0
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/public
 * @author     little-dream.fr <david@loading-data.com>
 */
class online_booking_partners
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


    /**
     * get_partner_activites
     * List partner activities with status (draft, valid,...)
     * TODO preview_nonce - create a nonce to secure & allow previewing
     * @return string
     */
    public static function get_partner_activites()
    {

        global $wpdb;


        $userID = get_current_user_id();

	    $nonce = wp_create_nonce( $userID );
        // The Query
        $args = array(
            'author' => $userID,
            'post_status' => array('pending', 'draft', 'publish'),
            'post_type' => 'product'
        );
        $the_partners_query = new WP_Query($args);

        // The Loop
	    if ( $the_partners_query->have_posts() ) :
		    $post_id = get_the_ID();

            $output =  '<table id="userTrips" class="partners u-' . $userID . ' post-'.$post_id.'">';
			    while ( $the_partners_query->have_posts() ) : $the_partners_query->the_post();
	                $output .= '<tr>';
					if ( has_post_thumbnail() ) :
						$output .= '<td><a href="'.get_the_permalink().'" title="'.get_the_title().'">';
						$output .= woocommerce_get_product_thumbnail(array(125,125));
						$output .= '</a></td>';
					endif;
	                $output .= '<td>'.get_the_title().'</td>';
	                if (get_post_status() == 'pending') {
	                    $output .= '<td>En attente de publication</td>';
	                } elseif (get_post_status() == 'publish') {
	                    $output .= '<td>public</td>';
	                }
	                $preview_url = '?preview_id='.get_the_ID().'&preview_nonce='.$nonce.'&preview=true';
				    $update_url = '';
	                $output .= '<td><a target="_blank" href="'.get_site_url(null,$update_url).'"> '.__('Modifier','online-booking').'</a></td>';

				    $output .= '<td><a target="_blank" href="'.get_site_url(null,$preview_url).'">'.__('Aperçu','online-booking').'</a></td>';
	                $output .= '</tr>';
	            endwhile;
            $output .= '</table>';

        else:
            // no posts found
            $output = __('Pas encore d\'activité.', 'online-booking');
        endif;
        /* Restore original Post Data */
        wp_reset_postdata();

        return $output;

    }






}