<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue frontend assets only when FAQs exist for the current post.
 */
function sfa_frontend_enqueue_assets()
{

    if (! is_singular('post')) {
        return;
    }

    $post_id = get_queried_object_id();
    if (! $post_id) {
        return;
    }

    $faqs = get_post_meta($post_id, SFA_META_KEY_FAQS, true);
    if (! is_array($faqs) || empty($faqs)) {
        return;
    }

    wp_enqueue_style(
        'sfa-frontend',
        SFA_PLUGIN_URL . 'assets/frontend/faq.css',
        array(),
        SFA_VERSION
    );

    wp_enqueue_script(
        'sfa-frontend',
        SFA_PLUGIN_URL . 'assets/frontend/faq.js',
        array(),
        SFA_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'sfa_frontend_enqueue_assets');
