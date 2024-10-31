<?php

	class rpp_parser {

		const tag_start = 'basicSubscriptions.init({';
		const tag_end = '});';

		private $vars;

		function __construct() {
			$this->vars = [
				'alwaysShow' => (object) ['type' => 'bool', 'default' => false],
				'autoPromptType' => (object) ['type' => 'enum', 'values' => ['contribution', 'subscription', 'none'], 'default' => null],
				'clientOptions.theme' => (object) ['type' => 'enum', 'values' => ['light', 'dark'], 'default' => null],
				'clientOptions.lang' => (object) ['type' => 'regexp', 'pattern' => '/[a-z]{2}/i', 'default' => null],
				'isAccessibleForFree' => (object) ['type' => 'bool', 'default' => null],
				'isPartOfProductId' => (object) ['type' => 'regexp', 'pattern' => '/\w.+:\w.+/i', 'default' => null],
				'isPartOfType' => (object) ['type' => 'regexp', 'pattern' => '/\w+/i', 'default' => ['Product']],
				'type' => (object) ['type' => 'regexp', 'pattern' => '/[a-z]+/i', 'default' => 'NewsArticle']
			];
		}

		private function parse_from_json($params_object, $flatten = true) {
			// Decode the source object
			$source_params = json_decode($params_object);
			// Decoding failed
			if (is_null($source_params)) {
				return false;
			}

			// Create our own params object
			$params = (object) [];
			foreach ($this->vars as $key_name => $var) {
				$key = mb_strtolower($key_name);
				if ($flatten) {
					$key_parts = explode('.', $key);
					if (count($key_parts) === 1) {
						$input = prop($key, $source_params, null);
					}
					else {
						$parent = prop($key_parts[0], $source_params, null);
						$input = prop($key_parts[1], $parent, null);
					}
				}
				else {
					$input = prop($key, $source_params, null);
				}

				// Parameter not set in source
				if (is_null($input)) {
					// Use default if set
					if (!is_null($var->default)) {
						$params->{$key} = $var->default;
					}

					continue;
				}

				// Validate received input
				switch ($var->type) {
					case 'bool':
						$params->{$key} = $input === true || $input === 'true' || intval($input) === 1;
						break;

					case 'enum':
						if (in_array($input, $var->values)) {
							$params->{$key} = $input;
						}
						else if (!is_null($var->default)) {
							$params->{$key} = $var->default;
						}
						break;

					case 'regexp':
						if (is_array($input)) {
							$ok = count($input) > 0;
							foreach ($input as $input_elem) {
								$ok &= preg_match($var->pattern, $input_elem) === 1;
							}
						}
						else {
							$ok = preg_match($var->pattern, $input) === 1;
						}

						if ($ok) {
							$params->{$key} = $input;
						}
						else if (!is_null($var->default)) {
							$params->{$key} = $var->default;
						}
						break;
				}
			}

			return $params;
		}

		function parse($raw_code) {
			$code = str_replace(["\n", "\r", "\t"], '', $raw_code);

			$pos_start = mb_stripos($code, rpp_parser::tag_start, 0);
			if ($pos_start === false) {
				return false;
			}
			$pos_start += mb_strlen(rpp_parser::tag_start);

			$pos_end = mb_stripos($code, rpp_parser::tag_end, $pos_start);
			if ($pos_end === false) {
				return false;
			}

			// Re-create params object as JSON string
			$params_object_raw = '{ ' . trim(trim(mb_substr($code, $pos_start, $pos_end - $pos_start)), ',') . ' }';

			// Fix invalid JSON from Google
			$search = [' true,', ' false,'];
			$replace = [' "true",', ' "false",'];
			$param_names = array_keys($this->vars);
			$param_names[] = 'clientOptions';
			$param_names[] = 'theme';
			$param_names[] = 'lang';
			foreach ($param_names as $name) {
				$search[] = $name . ':';
				$replace[] = '"' . mb_strtolower($name) . '":';
			}
			$params_json = str_ireplace($search, $replace, $params_object_raw);
			$params = $this->parse_from_json($params_json, true);

			return json_encode($params);
		}

		function write_snippet($params_json) {
			$params_clean = $this->parse_from_json($params_json, false);
			$params = (object) [];

			foreach ($this->vars as $key_name => $var) {
				$key = mb_strtolower($key_name);
				$value = prop($key, $params_clean);

				if (is_null($value)) {
					continue;
				}

				$key_parts = explode('.', $key_name);
				if (count($key_parts) === 1) {
					$params->{$key_name} = $value;
				}
				else {
					// Create parent if doesn't exist yet
					if (is_null(prop($key_parts[0], $params))) {
						$params->{$key_parts[0]} = (object) [];
					}

					// Set child vlaue
					$params->{$key_parts[0]}->{$key_parts[1]} = $value;
				}
			}

			include RPP_PLUGIN_DIR . 'views/snippet-template.php';
		}

		function print_from($params) {
			$html = '<ul>';

			foreach ($this->vars as $key_name => $var) {
				$key = mb_strtolower($key_name);
				$input = prop($key, $params, null);

				// Parameter not set in source
				if (is_null($input)) {
					// No default for this parameter
					if (is_null($var->default)) {
						continue;
					}

					// Use default
					$value = $var->default;
				}
				// Use received value
				else {
					$value = $input;
				}

				// Make array to text
				if (is_array($value)) {
					$value = implode(', ', $value);
				}
				// Print booleans human-friendly
				else if (is_bool($value)) {
					$value = $value ? 'True' : 'False';
				}

				// Print parameter
				$html .= '<li><b>' . str_replace('.', ' / ', $key_name) . '</b>: ' . $value . '</li>';
			}

			return $html . '</ul>';
		}
	}
