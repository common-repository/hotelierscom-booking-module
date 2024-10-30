<?php

if (!function_exists('htlrsbm_styling')) {
    function htlrsbm_styling($strBackgroundColor,$strTextColor) {
        return <<<HTML
            <style type="text/css">
                .ui-state-active,
                .ui-state-hover,
                .ui-datepicker-header,
                .hoteliers-form_btn {
                    background-color: #{$strBackgroundColor} !important;
                    color: #{$strTextColor} !important;
                    background-image: none !important;
                }
            </style>
HTML;
    }
}

if (!function_exists('htlrsbm_script')) {
    function htlrsbm_script($intChainId,$intHotelId,$strLanguage,$strFormClass = null) {
        $strOnSiteOverlay = in_array(htlrsbm_get_option('booking_engine_reminder'),['1',false],true) ? 'true' : 'false';
        $strGoogleAnalyticsCode = htlrsbm_get_option('google_analytics_code');
        $strFormClass = null !== $strFormClass ? "'{$strFormClass}'" : 'undefined';
        return <<<HTML
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    new hoteliers_form('{$intHotelId}','{$strLanguage}',{
                        chain_id: '{$intChainId}',
                        enable_onSiteOverlay: {$strOnSiteOverlay},
                        form_class: {$strFormClass},
                        ga_code: '{$strGoogleAnalyticsCode}',
                    });
                });
            </script>
HTML;
    }
}

if (!function_exists('htlrsbm_template')) {
    function htlrsbm_template($intDefaultHotel,$strAlignment,$strPromotionCodeChecked,$strDefaultPromotionCode,$strLanguage,$strFormClass = null) {
        $intNumberGridItems = 0;
        $strSelectHotelBlock = $strSelectHotelOptions = $strPromotionCode = '';

        foreach ([
            'arrival',
            'check',
            'departure',
            'selectHotel',
        ] as $strKey) {
            ${'str'.ucfirst($strKey)} = htlrsbm_translate($strKey,$strLanguage);
        }

        if (count($arrHotels = htlrsbm_get_option('hotels')) > 1) {
            $intNumberGridItems += 1;
            if (($intChainId = htlrsbm_get_option('chain_id')) > 0) {
                $strSelectHotelOptions .= '<option value="">'.htlrsbm_translate('selectHotelPlaceholder',$strLanguage).'</option>';
            }
            foreach ($arrHotels as $intHotelId => $strHotelName) {
                $strSelectHotelOptions .= '<option value="'.($intHotelId = (int)$intHotelId).'" '.($intDefaultHotel === $intHotelId ? 'selected="selected"' : '').'>'.$strHotelName.'</option>';
            }
            $strSelectHotelBlock = <<<HTML
                <li class="hoteliers-form__grid-item js-grid-item" data-item="hoteliers_hotelid">
                    <label class="hoteliers-form__label" for="">{$strSelectHotel}</label>
                    <div class="hoteliers-form_form-field-container">
                        <select class="hoteliers-form_form-field js-hf_hotel_hotelid" name="hf_hotel_hotelid">{$strSelectHotelOptions}</select>
                    </div>
                </li>
HTML;
        }

        if (!empty($strPromotionCodeChecked)) {
            $intNumberGridItems += 1;
            $strCode = htlrsbm_translate('code',$strLanguage);
            $strPromotionCode = <<<HTML
                <li class="hoteliers-form__grid-item js-grid-item" data-item="hoteliers_code">
                    <label class="hoteliers-form__label" for="">{$strCode}</label>
                    <div class="hoteliers-form_form-field-container hoteliers-form_form-field-container--code">
                        <input class="hoteliers-form_form-field" type="text" value="{$strDefaultPromotionCode}">
                    </div>
                </li>
HTML;
        }

        $strClass = ($strAlignment === 'horizontal' ? 'hoteliers-form__grid hoteliers-form__grid--'.($intNumberGridItems+3).'-fields' : '');

        return <<<HTML
            <div class="hoteliers-form {$strFormClass}">
                <form class="hoteliers-form__form">
                    <ul class="hoteliers-form__list {$strClass}">
                        {$strSelectHotelBlock}
                        <li class="hoteliers-form__grid-item js-grid-item" data-item="hoteliers_arrival">
                            <label class="hoteliers-form__label" for="">{$strArrival}</label>
                            <div class="hoteliers-form_form-field-container">
                                <input class="hoteliers-form_form-field" type="text">
                                <svg class="hoteliers-form__icon hoteliers-form__icon--no-event" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 32 32">
                                    <path fill="#000" d="M28 4h-3v1c0 1.1-0.9 2-2 2s-2-0.9-2-2v-1h-10v1c0 1.1-0.9 2-2 2s-2-0.9-2-2v-1h-3c-1.1 0-2 0.9-2 2v22c0 1.1 0.9 2 2 2h24c1.1 0 2-0.9 2-2v-22c0-1.1-0.9-2-2-2zM28 28c0 0 0 0 0 0h-24c0 0 0 0 0 0v-18h24v18zM9 6c0.6 0 1-0.4 1-1v-4c0-0.6-0.4-1-1-1s-1 0.4-1 1v4c0 0.6 0.4 1 1 1zM23 6c0.6 0 1-0.4 1-1v-4c0-0.6-0.4-1-1-1s-1 0.4-1 1v4c0 0.6 0.4 1 1 1zM18 12h-10v2h8v4h-8v2h8v4h-8v2h10zM22 26h2v-14h-4v2h2zM27.3 30.8h-22.5c-1.1 0-2-0.6-2-1.7v1c0 1.1 0.9 2 2 2h22.5c1.1 0 2-0.9 2-2v-1c0 1.1-0.9 1.8-2 1.8z"></path>
                                </svg>
                            </div>
                        </li>
                        <li class="hoteliers-form__grid-item js-grid-item" data-item="hoteliers_departure">
                            <label class="hoteliers-form__label" for="">{$strDeparture}</label>
                            <div class="hoteliers-form_form-field-container">
                                <input class="hoteliers-form_form-field" type="text">
                                <svg class="hoteliers-form__icon hoteliers-form__icon--no-event" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 32 32">
                                    <path fill="#000" d="M28 4h-3v1c0 1.1-0.9 2-2 2s-2-0.9-2-2v-1h-10v1c0 1.1-0.9 2-2 2s-2-0.9-2-2v-1h-3c-1.1 0-2 0.9-2 2v22c0 1.1 0.9 2 2 2h24c1.1 0 2-0.9 2-2v-22c0-1.1-0.9-2-2-2zM28 28c0 0 0 0 0 0h-24c0 0 0 0 0 0v-18h24v18zM9 6c0.6 0 1-0.4 1-1v-4c0-0.6-0.4-1-1-1s-1 0.4-1 1v4c0 0.6 0.4 1 1 1zM23 6c0.6 0 1-0.4 1-1v-4c0-0.6-0.4-1-1-1s-1 0.4-1 1v4c0 0.6 0.4 1 1 1zM18 12h-10v2h8v4h-8v2h8v4h-8v2h10zM22 26h2v-14h-4v2h2zM27.3 30.8h-22.5c-1.1 0-2-0.6-2-1.7v1c0 1.1 0.9 2 2 2h22.5c1.1 0 2-0.9 2-2v-1c0 1.1-0.9 1.8-2 1.8z"></path>
                                </svg>
                            </div>
                        </li>
                        {$strPromotionCode}
                        <li class="hoteliers-form__grid-item js-grid-item" data-item="hoteliers_submit">
                            <label class="hoteliers-form__label hoteliers-form__label--hidden" for="">Submit form</label>
                            <button class="btn-primary hoteliers-form_form-field hoteliers-form_btn">{$strCheck}</button>
                        </li>
                    </ul><!-- / hoteliers-form__list -->
                </form>
            </div>
HTML;
    }
}
