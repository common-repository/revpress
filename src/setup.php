<?php

	/**
	 * Activation, installation
	 */
	function revpress_activate() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$charset_collate = $wpdb->get_charset_collate();

		// Table for snippets
		$sql = "CREATE TABLE IF NOT EXISTS " . RPP_TABLE_SNIPPETS . " (" . PHP_EOL .
			"id mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT," . PHP_EOL .
			"name varchar(128) DEFAULT NULL," . PHP_EOL .
			"description text DEFAULT ''," . PHP_EOL .
			"params text DEFAULT NULL," . PHP_EOL .
			"priority smallint(4) UNSIGNED NOT NULL DEFAULT 0," . PHP_EOL .
			"active tinyint(1) UNSIGNED NOT NULL DEFAULT 0," . PHP_EOL .
			"created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP," . PHP_EOL .
			"PRIMARY KEY  (id)," . PHP_EOL .
			"INDEX idx_priority (priority)," . PHP_EOL .
			"INDEX idx_active (active)" . PHP_EOL .
			") $charset_collate;";
		dbDelta($sql);

		// Table for rules
		$sql = "CREATE TABLE IF NOT EXISTS " . RPP_TABLE_RULES . " (" . PHP_EOL .
			"id mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT," . PHP_EOL .
			"snippet_id mediumint(9) UNSIGNED NOT NULL," . PHP_EOL .
			"target_type varchar(32) DEFAULT ''," . PHP_EOL .
			"target_id mediumint(9) UNSIGNED NOT NULL," . PHP_EOL .
			"PRIMARY KEY  (id)," . PHP_EOL .
			"INDEX idx_snippet_id (snippet_id)," . PHP_EOL .
			"INDEX idx_target_type (target_type)," . PHP_EOL .
			"INDEX idx_target_id (target_id)" . PHP_EOL .
			") $charset_collate;";
		dbDelta($sql);
	}

	function revpress_deactivate() {

	}

	function revpress_uninstall() {
		global $wpdb;

		// Delete tables
		$sql = "DROP TABLE IF EXISTS " . RPP_TABLE_SNIPPETS;
		$wpdb->query($sql);

		$sql = "DROP TABLE IF EXISTS " . RPP_TABLE_RULES;
		$wpdb->query($sql);

		// Remove options
		delete_option(RPP_OPTION_EXCLUDE_ROLES);
		delete_option(RPP_OPTION_SKIP_CACHE);
		delete_option(RPP_OPTION_PREVIEW_PARAMS);
	}
