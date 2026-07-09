//Facebook Sdk init
window.fbAsyncInit = function() {
	FB.init({
	  appId      : xoo_sl_fb_localize.appID,
	  cookie     : true,  // enable cookies to allow the server to access 
	                      // the session
	  xfbml      : true,  // parse social plugins on this page
	  version    : 'v2.8' // use graph api version 2.8
	});
}; 



// Load the SDK asynchronously
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "https://connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


jQuery(document).ready(function($){

	$('body').on('click', '.xoo-sl-facebook-btn', function(){

		var $button = $(this);

		FB.login(function(response) {

			if (response.status === 'connected') {
		  		getData( $button );
		  	}
		  	else{

		  	}
		}, {scope: 'public_profile,email'});

	})

	function getData( $button ) {

	    FB.api('/me?fields=id,name,email,first_name,last_name', function(response) {
	    	response.social_type = "facebook";
	    	xoo_sl_localize.sendUserInfo( response, $button );	  	
	    });
	 }

});