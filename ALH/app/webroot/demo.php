<?php

/**
* Eventful Demo
* @author James Brooks <james@bluebaytravel.co.uk>
* @version 0.0.1
*/

require_once "Eventful_new.php";

$AppKey = "qGt8LCwr2CVprzVW";

$eV = new Eventful($AppKey);

$evLogin = $eV->login('sachint', 'sachint');
if($evLogin) {
$evArgs = array(
'location' => 'Mexico'
);

$cEvent = $eV->call('events/search', $evArgs);

echo "<pre>" . print_r($cEvent, true) . "</pre>";
}else{
die("<strong>Error logging into Eventful API</strong>");
}


?>