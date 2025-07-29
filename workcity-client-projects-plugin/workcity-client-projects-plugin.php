<?php
/*
Plugin Name: Workcity Client Projects
Description: Custom post type for managing client projects with shortcode display.
Version: 1.0
Author: Awoleye Bolaji
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Register Custom Post Type
function workcity_register_client_projects() {
    $labels = array(
        'name' => 'Client Projects',
        'singular_name' => 'Client Project',
        'add_new' => 'Add New Project',
        'add_new_item' => 'Add New Client Project',
        'edit_item' => 'Edit Client Project',
        'new_item' => 'New Client Project',
        'view_item' => 'View Client Project',
        'search_items' => 'Search Projects',
        'menu_name' => 'Client Projects'
    );

    $args = array(
        'label' => 'Client Projects',
        'labels' => $labels,
        'public' => true,
        'supports' => array('title'),
        'has_archive' => true,
        'menu_icon' => 'dashicons-portfolio',
        'show_in_rest' => true,
    );

    register_post_type('client_project', $args);
}
add_action('init', 'workcity_register_client_projects');

// Add Meta Boxes
function workcity_add_project_metaboxes() {
    add_meta_box('client_project_details', 'Project Details', 'workcity_project_fields', 'client_project', 'normal', 'default');
}
add_action('add_meta_boxes', 'workcity_add_project_metaboxes');

// Meta Fields Form
function workcity_project_fields($post) {
    $client_name = get_post_meta($post->ID, '_client_name', true);
    $description = get_post_meta($post->ID, '_description', true);
    $status = get_post_meta($post->ID, '_status', true);
    $deadline = get_post_meta($post->ID, '_deadline', true);
    ?>
    <p><label>Client Name:</label><br/>
    <input type="text" name="client_name" value="<?php echo esc_attr($client_name); ?>" class="widefat"/></p>

    <p><label>Description:</label><br/>
    <textarea name="description" rows="4" class="widefat"><?php echo esc_textarea($description); ?></textarea></p>

    <p><label>Status:</label><br/>
    <select name="status" class="widefat">
        <option value="pending" <?php selected($status, 'pending'); ?>>Pending</option>
        <option value="in_progress" <?php selected($status, 'in_progress'); ?>>In Progress</option>
        <option value="completed" <?php selected($status, 'completed'); ?>>Completed</option>
    </select></p>

    <p><label>Deadline:</label><br/>
    <input type="date" name="deadline" value="<?php echo esc_attr($deadline); ?>" class="widefat"/></p>
    <?php
}

// Save Meta Fields
function workcity_save_project_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['client_name'])) update_post_meta($post_id, '_client_name', sanitize_text_field($_POST['client_name']));
    if (isset($_POST['description'])) update_post_meta($post_id, '_description', sanitize_textarea_field($_POST['description']));
    if (isset($_POST['status'])) update_post_meta($post_id, '_status', sanitize_text_field($_POST['status']));
    if (isset($_POST['deadline'])) update_post_meta($post_id, '_deadline', sanitize_text_field($_POST['deadline']));
}
add_action('save_post', 'workcity_save_project_meta');

// Shortcode to Display Projects
function workcity_client_projects_shortcode($atts) {
    $query = new WP_Query(array(
        'post_type' => 'client_project',
        'posts_per_page' => -1,
    ));

    if (!$query->have_posts()) return '<p>No client projects found.</p>';

    $output = '<div class="client-projects"><ul>';
    while ($query->have_posts()) {
        $query->the_post();
        $client = get_post_meta(get_the_ID(), '_client_name', true);
        $desc = get_post_meta(get_the_ID(), '_description', true);
        $status = get_post_meta(get_the_ID(), '_status', true);
        $deadline = get_post_meta(get_the_ID(), '_deadline', true);

        $output .= '<li style="margin-bottom: 20px;">';
        $output .= '<h3>' . esc_html(get_the_title()) . '</h3>';
        $output .= '<p><strong>Client:</strong> ' . esc_html($client) . '</p>';
        $output .= '<p><strong>Status:</strong> ' . esc_html(ucwords(str_replace('_', ' ', $status))) . '</p>';
        $output .= '<p><strong>Deadline:</strong> ' . esc_html($deadline) . '</p>';
        $output .= '<p>' . esc_html($desc) . '</p>';
        $output .= '</li>';
    }
    $output .= '</ul></div>';
    wp_reset_postdata();
    return $output;
}
add_shortcode('client_projects', 'workcity_client_projects_shortcode');
