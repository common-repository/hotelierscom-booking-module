<?php

defined('ABSPATH') || exit; // Exit if accessed directly

$arrLanguages = [
    'en' => 'English',
    'nl' => 'Dutch',
    'de' => 'German',
    'fr' => 'French',
    'it' => 'Italian',
    'es' => 'Spanish',
];
$strNonceFieldName = $strNonceAction = HTLRSBM_PLUGIN_PREFIX.'update_translations';
$arrTranslations = htlrsbm_translate();
$arrUserTranslations = htlrsbm_get_option($strOption = 'translations',[]);

// Load the options
if (isset($_POST) && !empty($_POST)) {
    switch (true) {
        case current_user_can('manage_options') === false:
            add_settings_error('','','You do not have the rights to change these settings.');
            break;
        case !isset($_POST[$strNonceFieldName]) || wp_verify_nonce($_POST[$strNonceFieldName],$strNonceAction) === false:
            add_settings_error('','','Failed to verify nonce, please try to save your translations one more time.');
            break;
        default:
            htlrsbm_update_option($strOption,array_intersect_key($_POST,array_flip(array_keys($arrTranslations))));
            $arrTranslations = htlrsbm_translate();

            ?><div class="notice notice-success is-dismissible">
                <p>Translations updated successfully</p>
            </div><?php
            break;
    }

    settings_errors();
}

?><style type="text/css">
    .form-table td {
        vertical-align: top;
    }
    .form-table--nested {
        border-spacing: 10px;
        border-collapse: collapse;
    }
    .form-table--nested thead td {
        border: none;
        padding: 2px 4px;
    }
    .form-table--nested td {
        padding: 2px 0;
    }
    .form-table--nested td:not(:last-child) {
        padding-right: 10px;
    }
</style>

<div class="wrap">
    <h2>Translations</h2>

    <form method="post" class="form-table">
        <table class="widefat">
            <tr>
            <?php foreach ($arrLanguages as $strLanguageCode => $strLanguageName): ?>
                <td>
                    <label><b><?php echo $strLanguageName; ?></b></label>
                </td>
            <?php endforeach; ?>
            </tr>
        <?php foreach ($arrTranslations as $strKey => $arrLanguageTranslations): ?>
            <tr>
            <?php foreach ($arrLanguageTranslations as $strLanguage => $strText): ?>
                <td>
                    <input type="text" name="<?php echo $strName = "{$strKey}[{$strLanguage}]"; ?>" id="<?php echo $strName; ?>" value="<?php echo isset($arrUserTranslations[$strLanguage][$strKey]) ? $arrUserTranslations[$strLanguage][$strKey] : $strText; ?>">
                </td>
            <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
            <tr valign="top">
                <td scope="row" class="settingInput" id="bottomBorderNone"></td>
                <td id="bottomBorderNone"></td>
            </tr>
        </table>
        <?php submit_button(); ?>
        <?php wp_nonce_field($strNonceAction,$strNonceFieldName); ?>
    </form>
</div>
