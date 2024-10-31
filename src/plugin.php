<?php

	class rpp_plugin {

		// Wheter to insert code to skip page cache
		private $skip_cache = false;

		function __construct() {

		}

		/**
		 * Look for highest priority snippet to include, if any
		 * @global object $wpdb - Wordpress database
		 */
		function snippet_insert() {
			global $wpdb, $post;

			// Check for snippet preview
			$preview_request = filter_input(INPUT_GET, RPP_PREVIEW_QUERY_PARAM);
			if ($preview_request === 'yes') {
				$params = get_option(RPP_OPTION_PREVIEW_PARAMS, false);
				if ($params) {
					require_once RPP_PLUGIN_DIR . 'src/parser.php';

					$parser = new rpp_parser();
					$parser->write_snippet($params);
				}

				return;
			}

			// Exclude for user roles
			if (is_user_logged_in()) {
				$roles_to_exclude = get_option(RPP_OPTION_EXCLUDE_ROLES);

				if (is_array($roles_to_exclude) && count($roles_to_exclude) > 0) {
					$user_roles = (array) wp_get_current_user()->roles;
					$role_matches = array_intersect($roles_to_exclude, $user_roles);

					if (count($role_matches) > 0) {
						$this->skip_cache = boolval(get_option(RPP_OPTION_SKIP_CACHE));

						return;
					}
				}
			}

			// Get categories and tags associated to current page
			$page_categories = get_the_category();
			$page_category_ids = [];
			if (is_array($page_categories)) {
				foreach ($page_categories as $term) {
					$page_category_ids[] = $term->term_id;
				}
			}
			$page_tags = get_the_tags();
			$page_tag_ids = [];
			if (is_array($page_tags)) {
				foreach ($page_tags as $term) {
					$page_tag_ids[] = $term->term_id;
				}
			}
			// Type if matched rule and snippet params to include (or false)
			$type_found = false;
			$params_json = false;

			// List snippets
			$sql = "SELECT id, params " .
				"FROM " . RPP_TABLE_SNIPPETS . " " .
				"WHERE active = 1 " .
				"ORDER BY priority ASC";
			$snippets = $wpdb->get_results($sql);

			// Inspect snippets if they match for the site
			foreach ($snippets as $item) {
				// Leave if already found
				if ($type_found) {
					break;
				}

				// Entire site rule
				$sql = $wpdb->prepare("SELECT COUNT(*) FROM " . RPP_TABLE_RULES . " WHERE snippet_id = %d AND target_type = 'all'", $item->id);
				if (intval($wpdb->get_var($sql)) > 0) {
					$params_json = $item->params;
					$type_found = 'all';
				}

				if (!$type_found) {
					// Categories, tags and posts rule
					$sql = $wpdb->prepare("SELECT target_type, target_id FROM " . RPP_TABLE_RULES . " WHERE snippet_id = %d AND target_type IN ('category', 'tag', 'post_page')", $item->id);
					$term_rules = $wpdb->get_results($sql);
					foreach ($term_rules as $rule) {
						// Match category
						if ($rule->target_type === 'category' && in_array($rule->target_id, $page_category_ids)) {
							$params_json = $item->params;
							$type_found = 'category';
						}
						// Match tag
						else if ($rule->target_type === 'tag' && in_array($rule->target_id, $page_tag_ids)) {
							$params_json = $item->params;
							$type_found = 'tag';
						}
						// Match post / page
						else if ($rule->target_type === 'post_page' && $rule->target_id == prop('ID', $post, 0)) {
							$type_found = 'post_page';
							$params_json = $item->params;
						}
					}
				}
			}

			if (in_array($type_found, ['category', 'tag', 'post_page'])) {
				// Skip aggregator pages
				if (!is_singular() || !$post) {
					return;
				}
			}

			// Include code with params if found
			if ($type_found && $params_json) {
				require_once RPP_PLUGIN_DIR . 'src/parser.php';

				$parser = new rpp_parser();
				$parser->write_snippet($params_json);
			}
		}

		/**
		 * Add JS code to the page that adds a cache-skipping query parameter to frontend links
		 */
		function skip_cache_footer() {
			if ($this->skip_cache) {
				include RPP_PLUGIN_DIR . 'views/skip-cache.php';
			}
		}
	}
