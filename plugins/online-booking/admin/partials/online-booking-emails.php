<div class="wrap">
<h2>Online-booking - Emails & settings</h2>

<?php 
	$tiny_mce_args = array(
		'media_buttons' => true,
		'editor_height' => 120,
		'teeny'			=> true
	); 
	?>
<form method="post" action="options.php">
    <?php settings_fields( 'ob-settings-group' ); ?>
    <?php do_settings_sections( 'ob-settings-group' ); ?>
    <table class="form-table">
	            <tr valign="top">
        <th scope="row">Budget minimum / max (EUROS)</th>
        <td>
	        <input type="number"  name="ob_min_budget" placeholder="600" value="<?php echo esc_attr( get_option('ob_min_budget',50) ); ?>">
	        
        </td>
        <td>
	        <input type="number"  name="ob_max_budget" placeholder="600" value="<?php echo esc_attr( get_option('ob_max_budget',600) ); ?>">
	        
        </td>
        </tr>
        
         <tr valign="top">
        <td>
	        <strong>Nombre de jours Max</strong><br />
	        (4 jours recommandés max)
	     </td>
        <td>
	        <input type="number"  name="ob_max_days" placeholder="4" value="<?php echo esc_attr( get_option('ob_max_days',4) ); ?>">
	        
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Email(s) Administrateurs</th>
        <td>
	        <input  name="ob_admin_email" placeholder="Email Administrateur" value="<?php echo esc_attr( get_option('ob_admin_email') ); ?>">
	        
        </td>
        </tr>
        
        <tr>
	        <td><b>Email Confirmation</b>
		        <br />
		        Shortcodes : [client-name],[client-mail],[client-order-id]
		        
	        </td>
	        <td>
		        <?php
			        $content = esc_attr( get_option('ob_confirmation_content') );
					$editor_id = 'ob_confirmation_content';
					
					wp_editor( $content, $editor_id,$tiny_mce_args );
				?>
	        </td>
        </tr>
        
        <tr>
	        <td><b>Email Annulation</b>
		        <br />
		        Shortcodes : [client-name],[client-mail],[client-order-id]
	        </td>
	        <td>
		        <?php
			        $content = esc_attr( get_option('ob_annulation_content') );
					$editor_id = 'ob_annulation_content';
					
					wp_editor( $content, $editor_id,$tiny_mce_args );
				?>
	        </td>
        </tr>
        
         

    </table>
    
    <?php submit_button(); ?>

</form>
</div>