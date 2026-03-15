<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue admin assets only on post editor screens.
 */
function sfa_admin_enqueue_assets($hook_suffix)
{
    // Post editor screens: post.php (edit) and post-new.php (new).
    if ('post.php' !== $hook_suffix && 'post-new.php' !== $hook_suffix) {
        return;
    }

    $screen = get_current_screen();
    if (! $screen || ! in_array($screen->post_type, sfa_get_enabled_post_types(), true)) {
        return;
    }

    wp_enqueue_script(
        'sfa-admin',
        SFA_PLUGIN_URL . 'assets/admin/admin.js',
        array(),
        SFA_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'sfa_admin_enqueue_assets');
