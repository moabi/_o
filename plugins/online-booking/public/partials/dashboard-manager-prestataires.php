<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 01/10/16
 * Time: 11:08
 */

?>

<?php
$pm = new OnlineBookingProjectManager();
echo $pm->get_vendors_affiliated(); ?>
<h2>Activités disponibles</h2>
<?php
echo $pm->get_activities();
//do_action('manager-prestataires');