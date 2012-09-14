jQuery(document).ready(function() {
	launchMini(true, false, rcmail.env.jabber_domain, rcmail.env.jabber_username, rcmail.env.jabber_password);
	disconnectMini();
	jQuery('#jappix_mini').hide();
});
