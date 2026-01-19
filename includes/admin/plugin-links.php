<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Add "Settings" link on the Plugins screen.
 */
function sfa_add_plugin_action_links($links)
{
    $settings_url = admin_url('options-general.php?page=sfa-settings');
    $settings_link = '<a href="' . esc_url($settings_url) . '">' . esc_html__('Settings', SFA_TEXT_DOMAIN) . '</a>';

    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . SFA_PLUGIN_BASENAME, 'sfa_add_plugin_action_links');
