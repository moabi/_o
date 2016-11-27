<?php

$ob_user = new online_booking_vendor();
$ux = new online_booking_ux();
$user_id = get_current_user_id();
$data = $ob_user->get_legal_documents(false);


$output = '<h1>'.get_the_title().'</h1>';


$output .= '<div class="bk-listing pure-table">';
$output .= '<div class="table-header black-head">';

$output .= '<div class="pure-g">';
$output .= '<div class="pure-u-10-24">';
$output .= '<span>Type</span>';
$output .= '</div>';
$output .= '<div class="pure-u-8-24">';
$output .= '<span>Nom</span>';//ID
$output .= '</div>';
$output .= '<div class="pure-u-4-24">';
$output .= '<span>Validation</span>';//KBIS
$output .= '</div>';
$output .= '</div>';

$output .= '</div>';

$output .= '<div class="event-body">';
//SOCIETE
$output .= '<div class="pure-g">';
$output .= '<div class="pure-u-10-24">';
$output .= '<span>Société</span>';
$output .= '</div>';
$output .= '<div class="pure-u-8-24">';
$output .= $data['cie_name'];
$output .= '</div>';
$output .= '</div>';


//Identité
$output .= '<div class="pure-g">';
$output .= '<div class="pure-u-10-24">';
$output .= '<span>Pièce d\'identité <br />(recto/verso)</span>';
$output .= '</div>';
$output .= '<div class="pure-u-8-24">';
$output .= $data['id_name'];
$output .= '</div>';

$output .= '<div class="pure-u-6-24">';
$output .= $data['identite_validation_label'];
$output .= '</div>';
$output .= '</div>';


//kBIS
$output .= '<div class="pure-g">';
$output .= '<div class="pure-u-10-24">';
$output .= '<span>KBis</span>';
$output .= '</div>';
$output .= '<div class="pure-u-8-24">';
$output .= $data['kbis_name'];
$output .= '</div>';

$output .= '<div class="pure-u-6-24">';
$output .= $data['kbis_validation_label'];
$output .= '</div>';

$output .= '</div>';


//Attestation de vigilance URSSAF
$output .= '<div class="pure-g">';
$output .= '<div class="pure-u-10-24">';
$output .= '<span>Attestation de vigilance URSSAF</span>';
$output .= '</div>';
$output .= '<div class="pure-u-8-24">';
$output .= $data['urssaf_name'];
$output .= '</div>';
$output .= '<div class="pure-u-6-24">';
$output .= $data['urssaf_validation_label'];
$output .= '</div>';
$output .= '</div>';

$output .= '</div>';
$output .= '</div>';

return $output;
