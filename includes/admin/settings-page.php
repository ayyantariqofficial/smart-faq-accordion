<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Add settings page under "Settings".
 */
function sfa_add_settings_page()
{
    add_options_page(
        __('Smart FAQ Accordion', SFA_TEXT_DOMAIN), // Page title
        __('Smart FAQ Accordion', SFA_TEXT_DOMAIN), // Menu title
        'manage_options',                            // Capability
        'sfa-settings',                              // Menu slug
        'sfa_render_settings_page'                   // Callback
    );
}
add_action('admin_menu', 'sfa_add_settings_page');

/**
 * Render settings page (shell for now).
 */
function sfa_render_settings_page()
{
    if (! current_user_can('manage_options')) {
        return;
    }

?>
    <div class="wrap">
        <h1><?php esc_html_e('Smart FAQ Accordion Settings', SFA_TEXT_DOMAIN); ?></h1>

        <form method="post" action="options.php">
            <?php
            settings_fields('sfa_settings_group');
            do_settings_sections('sfa-settings');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

/**
 * Register settings, sections, and fields.
 */
function sfa_register_settings()
{

    register_setting(
        'sfa_settings_group',
        SFA_OPTION_KEY,
        array(
            'type'              => 'array',
            'sanitize_callback' => 'sfa_sanitize_settings',
            'default'           => sfa_default_settings(),
        )
    );

    add_settings_section(
        'sfa_main_section',
        __('FAQ Display & Styling', SFA_TEXT_DOMAIN),
        'sfa_settings_section_intro',
        'sfa-settings'
    );

    add_settings_field(
        'sfa_display_mode',
        __('Display mode', SFA_TEXT_DOMAIN),
        'sfa_field_display_mode',
        'sfa-settings',
        'sfa_main_section'
    );

    add_settings_field(
        'sfa_theme',
        __('Theme', SFA_TEXT_DOMAIN),
        'sfa_field_theme',
        'sfa-settings',
        'sfa_main_section'
    );

    add_settings_field(
        'sfa_icon',
        __('Icons', SFA_TEXT_DOMAIN),
        'sfa_field_icon',
        'sfa-settings',
        'sfa_main_section'
    );

    add_settings_field(
        'sfa_single_open',
        __('Accordion behavior', SFA_TEXT_DOMAIN),
        'sfa_field_single_open',
        'sfa-settings',
        'sfa_main_section'
    );

    add_settings_field(
        'sfa_open_first',
        __('Open first item by default', SFA_TEXT_DOMAIN),
        'sfa_field_open_first',
        'sfa-settings',
        'sfa_main_section'
    );
}
add_action('admin_init', 'sfa_register_settings');

/**
 * Section intro text.
 */
function sfa_settings_section_intro()
{
    echo '<p>' . esc_html__('Choose how FAQs appear on posts, and control the accordion style and behavior.', SFA_TEXT_DOMAIN) . '</p>';
    echo '<p><strong>' . esc_html__('Tip:', SFA_TEXT_DOMAIN) . '</strong> ' .
        esc_html__('If Auto-append is enabled, FAQs will be added at the end of posts unless you use the shortcode.', SFA_TEXT_DOMAIN) .
        '</p>';
}


/**
 * Sanitize settings before saving.
 */
function sfa_sanitize_settings($input)
{
    $defaults = sfa_default_settings();
    $clean    = array();

    if (! is_array($input)) {
        return $defaults;
    }

    // Display mode.
    $allowed_display = array('shortcode', 'auto');
    $display_mode    = isset($input['display_mode']) ? sanitize_text_field($input['display_mode']) : $defaults['display_mode'];
    $clean['display_mode'] = in_array($display_mode, $allowed_display, true) ? $display_mode : $defaults['display_mode'];

    // Theme.
    $allowed_themes = array('minimal', 'light', 'bordered');
    $theme          = isset($input['theme']) ? sanitize_text_field($input['theme']) : $defaults['theme'];
    $clean['theme'] = in_array($theme, $allowed_themes, true) ? $theme : $defaults['theme'];

    // Icon.
    $allowed_icons = array('chevron', 'plusminus', 'none');
    $icon          = isset($input['icon']) ? sanitize_text_field($input['icon']) : $defaults['icon'];
    $clean['icon'] = in_array($icon, $allowed_icons, true) ? $icon : $defaults['icon'];

    // Checkboxes.
    $clean['single_open'] = ! empty($input['single_open']) ? 1 : 0;
    $clean['open_first']  = ! empty($input['open_first']) ? 1 : 0;

    return wp_parse_args($clean, $defaults);
}

function sfa_field_display_mode()
{
    $settings = sfa_get_settings();
?>
    <select name="<?php echo esc_attr(SFA_OPTION_KEY); ?>[display_mode]">
        <option value="shortcode" <?php selected($settings['display_mode'], 'shortcode'); ?>>
            <?php esc_html_e('Shortcode only (use [smart_faq])', SFA_TEXT_DOMAIN); ?>
        </option>
        <option value="auto" <?php selected($settings['display_mode'], 'auto'); ?>>
            <?php esc_html_e('Auto-append to end of post (unless shortcode is used)', SFA_TEXT_DOMAIN); ?>
        </option>
    </select>
<?php
    echo '<p class="description">' . esc_html__('Use [smart_faq] inside your post content if you want to control placement manually.', SFA_TEXT_DOMAIN) . '</p>';
}

function sfa_field_theme()
{
    $settings = sfa_get_settings();
?>
    <select name="<?php echo esc_attr(SFA_OPTION_KEY); ?>[theme]">
        <option value="minimal" <?php selected($settings['theme'], 'minimal'); ?>>
            <?php esc_html_e('Minimal', SFA_TEXT_DOMAIN); ?>
        </option>
        <option value="light" <?php selected($settings['theme'], 'light'); ?>>
            <?php esc_html_e('Light', SFA_TEXT_DOMAIN); ?>
        </option>
        <option value="bordered" <?php selected($settings['theme'], 'bordered'); ?>>
            <?php esc_html_e('Bordered', SFA_TEXT_DOMAIN); ?>
        </option>
    </select>
<?php
    echo '<p class="description">' . esc_html__('Themes only control basic styling. Your site CSS can override it anytime.', SFA_TEXT_DOMAIN) . '</p>';
}

function sfa_field_icon()
{
    $settings = sfa_get_settings();
?>
    <select name="<?php echo esc_attr(SFA_OPTION_KEY); ?>[icon]">
        <option value="chevron" <?php selected($settings['icon'], 'chevron'); ?>>
            <?php esc_html_e('Chevron', SFA_TEXT_DOMAIN); ?>
        </option>
        <option value="plusminus" <?php selected($settings['icon'], 'plusminus'); ?>>
            <?php esc_html_e('Plus / Minus', SFA_TEXT_DOMAIN); ?>
        </option>
        <option value="none" <?php selected($settings['icon'], 'none'); ?>>
            <?php esc_html_e('None', SFA_TEXT_DOMAIN); ?>
        </option>
    </select>
<?php
    echo '<p class="description">' . esc_html__('Icons are visual only and do not affect schema output.', SFA_TEXT_DOMAIN) . '</p>';
}

function sfa_field_single_open()
{
    $settings = sfa_get_settings();
?>
    <label>
        <input type="checkbox" name="<?php echo esc_attr(SFA_OPTION_KEY); ?>[single_open]" value="1" <?php checked((int) $settings['single_open'], 1); ?> />
        <?php esc_html_e('Allow only one FAQ item to be open at a time', SFA_TEXT_DOMAIN); ?>
    </label>
<?php
}

function sfa_field_open_first()
{
    $settings = sfa_get_settings();
?>
    <label>
        <input type="checkbox" name="<?php echo esc_attr(SFA_OPTION_KEY); ?>[open_first]" value="1" <?php checked((int) $settings['open_first'], 1); ?> />
        <?php esc_html_e('Open the first FAQ item by default', SFA_TEXT_DOMAIN); ?>
    </label>
<?php
}
