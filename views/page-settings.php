<?php include RPP_PLUGIN_DIR . 'views/inc-page-header.php'; ?>

<form id="snippets-form" action="<?php echo esc_url(admin_url('admin.php?page=revpress-plugin')); ?>" method="post">
	<input id="snippets-order" type="hidden" name="ids" value="">
	<input type="hidden" name="submit_snippets" value="1">

	<?php
		wp_nonce_field('rpp-save-snippets');

		foreach ($snippets as $item) {
			include RPP_PLUGIN_DIR . 'views/inc-snippet-form.php';
		}

		// Form to add new snippet
		$item = (object) [
				'prefix' => 'snippet-0-',
				'id' => 0,
				'name' => '',
				'description' => '',
				'params' => '{}',
				'active' => 0,
				'rules' => [
					'all' => false,
					'categories' => [],
					'tags' => [],
					'posts_pages' => []
				]
		];
		include RPP_PLUGIN_DIR . 'views/inc-snippet-form.php';
	?>

	<div id="exclude-roles-container" class="rpp-form bordered-box">
		<div class="label">
			<label>Excluded roles</label>
		</div>
		<div class="option">
			<div>
				<input id="exclude-roles" type="checkbox" name="exclude_roles" value="1" <?php checked(!empty($exclude_roles)); ?> class="rule-roles yesno">
				<span data-target="exclude-roles" class="decor yesno">&zwnj;</span>
				<label for="exclude-roles" class="checker">Don't include snippet for these user roles</label>
			</div>

			<div id="exclude-roles-box" class="select-box<?php echo empty($exclude_roles) ? '' : ' active'; ?>">
				<select id="exclude-role-list" name="role_keys[]" multiple="multiple" class="rule-multi-select">
					<?php foreach ($user_roles as $role_key => $role): ?>
							<option value="<?php echo esc_attr($role_key); ?>" <?php selected(in_array($role_key, $exclude_roles)); ?>><?php echo esc_attr(element('name', $role, 'n/a')); ?></option>
						<?php endforeach; ?>
				</select>

				<div>
					<input id="skip-cache" type="checkbox" name="skip_cache" value="1" <?php checked($skip_cache); ?> class="yesno">
					<span data-target="skip-cache" class="decor yesno">&zwnj;</span>
					<label for="skip-cache" class="checker">Also bypass Cache for these user roles</label>
				</div>
			</div>
		</div>
	</div>
</form>

<form id="snippet-remove-form" action="<?php echo esc_url(admin_url('admin.php?page=revpress-plugin')); ?>" method="post">
	<input id="snippet-remove-id" type="hidden" name="id" value="">
	<input type="hidden" name="submit_remove_snippet" value="1">
</form>

<div id="source-snippet" style="display: none;">
	<div id="import-box-code" class="rpp-code-box">
		<textarea id="code-source" cols="40" rows="8" placeholder="Paste the code snippet you generated in your Publisher Center here" class="code"></textarea>
		<button type="button" onclick="tb_remove()">Cancel</button>
		<button type="button" onclick="parseSourceSnippet()" class="main">Import</button>
		<input id="target-snippet-id" type="hidden" value="">
	</div>
	<div id="import-box-wait" class="rpp-code-wait">
		<p>Please wait..</p>
	</div>
</div>

<div id="snippet-params" style="display: none;">
	<div id="params-box-info" class="rpp-params-box"></div>
	<div id="params-box-wait" class="rpp-params-wait">
		<p>Please wait..</p>
	</div>
</div>

<?php
	include RPP_PLUGIN_DIR . 'views/inc-page-footer.php';
