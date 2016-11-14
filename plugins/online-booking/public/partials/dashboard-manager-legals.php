<?php

$ob_user = new online_booking_vendor();
$ux = new online_booking_ux();
$user_id = get_current_user_id();
?>



<h1><?php echo get_the_title(); ?></h1>

<div class="legals-infos">
  <div class="legals-title">Pourquoi nous vous demandons ces documents ?</div>
  <ul>
    <li>Notre opérateur de paiement Mangopay, la filiale e-commerce du Crédit Mutuel, a pour obligation légale de vérifier vos informations dans le cadre de la lutte contre le blanchiment d'argent.</li>
    <li>Les organisateurs doivent également disposer de ces informations pour sécuriser leurs évènements en cas de problème et de grarantir que vous êtes à jour au niveau des obligations légales (assurance RC, agrément, ...)</li>  
  </ul>
  <strong>Des questions ?</strong>
  <p>Appellez nous au <b>0 826 81 10 12</b> ou contactez nous par mail: <a href="mailto:contact@onlyoo.fr">contact@onlyoo.fr</a>
</div>


<?php echo $ob_user->get_legal_documents(false); ?>
