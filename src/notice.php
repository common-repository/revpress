<?php

	class rpp_notice {

		// Message to display
		private string $message;
		// Type of notification
		private string $type;

		/**
		 * Create a notification
		 * @param string $message - Message to be displayed
		 * @param string $type - Type of notification (error, warning, success, info)
		 * @return void
		 */
		public function __construct(string $message, string $type = 'info') {
			$this->message = $message;
			$this->type = $type;

			add_action('admin_notices', [$this, 'render']);
		}

		/**
		 * Display the notification on the admin screen
		 * @return void
		 */
		public function render() {
			include RPP_PLUGIN_DIR . 'views/admin-notice.php';
		}
	}
