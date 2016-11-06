<?php
/**
 * Created by PhpStorm.
 * User: dfieffe
 * Date: 29/05/2015
 * Time: 13:43
 */
?>
<?php
global $wp_query;
$page_id = $wp_query->post->ID;
$user_id = get_current_user_id();
?>
<div class="pure-u-1 pure-u-md-6-24">
<div id="secondary" class="sidebar sidebar-vendor vendor-profile">
	<div class="avatar-change">
		<?php
		echo get_avatar($user_id,92);
		?>
		<span class="js-change-avatar camera">
			<i class="fa fa-camera" aria-hidden="true"></i>
		</span>

		<div id="set-avatar">
			<?php echo do_shortcode('[ninja_forms id=55]'); ?>
		</div>
	</div>

	<br>
	<strong>
	<?php
	echo get_user_meta($user_id,'first_name',true);
	echo ' '.get_user_meta($user_id,'last_name',true);
	?>
	</strong>

	<strong>23</strong> recommandations

<div class="blue-bg">
<div class="pure-g">
	<div class="pure-u-1">
CHIFFRE D'AFFAIRE <br>
		2980 E <br>
		<button>VIRER VOTRE ARGENT</button>
	</div>
</div>
</div>

	<?php if ( is_active_sidebar( 'sidebar-vendor-profile' ) ) : ?>
	      <?php dynamic_sidebar( 'sidebar-vendor-profile' ); ?>
	  <?php endif; ?>

</div>

	<div class="white-block smile-bg">
		<div class="brown-bg">
			3870 utilisateurs <br>
			nous font confiance
		</div>
		<ul class="checkmark">
			<li>
				Tiers de confiance
			</li>
			<li>
				Garanties Onlyoo
			</li>
			<li>
				Transaction sécurisé
			</li>
		</ul>


		<em>
			Des questions ?
		</em> <br>
		<i class="fa fa-phone" aria-hidden="true"></i> 0 826 81 10 12

	</div>
</div>

