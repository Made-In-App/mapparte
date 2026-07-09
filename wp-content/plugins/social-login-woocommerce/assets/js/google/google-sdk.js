jQuery(document).ready(function ($) {

    var auth2;

    function handleGoogleSuccess(googleUser, $button) {
        var profile = googleUser.getBasicProfile();
        var userInfo = {
            social_type: 'google',
            email: profile.getEmail(),
            first_name: profile.getGivenName(),
            last_name: profile.getFamilyName(),
            id: profile.getId(),
            name: profile.getName()
        };
        xoo_sl_localize.sendUserInfo(userInfo, $button); // Send data to server
    }

    function bindGoogleButtons() {
        if (!auth2) return;
        $('.xoo-sl-google-btn').each(function () {
            var $button = $(this);
            if ($button.data('xooSlGoogleBound')) return;
            $button.data('xooSlGoogleBound', true);
            auth2.attachClickHandler(
                this,
                {},
                function (googleUser) {
                    handleGoogleSuccess(googleUser, $button);
                }
            );
        });
    }

    function startApp() {
        if (typeof gapi === 'undefined') {
            setTimeout(startApp, 300);
            return;
        }
        gapi.load('auth2', function () {
            auth2 = gapi.auth2.init({
                client_id: xoo_sl_google_localize.clientID,
                cookiepolicy: 'single_host_origin',
                scope: 'profile email'
            });
            bindGoogleButtons();
        });
    }

    startApp();

    // Easy Login popup renders buttons dynamically: bind again when DOM changes.
    if (typeof MutationObserver !== 'undefined') {
        var observer = new MutationObserver(function () {
            bindGoogleButtons();
        });
        observer.observe(document.body, { childList: true, subtree: true });
    }

    // Also re-check after opening login modal.
    $(document).on('click', '.xoo-el-login-tgr', function () {
        setTimeout(bindGoogleButtons, 300);
        setTimeout(bindGoogleButtons, 900);
    });

})