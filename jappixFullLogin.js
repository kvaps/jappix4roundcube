jQuery(document).ready(function() {
	disconnectMini();
	doHttpLogin(rcmail.env.jabber_username, rcmail.env.jabber_password, rcmail.env.jabber_domain);
});
