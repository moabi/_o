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

			<div id="set-avatar" class="hidden">
				<?php //echo do_shortcode('[ninja_forms id=55]'); ?>
			</div>
		</div>

		<div class="profile-info">
			<?php
			echo get_user_meta($user_id,'first_name',true);
			echo ' '.get_user_meta($user_id,'last_name',true);
			?>
			<div class="reco-info">
				<strong>23</strong> recommandations
			</div>

		</div>
		<div class="blue-bg ca">
			CHIFFRE D'AFFAIRE <br>
			<div class="montant">2980 <span class="euro">&euro;</span></div>
			<button>VIRER VOTRE ARGENT</button>
		</div>

		<?php if ( is_active_sidebar( 'sidebar-vendor-profile' ) ) : ?>
			<?php dynamic_sidebar( 'sidebar-vendor-profile' ); ?>
		<?php endif; ?>

	</div>

	<div class="white-block smile-bg">
		<div class="brown-bg">
			<span class="nbr-utils">3870</span> utilisateurs <br>
			<strong>nous font confiance</strong>
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

		<div class="bottom-info">
			Des questions ? <br>
			<i class="fa fa-phone" aria-hidden="true"></i> <em>0 826 81 10 12</em>
		</div>
	</div>
</div>
