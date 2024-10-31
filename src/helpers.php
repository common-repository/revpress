<?php
	/**
	 * Helper functions
	 */
	if (!function_exists('element')) {

		/**
		 * Returns element value of an item in the array
		 * @param	string $key - Key of item
		 * @param	array $array - Source array
		 * @param	mixed $default - Default value to return if no item found
		 * @return mixed - Item value
		 */
		function element($key, $array, $default = null) {
			if (!is_array($array)) {
				return $default;
			}

			return array_key_exists($key, $array) ? $array[$key] : $default;
		}
	}

	if (!function_exists('prop')) {

		/**
		 * Returns property value of an object
		 * @param string $property - Name of object property
		 * @param object $object - Source object
		 * @param mixed $default - Default value to return if no property found
		 * @return mixed - Property value
		 */
		function prop($property, $object, $default = null) {
			if (!is_object($object) || !isset($object->{$property})) {
				return $default;
			}

			return $object->{$property};
		}
	}

	if (!function_exists('now')) {

		/**
		 * Returns current date/time ISO string
		 * @param bool $date_only - Exclude time
		 * @return string - Formatted date/time
		 */
		function now($date_only = false) {
			if ($date_only) {
				return date('Y-m-d');
			}

			return date('Y-m-d H:i:s');
		}
	}

	if (!function_exists('post_is_null')) {

		/**
		 * Check if a POST variable is not set
		 * @param string $key - POST variable to check
		 * @return bool - POST key unset
		 */
		function post_is_null($key) {
			$value = filter_input(INPUT_POST, $key);

			if ($value === null) {
				return true;
			}

			return false;
		}
	}

	if (!function_exists('post_not_null')) {

		/**
		 * Check if a POST variable has been received
		 * @param string $key - POST variable to check
		 * @return bool - POST key set
		 */
		function post_not_null($key) {
			return !post_is_null($key);
		}
	}

	if (!function_exists('post_checkbox')) {

		/**
		 * Process a checkbox POST variable
		 * @param string $key - POST variable to check
		 * @param mixed $unset - Value to return if not checked
		 * @param mixed $set - Value to return if checked or NULL to return POST value
		 * @return mixed - Processed value
		 */
		function post_checkbox($key, $unset = 0, $set = 1) {
			$value = filter_input(INPUT_POST, $key);

			if (empty($value)) {
				return $unset;
			}

			if (is_null($set)) {
				return $value;
			}

			return $set;
		}
	}

	if (!function_exists('post_array')) {

		/**
		 * Process an array POST variable
		 * @param string $key - POST variable to check
		 * @param mixed $unset - Value to return if not set or not an array
		 * @return mixed - Processed value
		 */
		function post_array($key, $unset = []) {
			$value = filter_input(INPUT_POST, $key, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

			if (!is_array($value)) {
				return $unset;
			}

			return $value;
		}
	}

	if (!function_exists('post_txt')) {

		/**
		 * Process a text POST variable
		 * @param string $key - POST variable to check
		 * @param mixed $unset - Value to return if not present
		 * @param bool $trim - Trim value
		 * @return string - Processed value
		 */
		function post_txt($key, $unset = '', $trim = true) {
			$value = filter_input(INPUT_POST, $key);

			if (empty($value)) {
				return $unset;
			}

			if ($trim) {
				return trim((string) $value);
			}

			return (string) $value;
		}
	}

	if (!function_exists('post_int')) {

		/**
		 * Process an integer POST variable
		 * @param string $key - POST variable to check
		 * @param mixed $unset - Value to return if not present
		 * @return int - Processed value
		 */
		function post_int($key, $unset = 0) {
			$value = filter_input(INPUT_POST, $key, FILTER_SANITIZE_NUMBER_INT);

			if (empty($value)) {
				return $unset;
			}

			return intval($value);
		}
	}

	if (!function_exists('post_float')) {

		/**
		 * Process an float POST variable
		 * @param string $key - POST variable to check
		 * @param mixed $unset - Value to return if not present
		 * @return int - Processed value
		 */
		function post_float($key, $unset = 0.0) {
			$value = filter_input(INPUT_POST, $key, FILTER_SANITIZE_NUMBER_FLOAT);

			if (empty($value)) {
				return $unset;
			}

			return floatval(str_replace(',', '.', $value));
		}
	}

	if (!function_exists('post_bool')) {

		/**
		 * Process a boolean POST variable
		 * @param string $key - POST variable to check
		 * @param mixed $unset - Value to return if not present
		 * @return bool - Processed value
		 */
		function post_bool($key, $unset = 0) {
			$value = filter_input(INPUT_POST, $key);

			if (empty($value)) {
				return $unset;
			}

			return (bool) (intval($value) > 0);
		}
	}
