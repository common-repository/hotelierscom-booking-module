<?php
/*
Plugin Name: Hoteliers.com Booking Module
Plugin URI: http://company.hoteliers.com/en/solutions/booking-engine/
Description: Add the Hoteliers.com booking engine code to your pages. This plugin lets you place the booking engine in your widgets.
Version: 1.10.1
Author: Hoteliers.com
Author URI: https://www.hoteliers.com/
Text Domain: hotelierscom-booking-module
*/

$arrWpUploadDir = wp_upload_dir();
defined($strKey = 'HTLRSBM_PLUGIN_FILE') || define($strKey,__FILE__,true);
defined($strKey = 'HTLRSBM_PLUGIN_PREFIX') || define($strKey,'htlrsbm_',true);

defined($strKey = 'HTLRSBM_PLUGIN_DIR') || define($strKey,untrailingslashit(dirname(HTLRSBM_PLUGIN_FILE)),true);
defined($strKey = 'HTLRSBM_PLUGIN_BASENAME') || define($strKey,basename(HTLRSBM_PLUGIN_DIR),true);
defined($strKey = 'HTLRSBM_PLUGIN_UPLOAD_BASEDIR') || define($strKey,$arrWpUploadDir['basedir'].DIRECTORY_SEPARATOR.HTLRSBM_PLUGIN_BASENAME,true);
defined($strKey = 'HTLRSBM_PLUGIN_UPLOAD_BASEURL') || define($strKey,$arrWpUploadDir['baseurl'].DIRECTORY_SEPARATOR.HTLRSBM_PLUGIN_BASENAME,true);

if (version_compare(PHP_VERSION,$strMinVersion = '5.6','<') === true) {
    add_action('admin_notices',function() use($strMinVersion) {
        echo '<div class="error"><p>'.__("Hoteliers.com Booking Module requires PHP {$strMinVersion} to function properly. Please upgrade PHP. The Plugin has been auto-deactivated.",HTLRSBM_PLUGIN_BASENAME).'</p></div>';
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
    });

    add_action('admin_init',function() {
        deactivate_plugins(plugin_basename(HTLRSBM_PLUGIN_FILE));
    });
} else {
    require HTLRSBM_PLUGIN_DIR.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
    require HTLRSBM_PLUGIN_DIR.DIRECTORY_SEPARATOR.'functions.php';
    require HTLRSBM_PLUGIN_DIR.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'menu.php';
    require HTLRSBM_PLUGIN_DIR.DIRECTORY_SEPARATOR.'widget.php';
    require HTLRSBM_PLUGIN_DIR.DIRECTORY_SEPARATOR.'template.php';
}
