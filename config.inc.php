<?php

// If fou want to use an extenal jappix, please provide jappix url
$rcmail_config['jappix_embedded'] = true;
$rcmail_config['jappix_url'] = 'https://jappix.com';

// Enables full jappix mode
$rcmail_config['jappix_task'] = true;

// Show jappix in iframe
$rcmail_config['jappix_iframe'] = true;

// Use roundcube creditionals (enables autologin)
$rcmail_config['jappix_use_autologin'] = true;


// =========== Autologin configuration ===========


// Username portion of XMPP username (bare JID), example: "user"
// if this contains @, this will only take the part before @,
// and the part after @ will replace the hostname definition above.
$rcmail_config['jappix_auto_username'] = 
	#$_SESSION['username'];
	preg_replace('/@.*$/', '', $_SESSION['username']);


// Hostname portion of XMPP username (bare JID), example: "example.net"
$rcmail_config['jappix_auto_domain'] = 
	#"example.net";
	preg_replace('/^.*@/', '', $_SESSION['username']);


?>
