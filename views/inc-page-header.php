<div class="rpp-admin">
	<div id="rpp-header" class="header">
		<div class="header-content" style="border-bottom: #009E12 1px solid;">
		<div>
			<img src="<?php echo esc_url(RPP_PLUGIN_URL); ?>/assets/image/logo.svg" alt="RevPress" />
			<a href="<?php echo esc_url(admin_url('admin.php')); ?>?page=revpress-plugin"<?php echo $rev_page === 'settings' ? ' class="active"' : ''; ?>>Settings</a>
			<a href="<?php echo esc_url(admin_url('admin.php')); ?>?page=revpress-plugin-guide"<?php echo $rev_page === 'guide' ? ' class="active"' : ''; ?>>Guide</a>
		</div>

		<?php if ($rev_page === 'settings'): ?>
			<div class="settings-options">
				<input id="priority-mode" type="checkbox" name="priority_mode" value="1" class="yesno">
				<span data-target="priority-mode" class="decor yesno">&zwnj;</span>
				<label for="priority-mode">Edit snippet priorities</label>

				<button id="snippets-save-button" class="save-button">Save</button>
			</div>
		<?php endif; ?>
		</div>
	</div>

	<div id="rpp-notices" class="notices-list"></div>

	<div class="main">
		<div class="bordered-box">
