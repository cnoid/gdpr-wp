jQuery(document).ready(function($) {
    var isConsentGiven = false;

    function fetchBannerViaAjax() {
        $.post(ajaxurl, {'action': 'fetch_gdpr_banner'}, function(response) {
            $('#gdpr-banner-placeholder').html(response);
            bindAcceptDismissEvents();
        });
    }

    function bindAcceptDismissEvents() {
        var acceptButton = document.querySelector('.accept-cookies');
        var dismissButton = document.querySelector('.dismiss-cookies');

        acceptButton && acceptButton.addEventListener('click', function() {
            document.querySelector('.gdpr-cookie-consent').style.display = 'none';
            document.cookie = 'gdpr_cookie_consent=accepted; path=/; max-age=' + (10 * 365 * 24 * 60 * 60);
            isConsentGiven = true;
        });

        dismissButton && dismissButton.addEventListener('click', function() {
            document.querySelector('.gdpr-cookie-consent').style.display = 'none';
            document.cookie = 'gdpr_cookie_consent=dismissed; path=/';
            document.cookie = 'gdpr_cookie_dismissed_time=' + Date.now() + '; path=/; max-age=' + (7 * 24 * 60 * 60);
            isConsentGiven = true;
        });
    }

    if (!isConsentGiven) {
        fetchBannerViaAjax();
    }
});
