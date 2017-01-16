<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 15/01/17
 * Time: 16:36
 */

?>
<?php acf_form_head(); ?>


<?php
$options = array(

	/* (string) Unique identifier for the form. Defaults to 'acf-form' */
	'id' => 'acf-form',

	/* (int|string) The post ID to load data from and save data to. Defaults to the current post ID.
	Can also be set to 'new_post' to create a new post on submit */
	'post_id' => 'new_post',

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
	'return' => '',

	/* (string) Extra HTML to add before the fields */
	'html_before_fields' => '',

	/* (string) Extra HTML to add after the fields */
	'html_after_fields' => '<p>Votre programme sera validé par nos équipes après soumission.</p>',

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



ob_start();
acf_form($options);
$contents = '<div class="white-block">';
$contents .= ob_get_contents();
$contents .= '</div>';
ob_end_clean();
return $contents;


?>