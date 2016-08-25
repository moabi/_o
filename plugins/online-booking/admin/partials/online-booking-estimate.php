<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http//little-dream.fr
 * @since      1.0.0
 *
 * @package    Online_Booking
 * @subpackage Online_Booking/admin/partials
 */
?>
<link rel="stylesheet" href="http//yui.yahooapis.com/pure/0.6.0/pure-min.css">
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php add_thickbox(); ?>

<h1>Online Booking</h1>



<h2>Etats</h2>
<table class="wp-list-table widefat  striped ">
	<thead>
		<th>Commande</th>
		<th>Acteur</th>
		<th>Emails envoyés</th>
		<th>Page mon compte</th>
		<th>Action</th>
		
	</thead>

	<tr>
		<td><strong>Création </strong> </td>
		<td>Client</td>
		<td></td>
		<td>Ajout dans compte client</td>
		<td>Enregistrement en base de donnée</td>
	</tr>
	<tr>
		<td><strong>Supprimé </strong> </td>
		<td>Client</td>
		<td></td>
		<td>Suppression dans compte client</td>
		<td>Garder en base ? (retargeting)</td>
	</tr>
	<tr>
		<td><strong>Modifié </strong> </td>
		<td>Client</td>
		<td></td>
		<td>Modification dans compte client</td>
		<td></td>
	</tr>
	
	<tr>
		<td><strong>Envoyé </strong> </td>
		<td>client</td>
		<td>client,administrateur</td>
		<td></td>	
	</tr>
	<tr>
		<td><strong>Validé </strong> </td>
		<td>Admininistrateur</td>
		<td>Client</td>
		<td>Modification dans compte client</td>
		<td>
			Verrouillage du devis (non modifiable par le client)
		</td>
	</tr>
	<tr>
		<td><strong>En facturation </strong> </td>
		<td>Admininistrateur</td>
		<td>Client</td>
		<td>Modification dans compte client</td>
		<td></td>
	</tr>
	<tr>
		<td><strong>Payé </strong> </td>
		<td>Admininistrateur</td>
		<td>Client</td>
		<td>Modification dans compte client</td>
		<td></td>
	</tr>
	<tr>
		<td><strong>Annulation </strong> </td>
		<td>Admininistrateur</td>
		<td>Client</td>
		<td>Modification dans compte client</td>
		<td></td>
	</tr>

</table>