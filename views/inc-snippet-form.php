<div data-id="<?php echo esc_attr($item->id > 0 ? $item->id : 'new'); ?>" class="rpp-admin-snippet">
	<div class="title">
		<?php if ($item->id > 0): ?>
				Snippet: <?php echo empty($item->name) ? '<i>name not set</i>' : esc_attr($item->name); ?>
			<?php else: ?>
				<i>Add new snippet</i>
		<?php endif; ?>

		<div class="priority">
			<span class="move">⇳</span>
		</div>
	</div>

	<div class="body snippet-body">
		<div class="rpp-form">
			<div class="label">
				<label for="<?php echo esc_attr($item->prefix); ?>name">Name</label>
			</div>
			<div class="option">
				<input id="<?php echo esc_attr($item->prefix); ?>name" type="text" name="name_<?php echo esc_attr($item->id); ?>" placeholder="Name of the snippet" value="<?php echo esc_attr($item->name); ?>" class="user">
			</div>

			<div class="label">
				<label for="<?php echo esc_attr($item->prefix); ?>code-source">Snippet</label>
			</div>
			<div class="option">
				<div class="">
					<a href="#TB_inline?&width=400&height=260&inlineId=source-snippet" name="Enter code" onclick="selectSourceSnippet('<?php echo esc_attr($item->id); ?>')" class="thickbox" style="color:#009E12;font-size:14px;line-height:24px;">Enter code from Google</a><br />
					<a href="#TB_inline?&width=400&height=260&inlineId=snippet-params" name="Display fields" onclick="showSnippetParams('<?php echo esc_attr($item->id); ?>')" class="thickbox" style="color:#009E12;font-size:14px;line-height:24px;">Display fields</a>
				</div>
				<input id="<?php echo esc_attr($item->prefix); ?>params" type="hidden" name="params_<?php echo esc_attr($item->id); ?>" value="<?php echo esc_attr($item->params); ?>">
			</div>

			<div class="label">
				<label>Include the snippet on</label>
			</div>
			<div class="option">
				<div>
					<input id="<?php echo esc_attr($item->prefix); ?>rule-all" type="radio" name="rule_<?php echo esc_attr($item->id); ?>" value="all" data-id="<?php echo esc_attr($item->id); ?>" <?php checked($item->rules['all']); ?> class="rule-type yesno">
					<span data-target="<?php echo esc_attr($item->prefix); ?>rule-all" class="decor yesno">&zwnj;</span>
					<label for="<?php echo esc_attr($item->prefix); ?>rule-all" class="checker">Entire site</label>
				</div>
				<hr>

				<div>
					<input id="<?php echo esc_attr($item->prefix); ?>rule-category" type="radio" name="rule_<?php echo esc_attr($item->id); ?>" value="category" data-id="<?php echo esc_attr($item->id); ?>" <?php checked(!empty($item->rules['categories'])); ?> class="rule-type yesno">
					<span data-target="<?php echo esc_attr($item->prefix); ?>rule-category" class="decor yesno">&zwnj;</span>
					<label for="<?php echo esc_attr($item->prefix); ?>rule-category" class="checker">Selected categories</label>
				</div>
				<div id="<?php echo esc_attr($item->prefix); ?>rule-category-box" class="select-box<?php echo empty($item->rules['categories']) ? '' : ' active'; ?>">
					<select id="<?php echo esc_attr($item->prefix); ?>rule-category-list" name="category_<?php echo esc_attr($item->id); ?>[]" multiple="multiple" data-list-type="category" class="rule-multi-select">
						<?php foreach ($item->rules['categories'] as $term_id => $term_name): ?>
								<option value="<?php echo esc_attr($term_id); ?>" selected="selected"><?php echo esc_attr($term_name); ?></option>
							<?php endforeach; ?>
					</select>
				</div>
				<hr>

				<div>
					<input id="<?php echo esc_attr($item->prefix); ?>rule-tag" type="radio" name="rule_<?php echo esc_attr($item->id); ?>" value="tag" data-id="<?php echo esc_attr($item->id); ?>" <?php checked(!empty($item->rules['tags'])); ?> class="rule-type yesno">
					<span data-target="<?php echo esc_attr($item->prefix); ?>rule-tag" class="decor yesno">&zwnj;</span>
					<label for="<?php echo esc_attr($item->prefix); ?>rule-tag" class="checker">Selected tags</label>
				</div>
				<div id="<?php echo esc_attr($item->prefix); ?>rule-tag-box" class="select-box<?php echo empty($item->rules['tags']) ? '' : ' active'; ?>">
					<select id="<?php echo esc_attr($item->prefix); ?>rule-tag-list" name="tag_<?php echo esc_attr($item->id); ?>[]" multiple="multiple" data-list-type="tag" class="rule-multi-select">
						<?php foreach ($item->rules['tags'] as $term_id => $term_name): ?>
								<option value="<?php echo esc_attr($term_id); ?>" selected="selected"><?php echo esc_attr($term_name); ?></option>
							<?php endforeach; ?>
					</select>
				</div>
				<hr>

				<div>
					<input id="<?php echo esc_attr($item->prefix); ?>rule-post-page" type="radio" name="rule_<?php echo esc_attr($item->id); ?>" value="post_page" data-id="<?php echo esc_attr($item->id); ?>" <?php checked(!empty($item->rules['posts_pages'])); ?> class="rule-type yesno">
					<span data-target="<?php echo esc_attr($item->prefix); ?>rule-post-page" class="decor yesno">&zwnj;</span>
					<label for="<?php echo esc_attr($item->prefix); ?>rule-post-page" class="checker">Selected Pages</label>
				</div>
				<div id="<?php echo esc_attr($item->prefix); ?>rule-post-page-box" class="select-box<?php echo empty($item->rules['posts_pages']) ? '' : ' active'; ?>">
					<select id="<?php echo esc_attr($item->prefix); ?>rule-post-page-list" name="post_page_<?php echo esc_attr($item->id); ?>[]" multiple="multiple" data-list-type="post_page" class="rule-multi-select">
						<?php foreach ($item->rules['posts_pages'] as $post_item): ?>
								<option value="<?php echo esc_attr($post_item->id); ?>" selected="selected"><?php echo esc_attr($post_item->title); ?></option>
							<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="label">
				<label for="<?php echo esc_attr($item->prefix); ?>description">Notes</label>
			</div>
			<div class="option">
				<textarea id="<?php echo esc_attr($item->prefix); ?>description" name="description_<?php echo esc_attr($item->id); ?>" cols="40" rows="2" placeholder="If you would like to leave yourself any notes about the use of this snippet, you may do so here" class="user"><?php echo esc_attr($item->description); ?></textarea>
			</div>

			<div class="label">
				<label>Enabled</label>
			</div>
			<div class="option">
				<input id="<?php echo esc_attr($item->prefix); ?>active" type="checkbox" name="active_<?php echo esc_attr($item->id); ?>" value="1" <?php checked(intval($item->active) > 0); ?> class="yesno">
				<span data-target="<?php echo esc_attr($item->prefix); ?>active" class="decor yesno">&zwnj;</span>
				<label for="<?php echo esc_attr($item->prefix); ?>active" class="checker">Snippet is active</label>
			</div>
		</div>

		<p class="snippet-actions">
			<a href="<?php echo esc_url(add_query_arg([RPP_PREVIEW_QUERY_PARAM => 'yes', 'cache' => 'skip'], home_url())); ?>" data-id="<?php echo esc_attr($item->id); ?>" class="action preview">Preview</a>
			<?php if ($item->id > 0): ?>
					<button type="button" onclick="removeSnippet(<?php echo esc_attr($item->id); ?>)" class="action delete">Delete</button>
				<?php endif; ?>
		</p>
	</div>
</div>
