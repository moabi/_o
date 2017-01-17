<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 15/01/17
 * Time: 16:36
 */

$ux = new online_booking_ux;
$user_id = get_current_user_id();
$edit_id = (isset($_GET['edit'])) ? intval($_GET['edit']) : false;
$edit_right = true;
if($edit_id){
	$post_author = get_post_field( 'post_author', $edit_id );
} else {
	$post_author = false;
}
$post_value = ($edit_id && ($post_author == $user_id)) ? $edit_id : 'new_post';
$edit_right = (($post_author == $user_id) && !isset($_GET['edit'])) ? true : false;

?>
<?php acf_form_head(); ?>

<?php
$args_theme = array(
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
	'hierarchical'       => 0,
	'class'              => 'postform terms-change form-control',
	'depth'              => 0,
	'taxonomy'           => 'theme',
	'hide_if_empty'      => true,

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

$form_data = '<hr />';
$form_data .= '<div class="pure-g">';
$form_data .= '<div class="pure-u-1-2">';
$form_data .= '<div class="padd-l">';
$form_data .= '<h2>Thème de votre package</h2>';
$form_data .= $ux->get_checkbox_taxonomy('theme', $args_theme);
$form_data .= '</div>';
$form_data .= '</div>';

$form_data .= '<div class="pure-u-1-2">';
$form_data .= '<div class="padd-l">';
$form_data .= '<h2>Lieu général de votre package</h2>';
$form_data .=  $ux->get_checkbox_taxonomy('lieu', $argsLieux);
$form_data .= '</div>';
$form_data .= '</div>';

$form_data .= '</div>';

$form_data .= '<p>Votre programme sera validé par nos équipes après soumission.</p>';
?>
<?php
$options = array(

	/* (string) Unique identifier for the form. Defaults to 'acf-form' */
	'id' => 'acf-form',

	/* (int|string) The post ID to load data from and save data to. Defaults to the current post ID.
	Can also be set to 'new_post' to create a new post on submit */
	'post_id' => $post_value,

	/* (array) An array of post data used to create a post. See wp_insert_post for available parameters.
	The above 'post_id' setting must contain a value of 'new_post' */
	'new_post'		=> array(
		'post_type'		=> 'sejour',
		'post_status'		=> 'pending'
	),

	/* (array) An array of field group IDs/keys to override the fields displayed in this form */
	'field_groups' => false,

	/* (array) An array of field IDs/keys to override the fields displayed in this form */
	'fields' => false,

	/* (boolean) Whether or not to show the post title text field. Defaults to false */
	'post_title' => true,

	/* (boolean) Whether or not to show the post content editor field. Defaults to false */
	'post_content' => true,

	/* (boolean) Whether or not to create a form element. Useful when a adding to an existing form. Defaults to true */
	'form' => true,

	/* (array) An array or HTML attributes for the form element */
	'form_attributes' => array(),

	/* (string) The URL to be redirected to after the form is submit. Defaults to the current URL with a GET parameter '?updated=true'.
	A special placeholder '%post_url%' will be converted to post's permalink (handy if creating a new post) */
	'return' => get_permalink().'?updated=true',

	/* (string) Extra HTML to add before the fields */
	'html_before_fields' => '',

	/* (string) Extra HTML to add after the fields */
	'html_after_fields' => $form_data,

	/* (string) The text displayed on the submit button */
	'submit_value' => __("Mettre à jour", 'acf'),

	/* (string) A message displayed above the form after being redirected. Can also be set to false for no message */
	'updated_message' => __("Post updated", 'acf'),

	/* (string) Determines where field labels are places in relation to fields. Defaults to 'top'.
	Choices of 'top' (Above fields) or 'left' (Beside fields) */
	'label_placement' => 'top',

	/* (string) Determines where field instructions are places in relation to fields. Defaults to 'label'.
	Choices of 'label' (Below labels) or 'field' (Below fields) */
	'instruction_placement' => 'label',

	/* (string) Determines element used to wrap a field. Defaults to 'div'
	Choices of 'div', 'tr', 'td', 'ul', 'ol', 'dl' */
	'field_el' => 'div',

	/* (string) Whether to use the WP uploader or a basic input for image and file fields. Defaults to 'wp'
	Choices of 'wp' or 'basic'. Added in v5.2.4 */
	'uploader' => 'wp',

	/* (boolean) Whether to include a hidden input field to capture non human form submission. Defaults to true. Added in v5.3.4 */
	'honeypot' => true

);

if($edit_right == false){
	$contents = '<div class="white-block">';
	$contents .= 'Vous n\'avez pas les droits suffisants';
	$contents .= '</div>';
}elseif(!isset($_GET['updated'])){
	ob_start();
	acf_form($options);
	$contents = '<div class="white-block">';
	$contents .= ob_get_contents();
	$contents .= '</div>';
	ob_end_clean();

} elseif(isset($_GET['updated'])){
	$contents = '<div class="white-block">';
	$contents .= '<h2>Merci pour votre contribution</h2>';
	$contents .= '</div>';


}

return $contents;

?>