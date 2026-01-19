<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Allow only a safe subset of HTML tags in FAQ answers.
 */
function sfa_sanitize_answer_html($html)
{
    $allowed = array(
        'a'      => array(
            'href'   => true,
            'title'  => true,
            'target' => true,
            'rel'    => true,
        ),
        'strong' => array(),
        'em'     => array(),
        'u'      => array(),
        'br'     => array(),
        'p'      => array(),
        'ul'     => array(),
        'ol'     => array(),
        'li'     => array(),
    );

    return wp_kses($html, $allowed);
}


/**
 * Save FAQ meta box data.
 */
function sfa_save_faq_meta_box($post_id)
{

    // 1) Only handle Posts (matches our spec).
    if ('post' !== get_post_type($post_id)) {
        return;
    }

    // 2) Skip autosaves.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // 3) Skip revisions.
    if (wp_is_post_revision($post_id)) {
        return;
    }

    // 4) Verify nonce.
    if (! isset($_POST['sfa_faqs_nonce'])) {
        return;
    }

    if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['sfa_faqs_nonce'])), 'sfa_save_faqs')) {
        return;
    }

    // 5) Capability check.
    // Editors/Admins are fine; Authors can edit their own posts, etc.
    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    // 6) Read FAQs input.
    $raw_faqs = isset($_POST['sfa_faqs']) ? $_POST['sfa_faqs'] : array();
    if (! is_array($raw_faqs)) {
        $raw_faqs = array();
    }

    $clean_faqs = array();

    // 7) Sanitize and normalize.
    foreach ($raw_faqs as $faq) {
        if (! is_array($faq)) {
            continue;
        }

        $question = isset($faq['question']) ? sanitize_text_field(wp_unslash($faq['question'])) : '';
        $answer_raw = isset($faq['answer']) ? wp_unslash($faq['answer']) : '';
        $answer     = sfa_sanitize_answer_html($answer_raw);

        $question = trim($question);
        $answer   = trim($answer);

        // Optional safety limits (good practice).
        $max_question_len = 200;
        $max_answer_len   = 2000;

        if (strlen($question) > $max_question_len) {
            $question = substr($question, 0, $max_question_len);
        }

        if (strlen($answer) > $max_answer_len) {
            $answer = substr($answer, 0, $max_answer_len);
        }

        // Skip empty rows.
        if ('' === trim($question) && '' === trim(wp_strip_all_tags($answer))) {
            continue;
        }

        $clean_faqs[] = array(
            'question' => $question,
            'answer'   => $answer,
        );
    }

    // Re-index array to avoid gaps (0,1,2...) after deletions.
    $clean_faqs = array_values($clean_faqs);


    // 8) Save or delete meta.
    if (empty($clean_faqs)) {
        delete_post_meta($post_id, SFA_META_KEY_FAQS);
        return;
    }

    update_post_meta($post_id, SFA_META_KEY_FAQS, $clean_faqs);
}
add_action('save_post', 'sfa_save_faq_meta_box');
