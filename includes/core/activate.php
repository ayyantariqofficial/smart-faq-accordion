<?php
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Runs on plugin activation.
 * Initializes default settings if they don't exist.
 */
function sfa_activate()
{
    // Only set defaults if option doesn't exist yet.
    if (false === get_option(SFA_OPTION_KEY, false)) {
        add_option(SFA_OPTION_KEY, sfa_default_settings(), '', false);
    }
}
