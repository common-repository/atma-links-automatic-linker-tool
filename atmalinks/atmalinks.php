<?php
/*
Plugin Name: AtmaLinks Automatic Linker Tool
Plugin URI: http://www.atmalinks.com/wordpress/
Description: AtmaLinks Wordpress Plugin - Add Affiliate Links to your page without any effort using AtmaLinks automatic linking system. Insert simple span classes and let us do affiliate tracking and signup for you, or activate the automatic links and sit back while our high-performance text matching engine find product names on your site as your users browse it. 
Version: 1.0
Author: Atma Tech, LLC.
Author URI: http://www.atmalinks.com/
*/

add_action('admin_menu', 'atmalinks_display_settings'); 
add_action('admin_init', 'register_atma_settings');
add_action('admin_print_styles', 'atmalinks_add_scripts'); 
add_filter('mce_css', 'atmalinks_add_editor_styles'); 
add_action('wp_footer', 'atmalinks_write_footer_scripts'); 

function register_atma_settings() {
	register_setting("atma_options", "atma_user_id", 'intval'); 
}

function atmalinks_write_footer_scripts() {
	$user_id = atmalinks_get_user_id(); 
	echo "<script src='http://www.atmalinks.com/atma_linker.js?p=$user_id'></script>\n"; 
	echo "<script>try {var atma_linker = _atma_linker($user_id);\ndocument.write(unescape(\"%3Cscript src='\") + atma_linker.write_script_src() + unescape(\"' type='text/javascript'%3E%3C/script%3E\"));} catch(err) {}</script>";
}

function atmalinks_display_settings() {
	if (function_exists('add_meta_box')) {
		add_meta_box('atmalinks_sb_box', 'AtmaLinks Controls', 'atmalinks_edit_post_sidebar', 'post', 'side', 'high'); 
	}
	add_options_page('Atma Links Settings', 'Atma Links Settings', 8, "atmalinks_settings_menu", 'atmalinks_show_settings_subpage'); 
} 

function atmalinks_add_editor_styles($url) {
	if (!empty($url)) {
		$url .= ',';
	}
	$url = WP_PLUGIN_URL . '/atmalinks/css/atmalinks_wp.css'; 
	return $url; 
}

function atmalinks_add_scripts() {
	wp_enqueue_script('atmalinks_wp_js', WP_PLUGIN_URL . '/atmalinks/js/atma_wp_plugin.js', array('jquery')); 
}

function atmalinks_get_user_id() {
	$atma_user_id = get_option("atma_user_id"); 
	if (!$atma_user_id || $atma_user_id == "") {
		$atma_user_id = 0; 
	}
	return $atma_user_id; 
}

function atmalinks_edit_post_sidebar() {
	?>
	<div class="atma_sb_outer">
		<div class="atma_sb_cont">
			<div class="atma_sb_header">
				<h4>Lazy Links Controls</h4>
				<p>These controls only work with the visual editor</p>
				<input type="button" class="atma-on-highlight-button button button-highlighted" onclick="atmalinks_wrap_in_lazy_link();" value="Create Lazy Link" /><br />
				<input type="button" class="atma-on-highlight-button button button-highlighted" onclick="atmalinks_remove_lazy_link();" value="Remove Lazy Link" /><br />
				<input type="button" class="button button-highlighted" onclick="atmalinks_clear_all()" value="Remove all Lazy Links on page" /><br />
			</div>
		</div>
	</div>

	<?php
}

function atmalinks_show_settings_subpage() {
	$atma_user_id = atmalinks_get_user_id(); 
?>
<div class="wrap" action="options.php">
	<h2>AtmaLinks Plugin Settings</h2>
	<p>These settings control the user id of your Atma Links account, allowing our script to correctly run on your site. For all other
	link control you use the <a href="http://www.atmalinks.com/publisher/account/">Atma Links control panel</a>.</p>
	<p>The plugin will not work unless you are an AtmaLinks user. To register, go to <a href="http://www.atmalinks.com/register/">http://www.atmalinks.com/register/</a>.</p> 
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">User ID:</th>
				<td><input type="text" size="6" name="atma_user_id" value="<?php echo $atma_user_id; ?>" />
					<span class="description"></span>
				</td>
			</tr>
		</table>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="atma_user_id" />
		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Change'); ?>" />
	</form>
	<h2>Atma Links Administrative Control Panel</h2>
	<p>This is an embedded version of the control panel available at <a href="http://www.atmalinks.com/publishers/control/">atmalinks.com</a>.</p>
	<iframe src="http://www.atmalinks.com/publishers/accounts/" width="85%" height="800" style="border:1px solid #DDDDDD">
		<p>The embedded control panel will only work if your browser supports iframes. If you are seeing this message, please access the 
		control panel by visiting <a href="http://www.atmalinks.com/publishers/control/"></a>.</p>
	</iframe>
</div>

<?php
}
?>
