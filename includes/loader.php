<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Core (activation already loaded in main file).
 * Central place to include plugin modules.
 * We'll add more includes here as we build features.
 */

// Admin (meta box + settings) - later.
// require_once SFA_PLUGIN_DIR . 'includes/admin/meta-box.php';
// Admin (meta box + settings).
require_once SFA_PLUGIN_DIR . 'includes/admin/meta-box.php';
require_once SFA_PLUGIN_DIR . 'includes/admin/enqueue.php';
require_once SFA_PLUGIN_DIR . 'includes/admin/save.php';
require_once SFA_PLUGIN_DIR . 'includes/admin/settings-page.php';
require_once SFA_PLUGIN_DIR . 'includes/admin/plugin-links.php';


// require_once SFA_PLUGIN_DIR . 'includes/admin/settings-page.php';

// Frontend (shortcode + auto-append) - later.
// require_once SFA_PLUGIN_DIR . 'includes/frontend/render.php';
// Frontend (shortcode + rendering).
require_once SFA_PLUGIN_DIR . 'includes/frontend/render.php';
require_once SFA_PLUGIN_DIR . 'includes/frontend/enqueue.php';
require_once SFA_PLUGIN_DIR . 'includes/frontend/auto-append.php';



// Schema (FAQ JSON-LD) - later.
// require_once SFA_PLUGIN_DIR . 'includes/schema/faq-schema.php';
// Schema (FAQ JSON-LD).
require_once SFA_PLUGIN_DIR . 'includes/schema/faq-schema.php';
