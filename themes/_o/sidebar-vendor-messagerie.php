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
		<?php
		echo $class_ux->get_avatar_form(141);
		?>

		<div class="profile-info">
			<?php
			echo get_user_meta($user_id,'first_name',true);
			echo ' '.get_user_meta($user_id,'last_name',true);
			?>


		</div>
		<div class="blue-bg ca">
			<i class="fa fa-life-ring" aria-hidden="true"></i> AIDE <br>

			<strong>Puis-je appeler le chef de projet ?</strong>

			<p>Oui ! Vous pouvez convenir avec le chef de projet d'une rencontre ou d'un appel

			téléphonique via cette conversation pour échanger sur le projet proposé. Nous vous

			conseillons de maintenir au maximum vos échanges avec le chef de projet dans le

			module de messagerie Onlyoo pour garder un historique et sécuriser votre mission.</p>
			<br>

			<strong>Comment suis je payé ?
			</strong>
			<p>Une fois votre réservation validé sur Onlyoo, le client l’accepte puis la prépaie. Les fonds

			sont bloqués sur son compte Onlyoo, cela vous garantit d’être payé en fin de mission.

			Une fois la prestation terminée, Onlyoo débloque les fonds et vous recevez l’argent en

			48h.</p>
			<br>

			<strong>Quels sont les frais Onlyoo ?</strong>

			<p>Les frais Onlyoo sont indiqués clairement lorsque vous recevez votre réservation. Ils sont

			indiqués dans votre "compte / partie Mes paiements" .</p>

			<br>
			<strong>
			Puis je modifié la réservation une fois validé ?</strong>

			<p>Non, vous ne pouvez pas modifier votre réservation après validation, mais vous pouvez

			avant validation de la réservation proposer un autre horaire disponible qui sera proposé

			au chef de projet qui donnera ainsi son accord .</p>
			<br>

			<strong>Des questions ?</strong>

			<p>Contactez nous</p>
			<ul>
				<li>par téléphone au 0826 81 10 12</li>
				<li>ou par mail à partenaire@onlyoo.fr</li>
			</ul>


		</div>


	</div>

	<div class="white-block smile-bg">

		<div class="padd-l">

		<h2>Onlyoo sécurise vos réservations</h2>

		<strong>Vérifications administratives</strong>

		<p>Nous vérifions la solvabilité de tous les clients . Vos échanges, devis, factures sont archivés et

		protégés .</p>
			<br>

		<strong>Gestion des événements</strong>

		<p>Nous nous occupons de la gestion des événements via votre chef de projet dédié . Dès qu'un

		événement est validé vous recevez une demande de réservation par SMS et Email pour la valider .

		Dès que vous validez votre réservation vous recevrez votre Brief avec toutes les informations à

		connaître avant l'arrivée des participants .</p>
			<br>

		<strong>Budget, Paiement sécurisé</strong>

		<p>Nous vous permettons d'être payer par virement bancaire complètement sécurisée et sans frais

		supplémentaire.</p>
			<br>

		<strong>Garantie sélection des chefs de projet</strong>


		<p>Nous avons une équipe dédié et une sélection des chefs de projets externe locaux qui répondent à

		des critères de qualité très exigeants. Ce sont tous des coordinateurs sérieux spécialisées dans la

		réception de groupe de loisir .</p>
		</div>
	</div>
</div>
