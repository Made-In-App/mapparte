jQuery(document).ready(function($){

	//Attaching global function for sdks to send data to server
	window.xoo_sl_localize.sendUserInfo = function( userInfo, $button ){

		if( !userInfo.email ){// If unsucessful request
			console.log(userInfo);
			return;
		}

		var $easyLoginSection = $button.parents('.xoo-el-section');

		$.ajax({
	        url: xoo_sl_localize.adminurl,
	        type: 'POST',
	        data: {
	          action: 'xoo_sl_fb_data',
	          userInfo: userInfo
	        },
	        success: function(response){

	        	if( response.message ){
	        		$('.xoo-sl-notice-container').html(response.message);
	        	}

	        	if( response.success === "true" ){

	        		var redirectTo = xoo_sl_localize.redirect_to;

	        		if( $easyLoginSection.length && $easyLoginSection.find('input[name="xoo_el_redirect"]').length ){
	        			redirectTo = $easyLoginSection.find('input[name="xoo_el_redirect"]').val();
	        		}

	        		// Force fresh page after social login to avoid stale cached guest page.
	        		// Small delay helps cookie/session propagation on some hosts/caches.
	        		var separator = redirectTo.indexOf('?') === -1 ? '?' : '&';
	        		redirectTo = redirectTo + separator + 'xoo_sl_login=1&_ts=' + Date.now();
	        		setTimeout(function(){
	        			window.location.replace(redirectTo);
	        		}, 900);
	        	}

	        	$(document).trigger('xoo_sl_processing_userinfo',[response]);
	        }
	    })
	}



	/*$(document).on( 'xoo_sl_processing_userinfo', function(event,response){

		if( !response.register || response.register !== "yes" || response.userData.length === 0) return; //exit if register is false
		var userData = response.userData;
		$('.xoo-el-form-container input[name="xoo_el_reg_email"]').val(userData.email); //Email
		$('.xoo-el-form-container input[name="xoo_el_reg_fname"]').val(userData.first_name); //FirstName
		$('.xoo-el-form-container input[name="xoo_el_reg_lname"]').val(userData.last_name); //Last Name

		//Trigger Register section
		$('.xoo-el-reg-tgr').trigger('click');;
	})*/

})