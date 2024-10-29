<?php
/**
 * Plugin Name: Alt Text Updater
 * Description: A plugin to update image alt text based on image title or using a CSV file. Supports large media libraries with batch processing and progress tracking.
 * Version: 1.0.0
 * Author: Anushka Deshingkar
 * License: GPLv2 or later
 */

defined('ABSPATH') or die('No script kiddies please!');

// Enqueue admin scripts and styles
function wp_alt_text_updater_enqueue_admin_scripts() {
    wp_enqueue_script('wp-alt-text-updater', plugin_dir_url(__FILE__) . 'wp-alt-text-updater.js', array('jquery'), '1.0', true);
    wp_enqueue_style('wp-alt-text-updater', plugin_dir_url(__FILE__) . 'wp-alt-text-updater.css');
}
add_action('admin_enqueue_scripts', 'wp_alt_text_updater_enqueue_admin_scripts');

// Create the admin page for the plugin
function wp_alt_text_updater_menu() {
    add_menu_page(
        'Alt Text Updater', 
        'Alt Text Updater', 
        'manage_options', 
        'alt-text-updater', 
        'wp_alt_text_updater_page', 
        'dashicons-edit', 
        100
    );
}
add_action('admin_menu', 'wp_alt_text_updater_menu');

// The admin page content
function wp_alt_text_updater_page() {
    ?>
    <div class="wrap">
        <h1>Alt Text Updater</h1>
        <form id="alt-text-updater-form" enctype="multipart/form-data" method="post">
            <p id="select-option">Select an Option:</p>
            <div>
                <label class="radio-label">
                    <input type="radio" name="update_option" value="by_title"> Update Alt Text Based on Image Title
                </label>
            </div>
            <div>
                <label class="radio-label">
                    <input type="radio" name="update_option" value="by_csv"> Upload CSV to Update Alt Text
                </label>
            </div>

            <div id="csv-upload-section" style="display: none;">
                <input type="file" name="csv_file" id="csv_file" accept=".csv">
            </div>

            <button id="submit-button" type="submit" style="display: none;">Submit</button>

            <!-- Progress Bar -->
            <div id="progress-bar-wrapper">
                <div id="progress-bar"></div>
                <div id="progress-text">0%</div>
            </div>
        </form>
    </div>
    <?php
}

// AJAX handler for updating alt text by title in batches
function wp_alt_text_updater_update_by_title() {
    $batch_size = 50; // Adjust as needed
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;

    $args = array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => $batch_size,
        'offset'         => $offset,
    );
    
    $images = get_posts($args);
    $total_images = wp_count_posts('attachment')->inherit;
    $updated_count = 0;

    foreach ($images as $image) {
        $title = get_the_title($image->ID);
        $current_alt = get_post_meta($image->ID, '_wp_attachment_image_alt', true);

        if (empty($current_alt)) {
            update_post_meta($image->ID, '_wp_attachment_image_alt', $title);
            $updated_count++;
        }
    }

    $has_more = ($offset + $batch_size) < $total_images;

    wp_send_json_success(array(
        'updated_count' => $updated_count,
        'next_offset'   => $has_more ? $offset + $batch_size : null,
        'progress'      => round((($offset + $batch_size) / $total_images) * 100),
    ));
}
add_action('wp_ajax_wp_alt_text_updater_update_by_title', 'wp_alt_text_updater_update_by_title');

// AJAX handler for updating alt text by CSV in batches
function wp_alt_text_updater_update_by_csv() {
    if (!empty($_FILES['csv_file']['tmp_name'])) {
        $csv_file = fopen($_FILES['csv_file']['tmp_name'], 'r');
        $batch_size = 50; // Adjust as needed
        $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;

        for ($i = 0; $i < $offset && !feof($csv_file); $i++) {
            fgetcsv($csv_file);
        }

        $updated_count = 0;
        $total = count(file($_FILES['csv_file']['tmp_name'])) - 1;

        while (($row = fgetcsv($csv_file)) !== false && $updated_count < $batch_size) {
            $image_url = $row[0];
            $alt_text = $row[1];
            $attachment_id = attachment_url_to_postid($image_url);

            if ($attachment_id) {
                update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);
                $updated_count++;
            }
        }

        $has_more = ($offset + $batch_size) < $total;

        fclose($csv_file);

        wp_send_json_success(array(
            'updated_count' => $updated_count,
            'next_offset'   => $has_more ? $offset + $batch_size : null,
            'progress'      => round((($offset + $batch_size) / $total) * 100),
        ));
    }
}
add_action('wp_ajax_wp_alt_text_updater_update_by_csv', 'wp_alt_text_updater_update_by_csv');
