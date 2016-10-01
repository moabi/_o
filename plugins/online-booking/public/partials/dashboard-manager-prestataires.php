<?php
/**
 * Created by PhpStorm.
 * User: david1
 * Date: 01/10/16
 * Time: 11:08
 */

$pm = new \projectmanager\OnlineBookingProjectManager();
echo $pm->get_vendors_affiliated();
do_action('manager-prestataires');