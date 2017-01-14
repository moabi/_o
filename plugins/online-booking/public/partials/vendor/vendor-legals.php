<?php

$ob_user = new online_booking_vendor();
$ux = new online_booking_ux();
$user_id = get_current_user_id();
$data = $ob_user->get_legal_documents($user_id);
$vendor_legal_form = get_option('ob_legals_vendor_shortcode');



$output = '';
$output .= '<div class=" pure-table">';
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

$output .= '<div class="event-body-row">';
//SOCIETE
$output .= '<div class="pure-g" style="border-bottom: 1px solid #ccc;padding: .7em;">';
$output .= '<div class="pure-u-10-24">';
$output .= '<span>Société</span>';
$output .= '</div>';
$output .= '<div class="pure-u-8-24">';
$output .= $data['cie_name'];
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';

$output .= '<div class="event-body-row">';

//Identité
$output .= '<div class="pure-g" style="border-bottom: 1px solid #ccc;padding: .7em;">';
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
$output .= '</div>';

$output .= '<div class="event-body-row">';

//kBIS
$output .= '<div class="pure-g" style="border-bottom: 1px solid #ccc;padding: .7em;">';
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
$output .= '</div>';

$output .= '<div class="event-body-row">';

//Attestation de vigilance URSSAF
$output .= '<div class="pure-g" style="border-bottom: 1px solid #ccc;padding: .7em;">';
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

$output .= do_shortcode($vendor_legal_form);

$output .= "<style>.event-body-row {
    background: #fff;
    margin-bottom: 4px;
    border: none;
    text-align: center;
    font-size: 14px;
}</style>";

return $output;
