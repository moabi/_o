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
$class_ux = new online_booking_ux();
?>
<div class="pure-u-1 pure-u-md-6-24" id="sidebar-vendor-account">
	<div id="secondary" class="sidebar sidebar-vendor vendor-profile">
		<div class="avatar-change">
			<?php
			echo $class_ux->get_custom_avatar($user_id,92);
			?>
			<a href="#set-avatar" class="js-change-avatar camera open-popup-link">
				<i class="fa fa-camera" aria-hidden="true"></i>
			</a>

			<div id="set-avatar" class="white-popup mfp-hide">
				<?php
				$avatar_form = esc_attr( get_option('ob_avatar_shortcode') );
				echo do_shortcode($avatar_form); ?>
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
			<?php
			$store_report = new WCVendors_Pro_Reports_Controller( 'wcvendors_pro', '1.3.6', false );
			$store_report->report_init();
			if(isset($store_report->commission_due)){
				$send_money_btn = (intval($store_report->commission_due) > 0)? '<button>VIRER VOTRE 
				ARGENT</button>':'' ;
			}
			?>
			<div class="montant"><?php
				if(isset($store_report->commission_paid)){
					echo wc_price($store_report->commission_paid);
				}
				?></div>
			<?php echo $send_money_btn;
			?>
		</div>


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
