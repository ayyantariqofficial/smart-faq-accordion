<?php

/**
 * Plugin Name:       Smart FAQ Accordion
 * Plugin URI:        https://example.com/smart-faq-accordion
 * Description:       Add SEO-friendly FAQs to posts with an accordion UI and FAQPage schema. Works with Classic Editor and supports shortcode/auto-append.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Ayyan Tariq
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       smart-faq-accordion
 *
 * @package SmartFAQAccordion
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Core constants.
 */
define('SFA_VERSION', '1.0.0');
define('SFA_PLUGIN_FILE', __FILE__);
define('SFA_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('SFA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SFA_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Plugin identifiers & keys.
 * Keeping these centralized prevents typos and makes refactoring easy.
 */
define('SFA_TEXT_DOMAIN', 'smart-faq-accordion');
define('SFA_OPTION_KEY', 'sfa_settings');
define('SFA_META_KEY_FAQS', '_sfa_faq_items');
define('SFA_SHORTCODE', 'smart_faq');

require_once SFA_PLUGIN_DIR . 'includes/core/activate.php';
register_activation_hook(SFA_PLUGIN_FILE, 'sfa_activate');

/**
 * Default settings (used now + later when we build settings page).
 *
 * display_mode:
 * - 'shortcode' => show only where shortcode is used
 * - 'auto'      => auto-append at end (unless shortcode exists)
 */
function sfa_default_settings()
{
    return array(
        'display_mode'    => 'shortcode', // 'shortcode' or 'auto'
        'theme'           => 'minimal',   // minimal | light | bordered (we'll implement later)
        'icon'            => 'chevron',   // chevron | plusminus | none (we'll implement later)
        'single_open'     => 1,           // 1 = only one open at a time
        'open_first'      => 1,           // 1 = first item open by default
    );
}

/**
 * Get merged settings (saved settings + defaults).
 * Safe because missing keys fall back to defaults.
 */
function sfa_get_settings()
{
    $saved    = get_option(SFA_OPTION_KEY, array());
    $defaults = sfa_default_settings();

    if (! is_array($saved)) {
        $saved = array();
    }

    return wp_parse_args($saved, $defaults);
}


/**
 * Boot the plugin.
 */
function sfa_boot()
{
    // Later we will load text domain and include files here:
    // - admin meta box
    // - settings page
    // - shortcode renderer
    // - schema output

    require_once SFA_PLUGIN_DIR . 'includes/loader.php';
}
add_action('plugins_loaded', 'sfa_boot');
