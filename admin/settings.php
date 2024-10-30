<?php

defined('ABSPATH') || exit; // Exit if accessed directly

$arrAlignments = ['horizontal','vertical',];
$strNonceFieldName = $strNonceAction = HTLRSBM_PLUGIN_PREFIX.'update_settings';

foreach ($arrOptions = [
    htlrsbm_option_name($strOptAlignment = 'alignment'),
    htlrsbm_option_name($strOptButtonBgColor = 'button_background_color'),
    htlrsbm_option_name($strOptButtonTxtColor = 'button_text_color'),
    htlrsbm_option_name($strOptChainId = 'chain_id'),
    htlrsbm_option_name($strOptCustomCss = 'custom_css'),
    htlrsbm_option_name($strOptDefaultPromoCode = 'default_promotion_code'),
    htlrsbm_option_name($strOptGoogleAnalyticsCode = 'google_analytics_code'),
    htlrsbm_option_name($strOptHotels = 'hotels') => [],
    htlrsbm_option_name($strOptBookingEngineReminder = 'booking_engine_reminder'),
    htlrsbm_option_name($strOptPromoCode = 'promotion_code'),
] as $mixKey => $mixValue) {
    $mixOption = (is_string($mixKey) ? $mixKey : $mixValue);
    $mixDefault = (is_string($mixKey) ? $mixValue : false);
    ${$mixOption} = htlrsbm_get_option($mixOption,$mixDefault);
    ${$mixOption} = is_numeric(${$mixOption}) ? ${$mixOption}+0 : ${$mixOption};
}

// Load the options
if (isset($_POST) && !empty($_POST)) {
    switch (true) {
        case current_user_can('manage_options') === false:
            add_settings_error('','','You do not have the rights to change these settings.');
            break;
        case !isset($_POST[$strNonceFieldName]) || wp_verify_nonce($_POST[$strNonceFieldName],$strNonceAction) === false:
            add_settings_error('','','Failed to verify nonce, please try to save your settings one more time.');
            break;
        default:
            $_POST = stripslashes_deep($_POST);
            foreach ($arrOptions as $mixKey => $mixValue) {
                $strOption = (is_string($mixKey) ? $mixKey : $mixValue);
                ${$strOption} = (isset($_POST[$strOption]) ? $_POST[$strOption] : ${$strOption});
                ${$strOption} = is_numeric(${$strOption}) ? ${$strOption}+0 : ${$strOption};

                switch ($strOption) {
                    case htlrsbm_option_name($strOptHotels):
                        $arrHotels = [];
                        foreach ($_POST[$strHotelId = htlrsbm_option_name('hotel_id')] as $intKey => $mixValue) {
                            if (empty($mixValue)) {
                                continue;
                            }
                            $arrHotels[$intHotelId = abs((int)$mixValue)] = trim(isset($_POST[$strKey = htlrsbm_option_name('hotel_name')][$intKey]) ? $_POST[$strKey][$intKey] : '');
                        }
                        ${$strOption} = $arrHotels;
                        break;
                    case htlrsbm_option_name($strOptCustomCss):
                        if (!is_dir($strUploadDir = HTLRSBM_PLUGIN_UPLOAD_BASEDIR)) {
                            wp_mkdir_p($strUploadDir);
                        }
                        file_put_contents($strUploadDir.DIRECTORY_SEPARATOR.'custom.css',(new CSSmin())->run(${$strOption}));
                        break;
                    default:
                        break;
                }

                $mixValue = ${$strOption};
                switch (true) {
                    case is_array($mixValue):
                        break;
                    case is_numeric(trim($mixValue)):
                        $mixValue = $mixValue + 0;
                        break;
                    case is_string($mixValue):
                        $mixValue = trim($mixValue);
                        break;
                    default:
                        break;
                }
                htlrsbm_update_option($strOption, $mixValue);
            }

            ?><div class="notice notice-success is-dismissible">
                <p>Settings updated successfully</p>
            </div><?php
            break;
    }

    settings_errors();
}

?><script type="text/javascript">
    jQuery(document).ready(function() {
        var $addHotel = jQuery('.js-btn--add-hotel-row'),
            $hotels = jQuery('.js-hotels'),
            intAmountHotels = 0,
            strClick = 'click';

        jQuery('.color').each(function() {
            var $color = jQuery(this),
                inputEl = $color.attr('rel');
            $color.ColorPicker({
                onChange: function(hsb,hex,rgb,el) {
                    jQuery('#'+inputEl).val(hex);
                    jQuery(el).ColorPickerHide();
                },
                onBeforeShow: function() {
                    var colorvalue = jQuery('#'+inputEl).val();
                    jQuery(this).ColorPickerSetColor(colorvalue);
                }
            })
        });

        $addHotel.on(strClick,function(objEvent,objData) {
            objEvent.preventDefault();
            intAmountHotels++;
            objData = (objData || {});

            $hotels.append(
                jQuery('<tr/>')
                    .addClass('js-hotels__single-hotel')
                    .append(
                        jQuery('<td/>').append(
                            jQuery('<input/>')
                                .attr('name','<?php echo htlrsbm_option_name('hotel_id'); ?>['+intAmountHotels+']')
                                .attr('placeholder','hotel id')
                                .attr('type','text')
                                .val(objData.id || '')
                        )
                    )
                    .append(
                        jQuery('<td/>').append(
                            jQuery('<input/>')
                                .attr('name','<?php echo htlrsbm_option_name('hotel_name'); ?>['+intAmountHotels+']')
                                .attr('placeholder','hotel name')
                                .attr('type','text')
                                .val(objData.name || '')
                        )
                    )
                    .append(
                        jQuery('<td/>').append(
                            jQuery('<button/>')
                                .addClass('js-btn--remove-hotel-row')
                                .html('-')
                        )
                    )
            );
        });

        jQuery(document).on(strClick,'.js-btn--remove-hotel-row',function(objEvent) {
            objEvent.preventDefault();
            jQuery(this).parents('.js-hotels__single-hotel').remove();
        });

    <?php if (($strOptionName = htlrsbm_option_name('hotels')) && empty(${$strOptionName})): ?>
        $addHotel.trigger(strClick);
    <?php else: ?>
    <?php foreach (${$strOptionName} as $intHotelId => $strHotelName): ?>
        $addHotel.trigger('click',{
            id: <?php echo $intHotelId?>,
            name: '<?php echo esc_js($strHotelName); ?>',
        });
    <?php endforeach; ?>
    <?php endif; ?>
    });
</script>

<style type="text/css">
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
    <h2>Settings</h2>

    <form method="post" class="form-table">
        <table class="widefat">
            <tr>
                <td scope="row" class="settingInput" id="">
                    <label for="<?php echo ($strOptionName = htlrsbm_option_name($strOptChainId)); ?>">Chain ID</label>
                </td>
                <td>
                    <input type="text" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>" maxlength="6" size="8" value="<?php echo ${$strOptionName}; ?>">
                </td>
            </tr>
            <tr valign="top">
                <td scope="row" class="settingInput" id="">
                    <label>Hotels</label>
                </td>
                <td id="">
                    <table class="form-table--nested">
                        <thead>
                            <tr>
                                <td>Hotel ID</td>
                                <td>Hotel name</td>
                                <td>&nbsp;</td>
                            </tr>
                        </thead>
                        <tbody class="js-hotels">

                        </tbody>
                    </table>
                    <button class="js-btn--add-hotel-row">Add hotel</button>
                </td>
            </tr>
            <tr>
                <td scope="row" class="settingInput" id="">
                    <label for="<?php echo ($strOptionName = htlrsbm_option_name($strOptGoogleAnalyticsCode)); ?>">Google Analytics code</label>
                </td>
                <td>
                    <input type="text" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>" maxlength="15" size="16" value="<?php echo ${$strOptionName}; ?>">
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
                    <label for="<?php echo ($strOptionName = htlrsbm_option_name($strOptAlignment)); ?>">Alignment</label>
                </td>
                <td>
                    <select id="<?php echo $strOptionName; ?>" name="<?php echo $strOptionName; ?>">
                    <?php foreach ($arrAlignments as $strAlignment): ?>
                        <option value="<?php echo $strAlignment; ?>" <?php echo (${$strOptionName} === $strAlignment ? 'selected="selected"' : ''); ?>><?php echo $strAlignment; ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
                    <label for="<?php echo ($strOptionName = htlrsbm_option_name($strOptBookingEngineReminder)); ?>">Show booking engine reminder</label>
                </td>
                <td>
                    <input type="hidden" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>-0" value="0">
                    <input type="checkbox" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>-1" value="1" <?php echo (in_array(${$strOptionName},[1,false,],true) ? 'checked="checked"' : ''); ?>>
                </td>
            </tr>
            <tr valign="top">
                <td scope="row" class="settingInput" id="">
                    <label for="<?php echo ($strOptionName = htlrsbm_option_name($strOptPromoCode)); ?>">Show promotion code</label>
                </td>
                <td>
                    <input type="hidden" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>-0" value="0">
                    <input type="checkbox" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>-1" value="1" <?php echo (${$strOptionName} === 1 ? 'checked="checked"' : ''); ?>>
                </td>
            </tr>
            <tr>
                <td scope="row" class="settingInput" id="">
                    <label for="<?php echo ($strOptionName = htlrsbm_option_name($strOptDefaultPromoCode)); ?>">Default promotion code</label>
                </td>
                <td>
                    <input type="text" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>" maxlength="15" size="16" value="<?php echo ${$strOptionName}; ?>">
                </td>
            </tr>
            <tr>
                <td scope="row" class="settingInput" id="">
                    <label for="<?php echo ($strOptionName = htlrsbm_option_name($strOptButtonBgColor)); ?>">Button background color</label>
                </td>
                <td>
                    #<input type="text" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>" maxlength="6" size="8" value="<?php echo ${$strOptionName}; ?>">&nbsp;
                    <button rel="<?php echo $strOptionName; ?>" class="color" type="button">Pick color</button>
                </td>
            </tr>
            <tr>
                <td scope="row" class="settingInput" id="">
                    <label for="<?php echo ($strOptionName = htlrsbm_option_name($strOptButtonTxtColor)); ?>">Button text color</label>
                </td>
                <td>
                    #<input type="text" name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>" maxlength="6" size="8" value="<?php echo ${$strOptionName}; ?>">&nbsp;
                    <button rel="<?php echo $strOptionName; ?>" class="color" type="button">Pick color</button>
                </td>
            </tr>
            <tr>
                <td scope="row" class="settingInput" id="" valign="top">
                    <label for="<?php echo ($strOptionName = htlrsbm_option_name($strOptCustomCss)); ?>">Custom CSS</label>
                </td>
                <td>
                    <textarea name="<?php echo $strOptionName; ?>" id="<?php echo $strOptionName; ?>" rows="8" cols="100"><?php echo ${$strOptionName} ?: file_get_contents(HTLRSBM_PLUGIN_DIR.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'theme.css'); ?></textarea>
                </td>
            </tr>
            <tr valign="top">
                <td scope="row" class="settingInput" id="bottomBorderNone"></td>
                <td id="bottomBorderNone">
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
        <?php wp_nonce_field($strNonceAction,$strNonceFieldName); ?>
    </form>
</div>
