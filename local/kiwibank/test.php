<?php

global $CFG;


require ('/var/www/staging-kbme.kiwibank.co.nz/local/kiwibank/classes/totara_sync.php');

$loader= new totara_sync();
$loader->upload_feedfiles();


?>
