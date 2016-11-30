<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 27/11/16
 * Time: 19:55
 */

$output = '';
$output .= '<div id="trip-no-result" class="table-header brown-head"><div class="pure-g">';
$output .= '<div class="pure-u-1-3">Création</div>';
$output .= '<div class="pure-u-1-3">Montant</div>';
$output .= '<div class="pure-u-1-3">Etat</div>';
$output .= '</div></div>';

$output .= '<div class="event-body"><div class="pure-g">';
$output .= '<div class="pure-u-1"><div class="pure-g">';
$output .= 'Aucun paiement pour le moment';
$output .= '</div></div>';
$output .= '</div></div>';

return $output;