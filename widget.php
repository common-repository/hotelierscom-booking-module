<?php

/**
 * Hoteliers.com booking module Insert HTML Snippet Widget Class
 */

////*****************************Sidebar Widget**********************************////

class Add_Hhtlrsbm_Widget extends WP_Widget
{

    /** constructor -- name this the same as the class above */
    public function __construct()
    {
        parent::__construct(false, $name = 'Add Hoteliers.com Booking Module');
    }

    /** @see WP_Widget::widget -- do not rename this */
    public function widget($args, $instance)
    {
        extract($args);
        global $wpdb;
        $title = apply_filters('widget_title', $instance['title']);

        echo $before_widget;
        if ($title) {
            echo $before_title.$title.$after_title;
        }
        // echo do_shortcode($entry->content);

        // when locale language is not supported take default language from settings
        $arrAvailableLanguages = htlrs_available_languages();
        $strFormClass = "js-hoteliers-form__form--instance-{$this->number}";
        if (
            true === empty($instance['language']) ||
            false === in_array($strLanguage = $instance['language'], $arrAvailableLanguages, true)
        ) {
            $strLanguage = true === in_array($strLocale = substr(get_locale(), 0, 2), $arrAvailableLanguages, true) ? $strLocale : htlrsbm_get_option('language', 'en');
        }

        htlrsbm_register_assets($strLanguage);

        echo htlrsbm_styling(htlrsbm_get_option('button_background_color'), htlrsbm_get_option('button_text_color'));

        echo htlrsbm_script(
            htlrsbm_get_option('chain_id'),
            array_keys(htlrsbm_get_option('hotels'))[0],
            $strLanguage,
            $strFormClass
        );

        echo htlrsbm_template(
            isset($instance['default_hotel']) && ($value = $instance['default_hotel']) && !empty($value) ? (int)$value : false,
            isset($instance['alignment']) && ($value = $instance['alignment']) && !empty($value) ? $value : htlrsbm_get_option('alignment'),
            in_array(htlrsbm_get_option('promotion_code'), ['1', true], true),
            htlrsbm_get_option('default_promotion_code'),
            $strLanguage,
            $strFormClass
        );

        echo $after_widget;
    }

    /** @see WP_Widget::update -- do not rename this */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['alignment'] = strip_tags($new_instance['alignment']);
        $instance['default_hotel'] = (int)(isset($new_instance['default_hotel']) ? $new_instance['default_hotel'] : 0);
        $instance['language'] = strip_tags($new_instance['language']);
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /** @see WP_Widget::form -- do not rename this */
    public function form($instance)
    {
        $alignment = isset($instance['alignment']) ? esc_attr($instance['alignment']) : '';
        $defaultHotel = isset($instance['default_hotel']) && ($defaultHotel = (int)$instance['default_hotel']) > 0 ? $defaultHotel : false;
        $language = isset($instance['language']) ? esc_attr($instance['language']) : 'en';
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';

        ?><p>
            <label for="<?php echo $fieldId = $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $fieldId; ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
        </p>

        <p>
            <label for="<?php echo $fieldId = $this->get_field_id('language'); ?>"><?php _e('Language:'); ?></label>
            <select class="widefat" id="<?php echo $fieldId; ?>" name="<?php echo $this->get_field_name('language'); ?>">
                <option <?php echo empty($language) ? 'selected="selected"' : ''; ?> value="">Use WordPress locale or legacy plugin language setting</option>
            <?php foreach (htlrs_available_languages() as $value): ?>
                <option value="<?php echo $value; ?>" <?php echo $value === $language ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
            <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $fieldId = $this->get_field_id('alignment'); ?>"><?php _e('Alignment:'); ?></label>
            <select class="widefat" id="<?php echo $fieldId; ?>" name="<?php echo $this->get_field_name('alignment'); ?>">
                <option <?php echo empty($alignment) ? 'selected="selected"' : ''; ?> value="">Inherit from global settings</option>
            <?php foreach (['horizontal', 'vertical',] as $value): ?>
                <option value="<?php echo $value; ?>" <?php echo $value === $alignment ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
            <?php endforeach; ?>
            </select>
        </p>

        <?php if (count($hotels = htlrsbm_get_option('hotels', [])) > 1): ?>
        <p>
            <label for="<?php echo $fieldId = $this->get_field_id('default_hotel'); ?>"><?php _e('Default hotel:'); ?></label>
            <select class="widefat" id="<?php echo $fieldId; ?>" name="<?php echo $this->get_field_name('default_hotel'); ?>">
                <option <?php echo $defaultHotel === false ? 'selected="selected"' : ''; ?> value="">None</option>
            <?php foreach (htlrsbm_get_option('hotels', []) as $hotelId => $hotelName): ?>
                <option value="<?php echo($hotelId = (int)$hotelId); ?>" <?php echo $hotelId === $defaultHotel ? 'selected="selected"' : ''; ?>><?php echo $hotelName; ?></option>
            <?php endforeach; ?>
            </select>
        </p>
    <?php endif;
    }

}

add_action('widgets_init', function () {
    register_widget('Add_Hhtlrsbm_Widget');
});
