<?php

if (false === function_exists('htlrs_available_languages')) {
    function htlrs_available_languages() {
        return ['en', 'nl', 'de', 'fr', 'it', 'es',];
    }
}

if (!function_exists('htlrsbm_option_name')) {
    function htlrsbm_option_name($strKey) {
        if (substr_count($strKey,HTLRSBM_PLUGIN_PREFIX) > 0) {
            return $strKey;
        }
        return HTLRSBM_PLUGIN_PREFIX.$strKey;
    }
}

if (!function_exists('htlrsbm_option')) {
    function htlrsbm_get_option($strKey,$mixDefault = false) {
        return get_option(htlrsbm_option_name($strKey),$mixDefault);
    }
}

if (!function_exists('htlrsbm_register_assets')) {
    function htlrsbm_register_assets($language = null) {
        htlrsbm_register_js_assets($language);

        htlrsbm_register_css_assets($language);
    }
}

if (false === function_exists('htlrsbm_register_css_assets')) {
    function htlrsbm_register_css_assets($language = null) {
        $objStyles = wp_styles();
        $arrQueuedStyles = array_reverse(array_unique(array_merge(array_keys($objStyles->registered), $objStyles->queue)));

        foreach ([
           HTLRSBM_PLUGIN_PREFIX.'_main' => 'css/main.css',
           HTLRSBM_PLUGIN_PREFIX.'_fancybox' => [
               'pattern' => 'fancybox',
               'src' => 'css/fancybox.css',
           ],
           HTLRSBM_PLUGIN_PREFIX.'_booker' => 'css/booker.css',
        ] as $strKey => $mixFile) {
            if (is_string($mixFile)) {
                $strFile = $mixFile;
            } elseif (true === is_array($mixFile) && true === htlrsbm_should_queue_asset($arrQueuedStyles, $mixFile['pattern'])) {
                $strFile = $mixFile['src'];
            }

            wp_register_style($strKey,plugins_url($strFile,HTLRSBM_PLUGIN_FILE), [], htlrsbm_plugin_version());
            wp_enqueue_style($strKey);
        }

        if (file_exists(HTLRSBM_PLUGIN_UPLOAD_BASEDIR.DIRECTORY_SEPARATOR.($strCustomCss = 'custom.css'))) {
            wp_register_style($strKey = HTLRSBM_PLUGIN_PREFIX.'_custom',HTLRSBM_PLUGIN_UPLOAD_BASEURL.DIRECTORY_SEPARATOR.$strCustomCss, [], htlrsbm_plugin_version());
            wp_enqueue_style($strKey);
        }
    }
}

if (false === function_exists('htlrsbm_register_js_assets')) {
    function htlrsbm_register_js_assets($language = null) {
        $objScripts = wp_scripts();
        $arrQueuedScripts = array_reverse(array_unique(array_merge(array_keys($objScripts->registered), $objScripts->queue)));

        foreach ([
            'jquery',
            'jquery-ui-core',
            'jquery-ui-position',
            'jquery-ui-datepicker',
        ] as $strKey) {
            wp_enqueue_script($strKey);
        }

        foreach (array_filter([
            HTLRSBM_PLUGIN_PREFIX.'_datepicker_language_script' => false === in_array($language, [null, 'en'], true)
                ? "js/jquery/datepicker-{$language}.js"
                : null,
            HTLRSBM_PLUGIN_PREFIX.'_fancybox_script' => [
                'pattern' => 'fancybox',
                'src' => 'js/jquery/jquery.fancybox.js',
            ],
            HTLRSBM_PLUGIN_PREFIX.'_hoteliers_script' => 'js/hoteliers.js',
        ]) as $strKey => $mixFile) {
            if (is_string($mixFile)) {
                $strFile = $mixFile;
            } elseif (true === is_array($mixFile) && true === htlrsbm_should_queue_asset($arrQueuedScripts, $mixFile['pattern'])) {
                $strFile = $mixFile['src'];
            }

            wp_register_script($strKey,plugins_url($strFile,HTLRSBM_PLUGIN_FILE),[],htlrsbm_plugin_version());
            wp_enqueue_script($strKey);
        }
    }
}

if (false === function_exists('htlrsbm_should_queue_asset')) {
    function htlrsbm_should_queue_asset(array $queuedItems, $pattern) {
        foreach ($queuedItems as $queuedItem) {
            if (false !== stripos($queuedItem, $pattern)) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('htlrsbm_update_option')) {
    function htlrsbm_update_option($strKey,$mixValue,$mixAutoload = null) {
        return update_option(htlrsbm_option_name($strKey),$mixValue,$mixAutoload);
    }
}

if (!function_exists('htlrsbm_plugin_version')) {
    function htlrsbm_plugin_version() {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH.'wp-admin/includes/plugin.php';
        }
        $plugin_folder = get_plugins('/'.plugin_basename(HTLRSBM_PLUGIN_DIR));
        return $plugin_folder[basename(HTLRSBM_PLUGIN_FILE)]['Version'];
    }
}

if (!function_exists('htlrsbm_translate')) {
    function htlrsbm_translate($strText = null,$strLanguage = null) {
        $arrTranslations = array_merge([
            'arrival' => [
                'en' => 'Arrival',
                'nl' => 'Aankomst',
                'de' => 'Ankunft',
                'fr' => 'Arrivée',
                'it' => 'Arrivo',
                'es' => 'Entrada',
            ],
            'departure' => [
                'en' => 'Departure',
                'nl' => 'Vertrek',
                'de' => 'Abfahrt',
                'fr' => 'Départ',
                'it' => 'Partenza',
                'es' => 'Salida',
            ],
            'check' => [
                'en' => 'Check',
                'nl' => 'Controleer',
                'de' => 'Verfügbarkeit prüfen',
                'fr' => 'Vérifier',
                'it' => 'Verificare',
                'es' => 'Verificar',
            ],
            'code' => [
                'en' => 'Code',
                'nl' => 'Code',
                'de' => 'Code',
                'fr' => 'Code',
                'it' => 'Code',
                'es' => 'Code',
            ],
            'selectHotel' => [
                'en' => 'Select hotel',
                'nl' => 'Selecteer hotel',
                'de' => 'Hotel auswählen',
                'fr' => 'Sélectionnez hôtel',
                'it' => 'Select hotel',
                'es' => 'Seleccione hotel',
            ],
            'selectHotelPlaceholder' => [
                'en' => '-- Choose --',
                'nl' => '-- Kies --',
                'de' => '-- Wählen Sie --',
                'fr' => '-- Choisissez --',
                'it' => '-- Seleziona --',
                'es' => '-- Seleccionar --',
            ],
        ],htlrsbm_get_option('translations',[]));
        if ($strText === null && $strLanguage === null) {
            return $arrTranslations;
        }
        return $arrTranslations[$strText][$strLanguage];
    }
}
