<?php
/*
Plugin Name: Page Specific Scripts for WordPress
Description: Add custom scripts to specific pages via a meta box.
Version: 1.0
Author: D Kandekore
*/

// Add meta box
function cps_add_custom_meta_box() {
    add_meta_box(
        'cps_meta_box',        // Unique ID
        'Custom Script',       // Box title
        'cps_custom_meta_box_html', // Content callback
        'page',                // Post type
        'normal',              // Context
        'high'                 // Priority
    );
}
add_action('add_meta_boxes', 'cps_add_custom_meta_box');

// Meta box HTML
function cps_custom_meta_box_html($post) {
    $value = get_post_meta($post->ID, '_cps_custom_script', true);
    ?>
    <label for="cps_custom_script">Insert custom script for this page:</label>
    <textarea name="cps_custom_script" id="cps_custom_script" rows="10" style="width:100%;"><?php echo esc_textarea($value); ?></textarea>
    <?php
}

// Save meta box content
function cps_save_custom_meta_box($post_id) {
    if (array_key_exists('cps_custom_script', $_POST)) {
        update_post_meta(
            $post_id,
            '_cps_custom_script',
            $_POST['cps_custom_script']
        );
    }
}
add_action('save_post', 'cps_save_custom_meta_box');

// Enqueue custom script
function cps_add_custom_script() {
    if (is_page()) {
        global $post;
        $custom_script = get_post_meta($post->ID, '_cps_custom_script', true);
        if (!empty($custom_script)) {
            echo '<script>' . $custom_script . '</script>';
        }
    }
}
add_action('wp_head', 'cps_add_custom_script');
