<?php
/**
* Plugin Name: GDPR Banner
* Plugin URI: https://github.com/cnoid/gdpr-wp
* Description: Shows a Cookie Consent banner. Customized for DailyAI. Workaround for Caching issues included.
* Version: 2.1.4
* Author: Mimmikk.
* Author URI: https://github.com/cnoid
* License: GPL2
*/

// Enqueue styles and scripts
function gdpr_enqueue_scripts() {
    wp_enqueue_style('gdpr-cookie-consent-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('gdpr-cookie-consent-script', plugin_dir_url(__FILE__) . 'gdpr-script.js', array('jquery'), false, true);
    wp_localize_script('gdpr-cookie-consent-script', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('wp_enqueue_scripts', 'gdpr_enqueue_scripts');


// Placeholder for banner
function gdpr_display_banner() {
    echo "<div id='gdpr-banner-placeholder'></div>";
}
add_action('wp_footer', 'gdpr_display_banner');

// AJAX handler for fetching the banner
function fetch_gdpr_banner() {
    if (isset($_COOKIE['gdpr_cookie_consent'])) {
        $consent = $_COOKIE['gdpr_cookie_consent'];
        if ($consent === 'accepted') {
            wp_die();
        } elseif ($consent === 'dismissed') {
            $dismissed_time = $_COOKIE['gdpr_cookie_dismissed_time'];
            if (time() - $dismissed_time < 604800) {
                wp_die();
            }
        }
    }
    include 'banner.php';
    wp_die();
}
add_action('wp_ajax_fetch_gdpr_banner', 'fetch_gdpr_banner');
add_action('wp_ajax_nopriv_fetch_gdpr_banner', 'fetch_gdpr_banner');

