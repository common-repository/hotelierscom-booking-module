<?php

defined('ABSPATH') || exit; // Exit if accessed directly

if (!function_exists('htlrsbm_page_settings')) {
    function htlrsbm_page_settings() {
        require __DIR__.DIRECTORY_SEPARATOR.'settings.php';
        require __DIR__.DIRECTORY_SEPARATOR.'footer.php';
    }
}

if (!function_exists('htlrsbm_page_translations')) {
    function htlrsbm_page_translations() {
        require __DIR__.DIRECTORY_SEPARATOR.'translations.php';
        require __DIR__.DIRECTORY_SEPARATOR.'footer.php';
    }
}

add_action('admin_menu',function() {
    add_menu_page(
        'Hoteliers.com Booking Module - Settings',
        'Booking Module',
        'manage_options',
        'htlrs_booking_module__settings',
        'htlrsbm_page_settings',
        plugins_url('images/logo.png',HTLRSBM_PLUGIN_FILE)
    );
    add_submenu_page(
        'htlrs_booking_module__settings',
        'Hoteliers.com Booking Module - Settings',
        'Settings',
        'manage_options',
        'htlrs_booking_module__settings',
        'htlrsbm_page_settings'
    );
    add_submenu_page(
        'htlrs_booking_module__settings',
        'Hoteliers.com Booking Module - Settings',
        'Translations',
        'manage_options',
        'htlrs_booking_module__translations',
        'htlrsbm_page_translations'
    );

    wp_register_script($strKey = HTLRSBM_PLUGIN_PREFIX.'_colorpicker_scripts',plugins_url('admin/colorpicker/js/colorpicker.js',HTLRSBM_PLUGIN_FILE));
    wp_enqueue_script($strKey);

    wp_register_style(HTLRSBM_PLUGIN_PREFIX.'_colorpicker_styles',plugins_url('admin/colorpicker/css/colorpicker.css',HTLRSBM_PLUGIN_FILE));
    wp_enqueue_style(HTLRSBM_PLUGIN_PREFIX.'_colorpicker_styles');
});
