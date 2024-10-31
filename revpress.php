<?php
	/**
	 * Plugin Name: RevPress
	 * Plugin URI: https://rev.press/
	 * Description: Provides management and makes it easy to add Subscribe with Google to your site
	 * Version: 1.1.4
	 * Author: Chris Andrews
	 * Author URI: https://rev.press/
	 * Requires PHP: 8.0
	 * License: GPLv2 or later
	 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
	 * */
	global $wpdb;

	/**
	 * Plugin internals
	 */
	// Full path to plugin directory
	define('RPP_PLUGIN_DIR', WP_PLUGIN_DIR . '/revpress/');
	// Full URL to the root foldr of the plugin
	define('RPP_PLUGIN_URL', plugins_url('', __FILE__));
	// Name of database table for the code snippets
	define('RPP_TABLE_SNIPPETS', $wpdb->prefix . 'revpress_snippets');
	// Name of database table for the rules of the snippets
	define('RPP_TABLE_RULES', $wpdb->prefix . 'revpress_snippet_rules');
	// WP option - don't insert snippet for these roles
	define('RPP_OPTION_EXCLUDE_ROLES', 'revpress_exclude_roles');
	// WP option - add query parameter to serve cached frontend pages
	define('RPP_OPTION_SKIP_CACHE', 'revpress_skip_cache');
	// WP option - parameters to use for the snippet preview
	define('RPP_OPTION_PREVIEW_PARAMS', 'revpress_preview_params');
	// Query parameter to use to trigger preview
	define('RPP_PREVIEW_QUERY_PARAM', 'revpress_preview_snippet');
	// Version of the plugin
	define('RPP_VERSION', '1.1.4');

	// Helper functions
	require_once RPP_PLUGIN_DIR . 'src/helpers.php';
	// Handle frontend
	require_once RPP_PLUGIN_DIR . 'src/plugin.php';
	// Plugin install/(de)activation
	require_once RPP_PLUGIN_DIR . 'src/setup.php';

	/**
	 * Admin section
	 */
	if (is_admin()) {
		// Admin notifications
		require_once RPP_PLUGIN_DIR . 'src/notice.php';
		// Admin pages and actions
		require_once RPP_PLUGIN_DIR . 'src/admin.php';

		$admin = new rpp_admin();
		$admin->page_settings_actions();

		/**
		 * AJAX callbacks
		 */
		add_action('wp_ajax_revpress_parse_code', [$admin, 'parse_code']);
		add_action('wp_ajax_revpress_show_params', [$admin, 'show_params']);
		add_action('wp_ajax_revpress_list_options', [$admin, 'list_options']);
		add_action('wp_ajax_revpress_prepare_preview', [$admin, 'prepare_preview']);

		/**
		 * Add admin page to manage inquiries
		 */
		add_action('admin_menu', [$admin, 'admin_menu']);

		/**
		 * Add a settings page to the admin dashboard
		 */
		add_action('admin_enqueue_scripts', [$admin, 'admin_assets']);

		/**
		 * Add Settings link to plugin listing page
		 */
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$admin, 'settings_link']);
	}
	/**
	 * Frontend plugin
	 */
	else {
		// Initialize main plugin object
		$front = new rpp_plugin();

		// Insert snippet
		add_action('wp_head', [$front, 'snippet_insert']);
		// Insert cache skipping
		add_action('wp_footer', [$front, 'skip_cache_footer']);
	}

	register_activation_hook(__FILE__, 'revpress_activate');
	register_deactivation_hook(__FILE__, 'revpress_deactivate');
	register_uninstall_hook(__FILE__, 'revpress_uninstall');
