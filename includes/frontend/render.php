<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Get FAQs for a post.
 */
function sfa_get_post_faqs($post_id)
{
    $faqs = get_post_meta($post_id, SFA_META_KEY_FAQS, true);

    if (! is_array($faqs)) {
        return array();
    }

    // Normalize.
    $faqs = array_values($faqs);

    // Filter invalid rows defensively.
    $clean = array();
    foreach ($faqs as $faq) {
        if (! is_array($faq)) {
            continue;
        }
        $q = isset($faq['question']) ? trim((string) $faq['question']) : '';
        $a = isset($faq['answer']) ? trim((string) $faq['answer']) : '';

        if ('' === $q && '' === wp_strip_all_tags($a)) {
            continue;
        }

        $clean[] = array(
            'question' => $q,
            'answer'   => $a,
        );
    }

    return $clean;
}

/**
 * Render FAQs accordion HTML.
 */
function sfa_render_faqs_html($post_id)
{
    $faqs = sfa_get_post_faqs($post_id);
    if (empty($faqs)) {
        return '';
    }

    $settings = sfa_get_settings();

    $theme_class = 'sfa-theme-' . sanitize_html_class($settings['theme']);
    $icon_class  = 'sfa-icon-' . sanitize_html_class($settings['icon']);

    // Data attributes for JS behavior later.
    $single_open = ! empty($settings['single_open']) ? '1' : '0';
    $open_first  = ! empty($settings['open_first']) ? '1' : '0';

    ob_start();
?>
    <div class="sfa-accordion <?php echo esc_attr($theme_class . ' ' . $icon_class); ?>"
        data-single-open="<?php echo esc_attr($single_open); ?>"
        data-open-first="<?php echo esc_attr($open_first); ?>">

        <?php foreach ($faqs as $i => $faq) :
            $question = $faq['question'];
            $answer   = $faq['answer'];

            $item_id = 'sfa-item-' . (int) $post_id . '-' . (int) $i;
        ?>
            <div class="sfa-item">
                <button class="sfa-question" type="button"
                    aria-expanded="false"
                    aria-controls="<?php echo esc_attr($item_id); ?>">
                    <span class="sfa-question-text"><?php echo esc_html($question); ?></span>
                    <span class="sfa-icon" aria-hidden="true"></span>
                </button>

                <div class="sfa-answer" id="<?php echo esc_attr($item_id); ?>" hidden>
                    <div class="sfa-answer-inner">
                        <?php
                        // Answers are already sanitized on save. Still output safely.
                        echo wp_kses_post(wpautop($answer));
                        ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
<?php
    return ob_get_clean();
}

/**
 * Shortcode: [smart_faq]
 */
function sfa_shortcode_smart_faq($atts = array(), $content = null)
{
    $post_id = get_the_ID();
    if (! $post_id) {
        return '';
    }

    // Mark that shortcode output happened on this request.
    $GLOBALS['sfa_shortcode_rendered'] = true;

    return sfa_render_faqs_html($post_id);
}

add_shortcode(SFA_SHORTCODE, 'sfa_shortcode_smart_faq');
