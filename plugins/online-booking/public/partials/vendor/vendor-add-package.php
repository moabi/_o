<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 15/01/17
 * Time: 16:36
 */
//acf_form_head();
acf_enqueue_uploader();

$ux = new online_booking_ux;
$user_id = get_current_user_id();
//check if it's an edition
$edit_id = (isset($_GET['edit'])) ? intval($_GET['edit']) : false;
$edit_mode = false;
$edit_right = true; // set
$post_value = 'new_post';
$post_thumbnail = '';
$theme_list_array = [];
$lieu_list_array = [];
$submit_label = __('Enregistrer','onlyoo');
$new_post_value = array(
	'post_type'		=> 'sejour',
	'post_status'	=> 'pending',
	'post_author'   => $user_id
);
$success_page = home_url(VENDOR_LIST_PACKAGE.'?type=sejour_saved');
if($edit_id){
	//get post author
	$post_author = get_post_field( 'post_author', $edit_id );
	$post_value = ($post_author == $user_id) ? $edit_id : 'new_post'; //set to new post
	$edit_mode = ($post_author == $user_id) ? true : false; //set to new post
	$edit_right = ($post_author == $user_id) ? true : false;
	if($edit_mode){
		$submit_label = __('Mettre à jour','onlyoo');
		$new_post_value = false;
		$post_thumbnail = get_the_post_thumbnail_url($edit_id);
		$lieu_list = wp_get_post_terms($edit_id, 'lieu', array("fields" => "all"));
		$theme_list = wp_get_post_terms($edit_id, 'theme', array("fields" => "all"));
		$success_page = home_url(VENDOR_LIST_PACKAGE.'?type=sejour_updated');
		foreach($theme_list as $term_single) {
			array_push($theme_list_array,$term_single->term_id);
		}
		foreach($lieu_list as $term_single) {
			array_push($lieu_list_array,$term_single->term_id);
		}

	}

}


/**
 * ADD A MEDIA UPLOAD FOR THUMBNAIL
 */
	$media = '<div class="upload-wrapper">';
	$media .= '<input type="button" name="upload-btn" id="upload-btn" class="button-secondary btn" 
value="Ajoutez une image principale" style="border:none;box-shadow: none;">';
	$media .= '</div>';

	$media .= "<script type='text/javascript'>
		jQuery(document).ready(function($){
			$('#upload-btn').click(function(e) {
				e.preventDefault();
				var image = wp.media({
					title: 'Upload Image',
					// mutiple: true if you want to upload multiple files at once
					multiple: false
				}).open()
					.on('select', function(e){
						// This will return the selected image from the Media Uploader, the result is an object
						var uploaded_image = image.state().get('selection').first();
						// We convert uploaded_image to a JSON object to make accessing it easier
						// Output to the console uploaded_image
						console.log(uploaded_image);
						var image_url = uploaded_image.toJSON().url;
						// Let's assign the url value to the input field
						$('#image_url').val(image_url);
						$('#uploaded-image').attr('src',image_url);
					});
			});
		});
	</script>";
?>
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
$form_data .= $ux->get_checkbox_taxonomy('theme', $args_theme,$theme_list_array);
$form_data .= '</div>';
$form_data .= '</div>';

$form_data .= '<div class="pure-u-1-2">';
$form_data .= '<div class="padd-l">';
$form_data .= '<h2>Lieu général de votre package</h2>';
$form_data .=  $ux->get_checkbox_taxonomy('lieu', $argsLieux,$lieu_list_array);
$form_data .= '</div>';
$form_data .= '</div>';

$form_data .= '</div>';

$form_data .= '<div class="clearfix"></div>';
$form_data .= '<div class="pure-g">';
$form_data .= '<div class="pure-u-md-1-2">';
$form_data .= $media;
$form_data .= '<input type="hidden" placeholder="Télécharger votre image avec le bouton ci-dessus" readonly name="image_url" id="image_url" class="regular-text">';
$form_data .= '</div>';
$form_data .= '<div class="pure-u-md-1-2">';
$form_data .= '<img src="'.$post_thumbnail.'" id="uploaded-image" alt="" />';
$form_data .= '</div>';
$form_data .= '</div>';


$form_data .= '<p>Votre programme sera validé par nos équipes après soumission.</p>';


/**
 * START ACF FORM
 */
$options = array(

	/* (string) Unique identifier for the form. Defaults to 'acf-form' */
	'id' => 'acf-form',

	/* (int|string) The post ID to load data from and save data to. Defaults to the current post ID.
	Can also be set to 'new_post' to create a new post on submit */
	'post_id' => $post_value,

	/* (array) An array of post data used to create a post. See wp_insert_post for available parameters.
	The above 'post_id' setting must contain a value of 'new_post' */
	'new_post'		=> $new_post_value,

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
	'return' => $success_page,

	/* (string) Extra HTML to add before the fields */
	'html_before_fields' => '',

	/* (string) Extra HTML to add after the fields */
	'html_after_fields' => $form_data,

	/* (string) The text displayed on the submit button */
	'submit_value' => $submit_label,

	/* (string) A message displayed above the form after being redirected. Can also be set to false for no message */
	'updated_message' => false,

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
}elseif($edit_right == true){
	ob_start();
	acf_form($options);
	$contents = '<div class="white-block">';
	$contents .= ob_get_contents();
	$contents .= '</div>';
	ob_end_clean();

}

return $contents;

?>