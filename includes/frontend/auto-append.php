<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Auto-append FAQs to the end of post content based on settings.
 */
function sfa_maybe_auto_append_faqs($content)
{

    // Only on frontend main query singular posts.
    if (is_admin()) {
        return $content;
    }

    if (! is_singular('post')) {
        return $content;
    }

    if (! in_the_loop() || ! is_main_query()) {
        return $content;
    }

    $post_id = get_the_ID();
    if (! $post_id) {
        return $content;
    }

    $settings = sfa_get_settings();

    // Only auto-append when enabled.
    if ('auto' !== $settings['display_mode']) {
        return $content;
    }

    // If shortcode already rendered on this request, do not auto-append.
    if (! empty($GLOBALS['sfa_shortcode_rendered'])) {
        return $content;
    }

    // If shortcode exists in the post content, do not auto-append.
    if (has_shortcode($content, SFA_SHORTCODE)) {
        return $content;
    }


    $faqs_html = sfa_render_faqs_html($post_id);
    if ('' === $faqs_html) {
        return $content;
    }

    return $content . "\n\n" . $faqs_html;
}
add_filter('the_content', 'sfa_maybe_auto_append_faqs', 20);
