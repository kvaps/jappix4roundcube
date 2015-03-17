jQuery(document).ready(function() {
//jQuery(document).ajaxSetup({ cache: true });
//jQuery(document).getScript("https://static.jappix.com/server/get.php?l=en&t=js&g=mini.xml", function() {
     JappixMini.launch({
        connection: {
           domain: rcmail.env.jabber_domain,
	   user: rcmail.env.jabber_username,
           password: rcmail.env.jabber_password,
        },

        application: {
           network: {
              autoconnect: false,
           },

           interface: {
              showpane: true,
              animate: false,
           },

           user: {
              random_nickname: false,
           },
        },
     });
//  });
  
	

});
