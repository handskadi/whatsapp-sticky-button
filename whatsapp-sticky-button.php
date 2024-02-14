<?php
/*
Plugin Name: WhatsApp Sticky Button
Description: Adds a floating WhatsApp button for easy chat initiation.
Version: 1.0
Author: Mohamed KADI
Author URI: https://www.mohamedkadi.com
Plugin URI: https://www.mohamedkadi.com/project/whatsapp-sticky-button
License: GPL v2 or later
Tested up to: 6.4.3
Requires at least: 6.0.0
*/


// Enqueue scripts and styles
function enqueue_whatsapp_sticky_button_scripts() {
    wp_enqueue_style('whatsapp-sticky-button-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('whatsapp-sticky-button-script', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'enqueue_whatsapp_sticky_button_scripts');

// Display WhatsApp button
function display_whatsapp_sticky_button() {
    $is_active = get_option('whatsapp_sticky_button_active', '1'); // Default to active
    if ($is_active !== '1') {
        return; // Exit if the button is deactivated
    }

    $phone_number = get_option('whatsapp_sticky_button_phone_number', 'your-default-phonenumber');
    $position = get_option('whatsapp_sticky_button_position', 'bottom-right');
    $custom_position = get_option('whatsapp_sticky_button_custom_position', '');
    $button_text = get_option('whatsapp_sticky_button_text', 'WhatsApp');

    $style = '';
    if ($position === 'custom' && !empty($custom_position)) {
        $style = 'style="' . esc_attr($custom_position) . '"';
    } else {
        $style = 'style="' . esc_attr($position) . ': 20px;"';
    }

    echo '<div id="whatsapp-sticky-button" class="whatsapp-sticky-button" ' . $style . '><a href="https://wa.me/' . esc_attr($phone_number) . '" target="_blank"><img src="' . plugin_dir_url(__FILE__) . 'images/whatsapp-icon.png" alt="WhatsApp"><span class="mktext">' . esc_html($button_text) . '</span></a></div>';
}

add_action('wp_footer', 'display_whatsapp_sticky_button');

// Add settings link on the plugin page
function add_whatsapp_sticky_button_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=whatsapp-sticky-button-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

$plugin_basename = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin_basename", 'add_whatsapp_sticky_button_settings_link');

// Add settings page
function whatsapp_sticky_button_settings_page() {
    ?>
    <div class="wrap">
        <h1>WhatsApp Sticky Button Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('whatsapp_sticky_button_settings');
            do_settings_sections('whatsapp_sticky_button_settings');
            submit_button();
            ?>
        </form>

        <!-- Footer Section -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ccc; text-align: center;">
            <p>Made by <a href="https://mohamedkadi.com" target="_blank">MKweb</a></p>
            <img src="<?php echo plugin_dir_url(__FILE__) . 'images/mk-logo.png'; ?>" alt="MKweb Logo" style="max-width: 100px; height: auto;">
        </div>
    </div>
    <?php
}

function whatsapp_sticky_button_register_settings() {
    register_setting('whatsapp_sticky_button_settings', 'whatsapp_sticky_button_active');
    register_setting('whatsapp_sticky_button_settings', 'whatsapp_sticky_button_phone_number');
    register_setting('whatsapp_sticky_button_settings', 'whatsapp_sticky_button_position');
    register_setting('whatsapp_sticky_button_settings', 'whatsapp_sticky_button_custom_position');
    register_setting('whatsapp_sticky_button_settings', 'whatsapp_sticky_button_text');

    add_settings_section('whatsapp_sticky_button_main', 'WhatsApp Sticky Button Settings', 'whatsapp_sticky_button_section_text', 'whatsapp_sticky_button_settings');

    add_settings_field('whatsapp_sticky_button_active', 'Activate WhatsApp Sticky Button', 'whatsapp_sticky_button_active_input', 'whatsapp_sticky_button_settings', 'whatsapp_sticky_button_main');
    add_settings_field('whatsapp_sticky_button_phone_number', 'WhatsApp Phone Number', 'whatsapp_sticky_button_phone_number_input', 'whatsapp_sticky_button_settings', 'whatsapp_sticky_button_main');
    add_settings_field('whatsapp_sticky_button_position', 'Button Position', 'whatsapp_sticky_button_position_input', 'whatsapp_sticky_button_settings', 'whatsapp_sticky_button_main');
    add_settings_field('whatsapp_sticky_button_custom_position', 'Custom Position', 'whatsapp_sticky_button_custom_position_input', 'whatsapp_sticky_button_settings', 'whatsapp_sticky_button_main');
    add_settings_field('whatsapp_sticky_button_text', 'Button Text', 'whatsapp_sticky_button_text_input', 'whatsapp_sticky_button_settings', 'whatsapp_sticky_button_main');
}

function whatsapp_sticky_button_section_text() {
    echo '<p>Enter your WhatsApp phone number, customize the button position, and set the button text below:</p>';
}

function whatsapp_sticky_button_active_input() {
    $is_active = get_option('whatsapp_sticky_button_active', '1');
    $checked = checked($is_active, '1', false);
    echo '<label><input type="checkbox" name="whatsapp_sticky_button_active" value="1" ' . $checked . '> Activate WhatsApp Sticky Button</label>';
}

function whatsapp_sticky_button_phone_number_input() {
    $phone_number = get_option('whatsapp_sticky_button_phone_number', 'your-default-phonenumber');
    echo '<input type="text" name="whatsapp_sticky_button_phone_number" value="' . esc_attr($phone_number) . '" />';
}

function whatsapp_sticky_button_position_input() {
    $position = get_option('whatsapp_sticky_button_position', 'bottom-right');
    $positions = array(
        'top-left' => 'Top Left',
        'top-right' => 'Top Right',
        'bottom-left' => 'Bottom Left',
        'bottom-right' => 'Bottom Right',
        'custom' => 'Custom',
    );

    echo '<select name="whatsapp_sticky_button_position">';
    foreach ($positions as $value => $label) {
        $selected = selected($value, $position, false);
        echo '<option value="' . esc_attr($value) . '">' . esc_html($label) . '</option>';
    }
    echo '</select>';
}

function whatsapp_sticky_button_custom_position_input() {
    $custom_position = get_option('whatsapp_sticky_button_custom_position', '');
    echo '<input type="text" name="whatsapp_sticky_button_custom_position" value="' . esc_attr($custom_position) . '" placeholder="e.g., top: 20px; right: 30px;" />';
}

function whatsapp_sticky_button_text_input() {
    $button_text = get_option('whatsapp_sticky_button_text', 'WhatsApp');
    echo '<input type="text" name="whatsapp_sticky_button_text" value="' . esc_attr($button_text) . '" />';
}

add_action('admin_menu', 'whatsapp_sticky_button_add_menu');

function whatsapp_sticky_button_add_menu() {
    add_options_page('WhatsApp Sticky Button Settings', 'WhatsApp Button', 'manage_options', 'whatsapp-sticky-button-settings', 'whatsapp_sticky_button_settings_page');
    add_action('admin_init', 'whatsapp_sticky_button_register_settings');
}
