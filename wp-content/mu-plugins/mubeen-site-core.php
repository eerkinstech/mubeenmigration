<?php
/**
 * Plugin Name: Mubeen Site Core
 * Description: Small, site-specific performance and security defaults for Mubeen Migration.
 * Version: 1.0.0
 */

defined('ABSPATH') || exit;

// The homepage is rendered from maintainable PHP/CSS/JS files instead of
// Elementor widgets. Keeping this in the site plugin prevents theme updates
// from overwriting the design.
require_once __DIR__ . '/mubeen-home/bootstrap.php';

// The one-time local build script does not need to bootstrap ElementsKit's
// frontend runtime. Skipping it in build mode keeps maintenance scripts fast;
// normal browser and WP Admin requests still load ElementsKit normally.
if (defined('MUBEEN_BUILD_MODE') && MUBEEN_BUILD_MODE) {
    add_filter('option_active_plugins', static function (array $plugins): array {
        return array_values(array_diff($plugins, ['elementskit-lite/elementskit-lite.php']));
    });
}

/**
 * Preserve the three established URL families from the current website.
 * Content is intentionally added only after review; empty placeholder posts are
 * not generated because they would create thin indexable pages.
 */
add_action('init', static function (): void {
    register_post_type('mm_service', [
        'labels' => [
            'name'          => 'Service Groups',
            'singular_name' => 'Service Group',
            'add_new_item'  => 'Add Service Group',
            'edit_item'     => 'Edit Service Group',
        ],
        'public'       => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-portfolio',
        'supports'     => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
        'has_archive'  => 'Visa',
        'rewrite'      => ['slug' => 'Visa', 'with_front' => false],
    ]);

    register_post_type('mm_visa', [
        'labels' => [
            'name'          => 'Visa Pages',
            'singular_name' => 'Visa Page',
            'add_new_item'  => 'Add Visa Page',
            'edit_item'     => 'Edit Visa Page',
        ],
        'public'       => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-media-document',
        'supports'     => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
        'has_archive'  => 'visa-list',
        'rewrite'      => ['slug' => 'visa-list', 'with_front' => false],
    ]);

    register_post_type('mm_destination', [
        'labels' => [
            'name'          => 'Destinations',
            'singular_name' => 'Destination',
            'add_new_item'  => 'Add Destination',
            'edit_item'     => 'Edit Destination',
        ],
        'public'       => true,
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-location-alt',
        'supports'     => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
        'has_archive'  => 'destination',
        'rewrite'      => ['slug' => 'destination', 'with_front' => false],
    ]);
});

// Remove non-essential legacy discovery tags and WordPress version disclosure.
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('template_redirect', 'wp_shortlink_header', 11);

// Disable emoji assets; modern devices render native emoji without these requests.
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

// Keep XML-RPC disabled unless a future integration explicitly requires it.
add_filter('xmlrpc_enabled', '__return_false');

// Do not expose REST API author collections to anonymous enumeration.
add_filter('rest_endpoints', static function (array $endpoints): array {
    if (!is_user_logged_in()) {
        unset($endpoints['/wp/v2/users']);
        unset($endpoints['/wp/v2/users/(?P<id>[\\d]+)']);
    }
    return $endpoints;
});
