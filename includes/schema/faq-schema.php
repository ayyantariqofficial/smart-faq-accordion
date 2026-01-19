<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Convert HTML answer into plain text for schema.
 * (Google expects text; keep it clean.)
 */
function sfa_schema_clean_text($html)
{
    $text = wp_strip_all_tags($html);
    $text = preg_replace('/\s+/', ' ', $text);
    return trim($text);
}

/**
 * Build FAQPage schema array for a given post.
 */
function sfa_build_faq_schema($post_id)
{
    $faqs = sfa_get_post_faqs($post_id);
    if (empty($faqs)) {
        return array();
    }

    $main_entity = array();

    foreach ($faqs as $faq) {
        $question = isset($faq['question']) ? trim((string) $faq['question']) : '';
        $answer   = isset($faq['answer']) ? sfa_schema_clean_text((string) $faq['answer']) : '';

        if ('' === $question || '' === $answer) {
            continue;
        }

        $main_entity[] = array(
            '@type'          => 'Question',
            'name'           => $question,
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text'  => $answer,
            ),
        );
    }

    if (empty($main_entity)) {
        return array();
    }

    return array(
        '@context'    => 'https://schema.org',
        '@type'       => 'FAQPage',
        'mainEntity'  => $main_entity,
    );
}

/**
 * Output FAQ schema in wp_head.
 */
function sfa_output_faq_schema()
{

    // Frontend only.
    if (is_admin()) {
        return;
    }

    // Posts only (as per your spec).
    if (! is_singular('post')) {
        return;
    }

    $post_id = get_queried_object_id();
    if (! $post_id) {
        return;
    }

    $schema = sfa_build_faq_schema($post_id);
    if (empty($schema)) {
        return;
    }

    /**
     * Detect if FAQPage schema is already present (from another plugin/theme).
     * This is a best-effort approach.
     */
    function sfa_faq_schema_already_present()
    {

        // Many SEO plugins store their JSON-LD in globals before output.
        // Rank Math often stores data in a global.
        if (! empty($GLOBALS['rank_math_json_ld'])) {
            $maybe = wp_json_encode($GLOBALS['rank_math_json_ld']);
            if (false !== strpos($maybe, '"FAQPage"')) {
                return true;
            }
        }

        // Yoast schema graph may exist in a global in some setups.
        if (! empty($GLOBALS['wpseo_schema'])) {
            $maybe = wp_json_encode($GLOBALS['wpseo_schema']);
            if (false !== strpos($maybe, '"FAQPage"')) {
                return true;
            }
        }

        return false;
    }

    // Avoid conflicts: if another FAQPage schema is already being output, skip ours.
    if (sfa_faq_schema_already_present()) {
        return;
    }

    // JSON encode safely.
    $json = wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if (empty($json)) {
        return;
    }

    echo "\n" . '<script type="application/ld+json">' . $json . '</script>' . "\n";
}
add_action('wp_head', 'sfa_output_faq_schema', 20);
