<?php

	class rpp_admin {

		private $nonce_name = 'rev-ED^K_#*%Yh6Pf-press';

		function __construct() {

		}

		/**
		 * Assets used by the plugin in the admin section
		 */
		function admin_assets() {
			wp_enqueue_style('rpp-admin-plugin', plugins_url('../assets/css/admin.css', __FILE__));

			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-sortable', ['jquery']);
			wp_enqueue_script('rpp-select2', RPP_PLUGIN_URL . '/assets/js/select2.min.js', ['jquery'], RPP_VERSION, true);
			wp_enqueue_script('rpp-admin', RPP_PLUGIN_URL . '/assets/js/admin.min.js', ['jquery', 'jquery-ui-sortable', 'rpp-select2'], RPP_VERSION, true);

			wp_localize_script(
				'rpp-admin',
				'rpp_ajax',
				[
					'ajax_url' => admin_url('admin-ajax.php'),
					'nonce' => wp_create_nonce($this->nonce_name)
				]
			);
		}

		/**
		 * Add menu entries for the plugin in the WP Admin section
		 */
		function admin_menu() {
			add_menu_page('RevPress Plugin admin', 'RevPress', 'manage_options', 'revpress-plugin', null, 'data:image/svg+xml;base64, ' . base64_encode(file_get_contents(RPP_PLUGIN_DIR . 'assets/image/dashicon.svg')));
			add_submenu_page('revpress-plugin', 'RevPress Plugin Settings', 'Settings', 'manage_options', 'revpress-plugin', [$this, 'page_settings']);
			add_submenu_page('revpress-plugin', 'RevPress Plugin Guide', 'Guide', 'manage_options', 'revpress-plugin-guide', [$this, 'page_guide']);
		}

		/**
		 * Renders the Settings page
		 */
		function page_settings() {
			global $wpdb;

			$rev_page = 'settings';

			// Data for fix options
			$user_roles = get_editable_roles();

			// List snippets
			$sql = "SELECT * " .
				"FROM " . RPP_TABLE_SNIPPETS . " " .
				"ORDER BY priority ASC";
			$snippets = $wpdb->get_results($sql);

			// Parse snippet details
			foreach ($snippets as $item) {
				// Set properties to use in the form
				$item->prefix = 'snippet-' . prop('id', $item, 0) . '-';
				$item->rules = [
					'all' => false,
					'categories' => [],
					'tags' => [],
					'posts_pages' => []
				];

				// Check for 'Entire site' rule
				$sql = $wpdb->prepare("SELECT COUNT(*) FROM " . RPP_TABLE_RULES . " WHERE snippet_id = %d AND target_type = 'all'", $item->id);
				if (intval($wpdb->get_var($sql)) > 0) {
					$item->rules ['all'] = true;

					continue;
				}

				// Check for category rule
				$sql = $wpdb->prepare("SELECT target_id FROM " . RPP_TABLE_RULES . " WHERE snippet_id = %d AND target_type = 'category'", $item->id);
				$rule_item_ids = $wpdb->get_col($sql);
				if (!empty($rule_item_ids)) {
					$item->rules['categories'] = get_terms([
						'taxonomy' => 'category',
						'include' => $rule_item_ids,
						'fields' => 'id=>name',
						'orderby' => 'name',
						'hide_empty' => 0
					]);

					continue;
				}

				// Check for tag rule
				$sql = $wpdb->prepare("SELECT target_id FROM " . RPP_TABLE_RULES . " WHERE snippet_id = %d AND target_type = 'tag'", $item->id);
				$rule_item_ids = $wpdb->get_col($sql);
				if (!empty($rule_item_ids)) {
					$item->rules['tags'] = get_terms([
						'taxonomy' => 'post_tag',
						'include' => $rule_item_ids,
						'fields' => 'id=>name',
						'orderby' => 'name',
						'hide_empty' => 0
					]);

					continue;
				}

				// Check for tag rule
				$sql = $wpdb->prepare("SELECT target_id FROM " . RPP_TABLE_RULES . " WHERE snippet_id = %d AND target_type = 'post_page'", $item->id);
				$rule_item_ids = $wpdb->get_col($sql);
				if (!empty($rule_item_ids)) {
					$post_list = get_posts([
						'post_type' => ['page'],
						'include' => $rule_item_ids,
						'numberposts' => -1,
						'orderby' => 'title'
					]);
					foreach ($post_list as $post_item) {
						$title = mb_strlen($post_item->post_title) > 24 ? mb_substr($post_item->post_title, 0, 20) . '..' : $post_item->post_title;
						$item->rules['posts_pages'][] = (object) [
								'id' => $post_item->ID,
								'title' => $title
						];
					}

					continue;
				}
			}

			// Get exclude roles
			$exclude_roles = get_option(RPP_OPTION_EXCLUDE_ROLES);
			if (!is_array($exclude_roles)) {
				$exclude_roles = [];
			}
			$skip_cache = boolval(get_option(RPP_OPTION_SKIP_CACHE));

			add_thickbox();

			include RPP_PLUGIN_DIR . 'views/page-settings.php';
		}

		/**
		 * Process the POST-ed data from the Settings page
		 * @global type $wpdb - Database object
		 */
		function page_settings_actions() {
			global $wpdb;

			// Save snippets
			if (post_not_null('submit_snippets')) {
				// Handle snippets
				$snippet_ids = explode(',', post_txt('ids'));
				$priority = 10;

				foreach ($snippet_ids as $snippet_id) {
					$new = false;
					if ($snippet_id === 'new') {
						$snippet_id = 0;
					}

					$snipet_name = post_txt('name_' . $snippet_id);
					if (empty($snipet_name)) {
						continue;
					}

					$snippet_data = [
						'name' => $snipet_name,
						'description' => post_txt('description_' . $snippet_id),
						'params' => post_txt('params_' . $snippet_id),
						'priority' => $priority++,
						'active' => post_checkbox('active_' . $snippet_id)
					];

					if ($snippet_id > 0) {
						// Update in database
						$rows = $wpdb->update(
							RPP_TABLE_SNIPPETS,
							$snippet_data,
							['id' => $snippet_id]
						);
					}
					else {
						$snippet_data['created'] = now();

						// Save data to database
						$wpdb->insert(RPP_TABLE_SNIPPETS, $snippet_data);
						$snippet_id = $wpdb->insert_id;
						$new = true;
					}

					// Remove previous rules
					$sql = $wpdb->prepare("DELETE FROM " . RPP_TABLE_RULES . " WHERE snippet_id = %d", $snippet_id);
					$wpdb->query($sql);

					// Add new rules
					$rule_type = post_txt($new ? 'rule_0' : 'rule_' . $snippet_id);
					switch ($rule_type) {
						case 'all':
							$rule_data = [
								'snippet_id' => $snippet_id,
								'target_type' => 'all',
								'target_id' => 0
							];
							$wpdb->insert(RPP_TABLE_RULES, $rule_data);
							break;

						case 'category':
							$rule_categories = post_array($new ? 'category_0' : 'category_' . $snippet_id);
							foreach ($rule_categories as $category_id) {
								$rule_data = [
									'snippet_id' => $snippet_id,
									'target_type' => 'category',
									'target_id' => $category_id
								];
								$wpdb->insert(RPP_TABLE_RULES, $rule_data);
							}
							break;

						case 'tag':
							$rule_tags = post_array($new ? 'tag_0' : 'tag_' . $snippet_id);
							foreach ($rule_tags as $tag_id) {
								$rule_data = [
									'snippet_id' => $snippet_id,
									'target_type' => 'tag',
									'target_id' => $tag_id
								];
								$wpdb->insert(RPP_TABLE_RULES, $rule_data);
							}
							break;

						case 'post_page':
							$rule_posts_pages = post_array($new ? 'post_page_0' : 'post_page_' . $snippet_id);
							foreach ($rule_posts_pages as $post_page_id) {
								$rule_data = [
									'snippet_id' => $snippet_id,
									'target_type' => 'post_page',
									'target_id' => $post_page_id
								];
								$wpdb->insert(RPP_TABLE_RULES, $rule_data);
							}
							break;
					}
				}

				// Handle user roles
				$role_keys = [];
				$skip_cache = 0;
				$set_exclude_roles = post_bool('exclude_roles');
				if ($set_exclude_roles) {
					$role_keys = post_array('role_keys');
					$skip_cache = post_bool('skip_cache') ? 1 : 0;
				}
				// Update or add option - exlude list
				if (update_option(RPP_OPTION_EXCLUDE_ROLES, $role_keys) === false) {
					add_option(RPP_OPTION_EXCLUDE_ROLES, $role_keys);
				}
				// Update or add option - skip cache
				if (update_option(RPP_OPTION_SKIP_CACHE, $skip_cache) === false) {
					add_option(RPP_OPTION_SKIP_CACHE, $skip_cache);
				}

				new rpp_notice('Snippets saved successfully!', 'success');
			}

			// Remove a snippet
			if (post_not_null('submit_remove_snippet')) {
				$snippet_id = post_int('id');
				$sql = $wpdb->prepare("DELETE FROM " . RPP_TABLE_SNIPPETS . " WHERE id = %d", $snippet_id);
				$wpdb->query($sql);
				$sql = $wpdb->prepare("DELETE FROM " . RPP_TABLE_RULES . " WHERE snippet_id = %d", $snippet_id);
				$wpdb->query($sql);

				new rpp_notice('Snippet deleted successfully!', 'success');
			}
		}

		/**
		 * Renders the Guide page
		 */
		function page_guide() {
			$rev_page = 'guide';

			include RPP_PLUGIN_DIR . 'views/page-guide.php';
		}

		/**
		 * Add extra links for the entry on the Installed Plugins page
		 */
		function settings_link(array $links) {
			$links[] = '<a href="' . esc_url(admin_url('admin.php')) . '?page=revpress-plugin">Settings</a>';
			$links[] = '<a href="' . esc_url(admin_url('admin.php')) . '?page=revpress-plugin-guide">Guide</a>';

			return $links;
		}

		/**
		 * AJAX - Parse the copy-pasted code snippet to extract the parameters
		 */
		function parse_code() {
			check_ajax_referer($this->nonce_name);

			require_once RPP_PLUGIN_DIR . 'src/parser.php';

			$code = urldecode(post_txt('code'));
			$parser = new rpp_parser();
			$params = $parser->parse($code);

			wp_send_json((object) [
					'success' => $params !== false,
					'params' => $params
			]);
		}

		/**
		 * AJAX - Create a human-friendly list of the parameters and values
		 */
		function show_params() {
			check_ajax_referer($this->nonce_name);

			require_once RPP_PLUGIN_DIR . 'src/parser.php';

			$params = json_decode(post_txt('params'));
			$parser = new rpp_parser();
			$html = $parser->print_from($params);

			wp_send_json((object) [
					'success' => $html !== false,
					'html' => $html
			]);
		}

		/**
		 * AJAX - send list of available options for the autocomplete dropdowns in the rules section
		 */
		function list_options() {
			// Check nonce
			check_ajax_referer($this->nonce_name);

			$type = urldecode(post_txt('type'));
			$term = urldecode(post_txt('term'));

			switch ($type) {
				case 'category':
					$terms = get_terms([
						'taxonomy' => 'category',
						'name__like' => $term,
						'fields' => 'id=>name',
						'number' => 64,
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => 0
					]);
					break;

				case 'tag':
					$terms = get_terms([
						'taxonomy' => 'post_tag',
						'name__like' => $term,
						'fields' => 'id=>name',
						'number' => 64,
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => 0
					]);
					break;

				case 'post_page':
					$terms = get_posts([
						'numberposts' => 64,
						'post_type' => ['page'],
						's' => $term,
						'orderby' => 'title',
						'order' => 'ASC'
					]);
					break;

				default:
					$terms = [];
					break;
			}

			// Prepare for output
			$response = [];
			foreach ($terms as $id => $item) {
				if ($type === 'post_page') {
					$title = mb_strlen($item->post_title) > 44 ? mb_substr($item->post_title, 0, 36) . '..' : $item->post_title;
					$response[] = (object) [
							'id' => $item->ID,
							'text' => $title
					];
				}
				else {
					$response[] = (object) [
							'id' => $id,
							'text' => $item
					];
				}
			}

			wp_send_json($response);
		}

		/**
		 * Save parameters to be used for the preview
		 */
		function prepare_preview() {
			// Check nonce
			check_ajax_referer($this->nonce_name);

			$params = post_txt('params');

			// Add or update option - exlude list
			if (add_option(RPP_OPTION_PREVIEW_PARAMS, $params) === false) {
				update_option(RPP_OPTION_PREVIEW_PARAMS, $params);
			}

			wp_send_json((object) [
					'success' => get_option(RPP_OPTION_PREVIEW_PARAMS) === $params
			]);
		}
	}
