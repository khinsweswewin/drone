<?php 

/**
 * Add Admin Menu
 */
function dscnr_news_room_admin_menu() {
	add_options_page(
		'Newroom Setting',
		'Newroom Setting',
		'manage_options',
		'dscnr',
		'dscnr_news_room_page'
	);
}
add_action( 'admin_menu', 'dscnr_news_room_admin_menu' );

/**
 * Display Setting Screen
 */
function dscnr_news_room_page() {
	?>
	<div class="form">
		<h2>Newsroom Setting</h2>
		<form method="post" action="options.php" autocomplete="off">
			<?php wp_nonce_field( 'options-options' ); ?>
			<input type="hidden" name="type" value="dscnr_new_room_settings">
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							Search Keyword for News Section ( English )
						</th>
						<td>
							<textarea name="dscnr_search_keyword_eng" rows="10" cols="30" id="dscnr_search_keyword_eng"><?php echo esc_attr( get_option( 'dscnr_search_keyword_eng' ) ); ?></textarea>
						</td>
					</tr>
					<tr>
						<th>
							Translate to Japanese
						</th>
						<td>
							<input name="translate_to_jp" type="checkbox" id="translate_to_jp" value="1" <?php checked( '1', get_option( 'translate_to_jp' ) ); ?> />
						</td>
					</tr>
					<tr>
						<th>
							Search Keyword for News Section ( Japanese )
						</th>
						<td>
							<textarea name="dscnr_search_keyword_jp" rows="10" cols="30" id="dscnr_search_keyword_jp"><?php echo esc_attr( get_option( 'dscnr_search_keyword_jp' ) ); ?></textarea>
						</td>
					</tr>
					<tr>
						<th>
							Exclude Competitors List
						</th>
						<td>
							<textarea name="dscnr_exclude_competitors" rows="10" cols="30"><?php echo esc_attr( get_option( 'dscnr_exclude_competitors' ) ); ?></textarea>
						</td>
					</tr>
					<tr>
						<th>
							Exclude Domains List
						</th>
						<td>
							<textarea name="dscnr_exclude_domains" rows="10" cols="30"><?php echo esc_attr( get_option( 'dscnr_exclude_domains' ) ); ?></textarea>
						</td>
					</tr>
					<tr>
						<th>
							Exclude URL List
						</th>
						<td>
							<textarea name="dscnr_exclude_urls" rows="10" cols="30"><?php echo esc_attr( get_option( 'dscnr_exclude_urls' ) ); ?></textarea>
						</td>
					</tr>
					<tr>
						<th>
							Twitter User Name
						</th>
						<td>
							<input type="text" name="dscnr_twitter_user_name" id="dscnr_twitter_user_name" value="<?php echo esc_attr( get_option( 'dscnr_twitter_user_name' ) ); ?>" size="30"/>
						</td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="option_page" value="options" />
			<input type="hidden" name="page_options" value="dscnr_exclude_competitors,dscnr_search_keyword_eng,dscnr_search_keyword_jp,dscnr_exclude_domains,dscnr_exclude_urls,dscnr_twitter_user_name,translate_to_jp" />
			<p class=""><input type="submit" value="<?php echo __('Save')?>" class="button button-primary" id="dscnr_news_room_settings_submit" name="submit"></p>
		</form>
	</div>
<?php	
}
