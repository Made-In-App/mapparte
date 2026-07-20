jQuery(document).ready(function ($) {

    var googleInitialized = false;
    var activeButton = null;

    function redirectAfterLogin(response, $button) {
        if (response.message) {
            $('.xoo-sl-notice-container').html(response.message);
        }

        if (response.success !== 'true') {
            return;
        }

        var redirectTo = xoo_sl_localize.redirect_to;
        var $easyLoginSection = $button.parents('.xoo-el-section');
        if ($easyLoginSection.length && $easyLoginSection.find('input[name="xoo_el_redirect"]').length) {
            redirectTo = $easyLoginSection.find('input[name="xoo_el_redirect"]').val();
        }

        var separator = redirectTo.indexOf('?') === -1 ? '?' : '&';
        redirectTo += separator + 'xoo_sl_login=1&_ts=' + Date.now();
        setTimeout(function () {
            window.location.replace(redirectTo);
        }, 300);
    }

    function handleCredentialResponse(response) {
		var $button = activeButton || $('.xoo-sl-google-btn[data-xoo-sl-google-rendered="1"]:visible').first();
        $.ajax({
            url: xoo_sl_google_localize.adminurl,
            type: 'POST',
            data: {
                action: 'xoo_sl_google_data',
                credential: response.credential,
                security: xoo_sl_google_localize.nonce
            },
            success: function (loginResponse) {
                redirectAfterLogin(loginResponse, $button);
                $(document).trigger('xoo_sl_processing_userinfo', [loginResponse]);
            }
        });
    }

    function renderGoogleButtons() {
        if (!googleInitialized || typeof google === 'undefined' || !google.accounts || !google.accounts.id) {
            return;
        }

        $('.xoo-sl-google-btn').each(function () {
            var $button = $(this);
            if ($button.attr('data-xoo-sl-google-rendered') === '1') {
                return;
            }

            $button.attr('data-xoo-sl-google-rendered', '1').empty();
            google.accounts.id.renderButton(this, {
                type: 'standard',
                theme: 'filled_blue',
                size: 'large',
                text: 'continue_with',
                shape: 'rectangular',
                width: Math.max(180, Math.round($button.outerWidth() || 220))
            });
            $button.on('click', function () {
                activeButton = $button;
            });
        });
    }

    function startGoogleIdentity() {
        if (typeof google === 'undefined' || !google.accounts || !google.accounts.id) {
            setTimeout(startGoogleIdentity, 200);
            return;
        }

        google.accounts.id.initialize({
            client_id: xoo_sl_google_localize.clientID,
            callback: handleCredentialResponse,
            ux_mode: 'popup',
            use_fedcm_for_prompt: true
        });
        googleInitialized = true;
        renderGoogleButtons();
    }

    startGoogleIdentity();

    if (typeof MutationObserver !== 'undefined') {
        var observer = new MutationObserver(renderGoogleButtons);
        observer.observe(document.body, {childList: true, subtree: true});
    }

    $(document).on('click', '.xoo-el-login-tgr', function () {
        setTimeout(renderGoogleButtons, 100);
    });
});
