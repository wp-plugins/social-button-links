<?php
/**
 * Plugin Name: Social Button Links 
 * Plugin URI: 
 * Description: Allows you to create a shortcode of social button links for your pages.
 * Version: 1.0.0
 * Author: Tricks Of IT
 * Author URI: http://www.tricksofit.com/
 */


 class toiSocialButtonLinks {
	var $domain = 'social-buttons-links';
	
	function __construct() {
	
		if ( is_admin() ){
			add_action( 'admin_menu', array($this, 'sobl_add_settings_menu'));
			add_filter( 'plugin_action_links', array($this, 'sobl_add_settings_link'), 20, 2 );
		}
	}
	
	function sobl_add_settings_menu(){
		add_options_page( __( 'Social Button Links', $this->domain ), __( 'Social Button Links', $this->domain ), 'manage_options', 'social-buttons-links', array($this,  'sobl_settings_page') );
	}
	
	function sobl_add_settings_link($links, $file){
		if ( plugin_basename( __FILE__ ) == $file ) {
			$settings_link = '<a href="' . add_query_arg( array( 'page' => 'social-buttons-links' ), admin_url( 'options-general.php' ) ) . '">' . __( 'Settings', $this->domain ) . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;
	}
	
	function sobl_settings_page(){
		if (!current_user_can('manage_options'))  {
			wp_die('You do not have sufficient permissions to access this page.');
		}
		
		if (!empty($_POST) && isset($_POST['sobl_settings']) && check_admin_referer('sobl_update_settings','sobl_nonce_field'))
		{
			$sobl_settings = $_POST['sobl_settings'];
		}
		
		?>
		<div class="wrap">
		<?php if ( function_exists('screen_icon') ) screen_icon(); ?>
		<h2><?php _e( 'Social Button Links Settings', $this->domain ); ?></h2>
		
		<form method="post" action="">
			<?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('sobl_update_settings','sobl_nonce_field'); ?>
			
			<table class="form-table"><tbody>
				<tr valign="top">
					<th scope="row"><?php _e( 'Facebook', $this->domain ); ?></th>
					<td>
						<fieldset>
							<label for="sobl-facebook_link"><input name="sobl_settings[facebook]" type="text" id="sobl-facebook_link" value="<?php echo isset($sobl_settings['facebook'])?$sobl_settings['facebook']:'' ?>"  /> <span style="color:#aaa;"><?php _e( 'Enter your facebook page URL', $this->domain ); ?></span></label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Google Plus', $this->domain ); ?></th>
					<td>
						<fieldset>
							<label for="sobl-google_link"><input name="sobl_settings[googleplus]" type="text" id="sobl-google_link" value="<?php echo isset($sobl_settings['googleplus'])?$sobl_settings['googleplus']:'' ?>"  /> <span style="color:#aaa;"><?php _e( 'Enter your google plus page URL', $this->domain ); ?></span></label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Twitter', $this->domain ); ?></th>
					<td>
						<fieldset>
							<label for="sobl-twitter_link"><input name="sobl_settings[twitter]" type="text" id="sobl-twitter_link" value="<?php echo isset($sobl_settings['twitter'])?$sobl_settings['twitter']:'' ?>"  /> <span style="color:#aaa;"><?php _e( 'Enter your twitter account URL', $this->domain ); ?></span></label>
						</fieldset>
					</td>
				</tr>
				
				
				<tr valign="top">
					<th scope="row"><?php _e( 'LinkedIn', $this->domain ); ?></th>
					<td>
						<fieldset>
							<label for="sobl-linkedin_link"><input name="sobl_settings[linkedin]" type="text" id="sobl-linkedin_link" value="<?php echo isset($sobl_settings['linkedin'])?$sobl_settings['linkedin']:'' ?>"  /> <span style="color:#aaa;"><?php _e( 'Enter your linkedin profile URL', $this->domain ); ?></span></label>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php _e( 'Pinterest', $this->domain ); ?></th>
					<td>
						<fieldset>
							<label for="sobl-pinterest_link"><input name="sobl_settings[pinterest]" type="text" id="sobl-pinterest_link" value="<?php echo isset($sobl_settings['pinterest'])?$sobl_settings['pinterest']:'' ?>"  /> <span style="color:#aaa;"><?php _e( 'Enter your pinterest page URL', $this->domain ); ?></span></label>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php _e( 'YouTube', $this->domain ); ?></th>
					<td>
						<fieldset> <label for="sobl-youtube_link"><input name="sobl_settings[youtube]" type="text" id="sobl-youtube_link" value="<?php echo isset($sobl_settings['youtube'])?$sobl_settings['youtube']:'' ?>" /> <span style="color:#aaa;"><?php _e( 'Enter your youtube channel URL', $this->domain ); ?></span></label>
						</fieldset>
					</td>
				</tr>
				
				
				</tbody>
			</table>
			<p class="submit">
			  <input type="hidden" name="action" value="sobl_update_settings"/>
			  <input type="submit" name="sobl_update_settings" class="button-primary" value="<?php _e('Generate Shortcode', $this->domain); ?>"/>
			</p>
				
		</form>
		
		
		<?php
			if(isset($sobl_settings) && !empty($sobl_settings)){
				$short_code = "";
				foreach($sobl_settings as $key=>$val){
					if(!empty($val)){
						$short_code .= $key.'="'.$val.'" ';
					}
				}
				
				echo "<div style=''>";
				echo "<p>Copy the below shortcode and place into your WordPress site.";
				echo "</p>";
				echo "<textarea cols='60' rows='3' onclick='this.select();this.focus();'>";
				echo "[social-links $short_code]";
				echo "</textarea>";
				
				echo "</div>";
				
			}
		?>
		
	</div>
	
	
<?php
	}
	
	public static function socialbuttons_display( $atts ) {
		
		$content = '';
		$dir = plugin_dir_url( __FILE__ );
		if(isset($atts) && !empty($atts) && count($atts)){
			$content .= '<ul class="socialblinks">';
			foreach($atts as $k=>$v){
				$content .= "<li claass='$k'><a target='_blank' href='$v' class='$k'/><img src='$dir/images/$k.png' alt='$k' width='36px' height='36px' /></a></li>";
			}
			$content .= '</ul>';
			
			$content .= '<style>';
			$content .= 'ul.socialblinks{margin: 10px 0;}';
			$content .= '.socialblinks li{display:inline;margin-right: 8px;}';
			$content .= '.socialblinks li a{text-decoration: none;}';
			
			$content .= '</style>';
			
		}
		
		return $content;
	}
	
}

new toiSocialButtonLinks();

add_shortcode( 'social-links', array( 'toiSocialButtonLinks', 'socialbuttons_display' ) );
 
?>