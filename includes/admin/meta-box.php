<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Register FAQ meta box for posts.
 */
function sfa_register_faq_meta_box()
{
    add_meta_box(
        'sfa_faq_meta_box',          // Meta box ID
        __('FAQs', SFA_TEXT_DOMAIN), // Title
        'sfa_render_faq_meta_box',   // Callback (we'll build next)
        'post',                      // Screen (Posts only)
        'normal',                    // Context
        'default'                    // Priority
    );
}
add_action('add_meta_boxes', 'sfa_register_faq_meta_box');

/**
 * Render callback (placeholder for now).
 */
function sfa_render_faq_meta_box($post)
{
    // Nonce for security (we'll verify it in Step 2.4 save handler).
    wp_nonce_field('sfa_save_faqs', 'sfa_faqs_nonce');

    // Get existing FAQs from post meta (will be empty until we build saving).
    $faqs = get_post_meta($post->ID, SFA_META_KEY_FAQS, true);

    if (! is_array($faqs)) {
        $faqs = array();
    }

    // If no FAQs yet, show one empty row for better UX.
    if (empty($faqs)) {
        $faqs[] = array(
            'question' => '',
            'answer'   => '',
        );
    }

?>
    <div id="sfa-faq-wrapper">
        <?php foreach ($faqs as $index => $faq) :
            $question = isset($faq['question']) ? $faq['question'] : '';
            $answer   = isset($faq['answer']) ? $faq['answer'] : '';
        ?>
            <div class="sfa-faq-row" style="margin-bottom:16px; padding:12px; border:1px solid #ccd0d4; border-radius:6px;">
                <p style="margin-top:0;">
                    <label for="sfa_faqs_<?php echo esc_attr($index); ?>_question" style="font-weight:600;">
                        <?php esc_html_e('Question', SFA_TEXT_DOMAIN); ?>
                    </label>
                    <input
                        type="text"
                        class="widefat"
                        id="sfa_faqs_<?php echo esc_attr($index); ?>_question"
                        name="sfa_faqs[<?php echo esc_attr($index); ?>][question]"
                        value="<?php echo esc_attr($question); ?>"
                        placeholder="e.g. Is breakfast included?" />
                </p>

                <p style="margin-bottom:0;">
                    <label for="sfa_faqs_<?php echo esc_attr($index); ?>_answer" style="font-weight:600;">
                        <div class="sfa-toolbar" style="margin:8px 0;">
                            <button type="button" class="button sfa-format-btn" data-cmd="bold"><strong>B</strong></button>
                            <button type="button" class="button sfa-format-btn" data-cmd="italic"><em>I</em></button>
                            <button type="button" class="button sfa-format-btn" data-cmd="underline"><span style="text-decoration:underline;">U</span></button>
                            <button type="button" class="button sfa-format-btn" data-cmd="link">Link</button>
                        </div>
                        <?php esc_html_e('Answer', SFA_TEXT_DOMAIN); ?>
                    </label>
                    <textarea
                        class="widefat"
                        rows="4"
                        id="sfa_faqs_<?php echo esc_attr($index); ?>_answer"
                        name="sfa_faqs[<?php echo esc_attr($index); ?>][answer]"
                        placeholder="Write a clear, helpful answer."><?php echo esc_textarea($answer); ?></textarea>
                </p>

                <p style="margin-top:10px;">
                    <button type="button" class="button sfa-remove-faq">
                        <?php esc_html_e('Remove', SFA_TEXT_DOMAIN); ?>
                    </button>
                </p>
            </div>
        <?php endforeach; ?>
    </div>

    <p>
        <button type="button" class="button button-primary" id="sfa-add-faq">
            <?php esc_html_e('Add FAQ', SFA_TEXT_DOMAIN); ?>
        </button>
    </p>

    <!-- Template row (hidden). JS will clone this in Step 2.3 -->
    <script type="text/template" id="sfa-faq-row-template">
        <div class="sfa-faq-row" style="margin-bottom:16px; padding:12px; border:1px solid #ccd0d4; border-radius:6px;">
			<p style="margin-top:0;">
				<label style="font-weight:600;"><?php esc_html_e('Question', SFA_TEXT_DOMAIN); ?></label>
				<input type="text" class="widefat" name="sfa_faqs[{INDEX}][question]" value="" placeholder="e.g. Is breakfast included?" />
			</p>
			<p style="margin-bottom:0;">
				<label style="font-weight:600;"><?php esc_html_e('Answer', SFA_TEXT_DOMAIN); ?></label>
                <div class="sfa-toolbar" style="margin:8px 0;">
	            <button type="button" class="button sfa-format-btn" data-cmd="bold"><strong>B</strong></button>
	            <button type="button" class="button sfa-format-btn" data-cmd="italic"><em>I</em></button>
	            <button type="button" class="button sfa-format-btn" data-cmd="underline"><span style="text-decoration:underline;">U</span></button>
	            <button type="button" class="button sfa-format-btn" data-cmd="link">Link</button>
                </div>
				<textarea class="widefat" rows="4" name="sfa_faqs[{INDEX}][answer]" placeholder="Write a clear, helpful answer."></textarea>
			</p>
			<p style="margin-top:10px;">
				<button type="button" class="button sfa-remove-faq"><?php esc_html_e('Remove', SFA_TEXT_DOMAIN); ?></button>
			</p>
		</div>
	</script>
<?php
}
