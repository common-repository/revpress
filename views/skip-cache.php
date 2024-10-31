<script>
	const admin_url = '<?php echo esc_url(admin_url()); ?>'

	jQuery(document).ready(function($) {
		$('a[href^="<?php echo esc_url(site_url()); ?>"]').each(function() {
			let href = $(this).attr('href')

			if (href.indexOf(admin_url) < 0) {
				$(this).attr('href', href + (href.indexOf('?') !== -1 ? '&' : '?') + 'cache=skip')
			}
		})
	})
</script>
