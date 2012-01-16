<?php
if ( ! class_exists( 'mojoToolboxOptions' ) ) :

	/**
	 * mojoToolboxOptions class.
	 *
	 * @version 1.0
	 * @since 1.0
	 * @todo add media upload support for images.
	 */
	class mojoToolboxOptions {
		
		private $options;
		
		/**
		 * __construct function.
		 *
		 * Stick actions and filters in here.
		 * 
		 * @access public
		 * @return void
		 * @since 1.0
		 */
		function __construct() {
			
			$this->options = get_option( 'mojoToolbox_options' );
			
			register_activation_hook( __FILE__, array( &$this, 'add_defaults' ) );
			register_uninstall_hook( __FILE__, array( &$mojoToolboxOptions, 'delete_plugin_options' ) );
			
			add_action( 'admin_init', array( &$this, 'options_init' ) );
			add_action( 'admin_menu', array( &$this, 'add_options_page' ) );
		}
		
		/**
		 * add_defaults function.
		 * 
		 * @access public
		 * @return void
		 * @since 1.0
		 */
		function add_defaults() {
			$tmp = get_option('mojoToolbox_options');
		    if ( ( $tmp['chk_default_options_db'] == '1' ) || ( ! is_array( $tmp ) ) ) :
		    
				delete_option('mojoToolbox_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
				
				$arr = array(	'avatar_title' => 'My Custom Avatar',
				);
				
				update_option('mojoToolbox_options', $arr);
			
			endif;

		}
		
		/**
		 * delete_plugin_options function.
		 * 
		 * @access public
		 * @static
		 * @return void
		 * @since 1.0
		 */
		public static function delete_plugin_options() {
			delete_option( 'mojoToolbox_options' );
		}
		
		/**
		 * options_init function.
		 * 
		 * @access public
		 * @return void
		 * @since 1.0
		 */
		function options_init() {
			register_setting( 'mojoToolbox_plugin_options', 'mojoToolbox_options', array( &$this, 'validate_options' ) );		
		}
		
		/**
		 * add_options_page function.
		 * 
		 * @access public
		 * @return void
		 * @since 1.0
		 */
		function add_options_page() {
			add_submenu_page( 'options-general.php', __('Mojo Toolbox Options', 'mojo-toolbox' ), __('Mojo Toolbox', 'mojo-toolbox'), 'manage_options', 'mojoToolbox-options', array( $this, 'render_form' )  );
		}
		
		/**
		 * render_form function.
		 * 
		 * @access public
		 * @return void
		 * @since 1.0
		 */
		function render_form() {
		
		?>
			<div class="wrap">
				
				<!-- Display Plugin Icon, Header, and Description -->
				<div class="icon32" id="icon-tools"><br /></div>
				<h2><?php echo _e( 'Mojo Toolbox Options', 'mojo-toolbox' );?></h2>
		
				<!-- Beginning of the Plugin Options Form -->
				<form method="post" action="options.php">
					<?php settings_fields('mojoToolbox_plugin_options'); ?>
		
					<!-- Table Structure Containing Form Controls -->
					<!-- Each Plugin Option Defined on a New Table Row -->
					<table class="form-table">
		
		
						<tr valign="top">
							<th scope="row"><?php echo _e( 'General Options', 'mojo-toolbox' );?></th>
							<td>
								<label><input name="mojoToolbox_options[remove_comments]" type="checkbox" value="1" <?php if ( isset( $this->options['remove_comments'] ) ) { checked( '1', $this->options['remove_comments'] ); } ?> /> <?php echo _e( 'Hide the comments system.', 'mojo-toolbox' );?></label><br />
								<label><input name="mojoToolbox_options[remove_editor]" type="checkbox" value="1" <?php if ( isset( $this->options['remove_editor'] ) ) { checked( '1', $this->options['remove_editor'] ); } ?> /> <?php echo _e( 'Hide the theme editor screen.', 'mojo-toolbox' );?></label><br />
								<label><input name="mojoToolbox_options[hide_wp]" type="checkbox" value="1" <?php if ( isset( $this->options['hide_wp'] ) ) { checked( '1', $this->options['hide_wp'] ); } ?> /> <?php echo _e( 'Hide the Wordpress Meta Generator tag.', 'mojo-toolbox' );?></label><br />
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php echo _e( 'Dashboard Options', 'mojo-toolbox' );?></th>
							<td>
								<label><input name="mojoToolbox_options[dashboard_plugins]" type="checkbox" value="1" <?php if ( isset( $this->options['dashboard_plugins'] ) ) { checked( '1', $this->options['dashboard_plugins'] ); } ?> /> <?php echo _e( 'Hide the "Plugins" dashboard widget.', 'mojo-toolbox' );?></label><br />
								<label><input name="mojoToolbox_options[dashboard_primary]" type="checkbox" value="1" <?php if ( isset( $this->options['dashboard_primary'] ) ) { checked( '1', $this->options['dashboard_primary'] ); } ?> /> <?php echo _e( 'Hide the "Wordpress Blog" dashboard widget.', 'mojo-toolbox' );?></label><br />
								<label><input name="mojoToolbox_options[dashboard_links]" type="checkbox" value="1" <?php if ( isset( $this->options['dashboard_links'] ) ) { checked( '1', $this->options['dashboard_links'] ); } ?> /> <?php echo _e( 'Hide the "Incoming links" dashboard widget.', 'mojo-toolbox' );?></label><br />
								<label><input name="mojoToolbox_options[dashboard_secondary]" type="checkbox" value="1" <?php if ( isset( $this->options['dashboard_secondary'] ) ) { checked( '1', $this->options['dashboard_secondary'] ); } ?> /> <?php echo _e( 'Hide the "Other News" dashboard widget.', 'mojo-toolbox' );?></label><br />
							</td>
						</tr>

						<tr>
							<th scope="row"><?php echo _e( 'Custom Login Logo URL', 'mojo-toolbox' );?></th>
							<td>
								<label><input name="mojoToolbox_options[login_logo]" type="text" value="<?php if ( isset( $this->options['login_logo'] ) ) echo $this->options['login_logo'];?>"/><span style="color:#666666;margin-left:2px;"><?php echo _e( 'The URL for your custom login page logo, (270px x 60px works well!)', 'mojo-toolbox' );?></span></label>
							</td>
						</tr>
						
						<tr>
							<th scope="row"><?php echo _e( 'Custom Login URL', 'mojo-toolbox' );?></th>
							<td>
								<label><input name="mojoToolbox_options[login_url]" type="text" value="<?php if ( isset( $this->options['login_url'] ) ) echo $this->options['login_url'];?>"/><span style="color:#666666;margin-left:2px;"><?php echo _e( 'The URL to goto when clicking on the login screen logo. (defaults to the site url when activated, instead of wordpress.org)', 'mojo-toolbox' );?></span></label>
							</td>
						</tr>
						
						<tr>
							<th scope="row"><?php echo _e( 'Custom Login Description', 'mojo-toolbox' );?></th>
							<td>
								<label><input name="mojoToolbox_options[login_desc]" type="text" value="<?php if ( isset( $this->options['login_desc'] ) ) echo $this->options['login_desc'];?>"/><span style="color:#666666;margin-left:2px;"><?php echo _e( 'The text to show as the page title and hover text on the login screen. (defaults to the Blog Description when activated)', 'mojo-toolbox' );?></span></label>
							</td>
						</tr>
						
						<tr>
							<th scope="row"><?php echo _e( 'Custom Admin Logo URL', 'mojo-toolbox' );?></th>
							<td>
								<label><input name="mojoToolbox_options[admin_logo]" type="text" value="<?php if ( isset( $this->options['admin_logo'] ) ) echo $this->options['admin_logo'];?>"/><span style="color:#666666;margin-left:2px;"><?php echo _e( 'The URL for your custom login page logo, (16px x 16px!)', 'mojo-toolbox' );?></span></label>
							</td>
						</tr>
						
						<tr>
							<th scope="row"><?php echo _e( 'Custom Avatar', 'mojo-toolbox' );?></th>
							<td>
								<label><input name="mojoToolbox_options[avatar_image]" type="text" value="<?php if ( isset( $this->options['avatar_image'] ) ) echo $this->options['avatar_image'];?>"/><span style="color:#666666;margin-left:2px;"><?php echo _e( 'The URL for your custom avatar, (70px x 70px!)', 'mojo-toolbox' );?></span></label>
							</td>
						</tr>
						
						<tr>
							<th scope="row"><?php echo _e( 'Custom Avatar Title', 'mojo-toolbox' );?></th>
							<td>
								<label><input name="mojoToolbox_options[avatar_title]" type="text" value="<?php if ( isset( $this->options['avatar_title'] ) ) echo $this->options['avatar_title'];?>"/><span style="color:#666666;margin-left:2px;"><?php echo _e( 'The title for your custom avatar', 'mojo-toolbox' );?></span></label>
							</td>
						</tr>
						
						<tr>
							<th scope="row"><?php echo _e( 'Dashboard Footer Text', 'mojo-toolbox' );?></th>
							<td>
								<textarea name="mojoToolbox_options[footer_text]" rows="7" cols="50"><?php if ( isset( $this->options['footer_text'] ) ) echo $this->options['footer_text'];?></textarea><br /><span style="color:#666666; margin-left: 2px;"><?php echo _e( 'Text to show in the dashboard footer area', 'mojo-toolbox' );?></span>
							</td>
						</tr>
		
						<tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
						<tr valign="top" style="border-top:#dddddd 1px solid;">
							<th scope="row"><?php echo _e( 'Database Options', 'mojo-toolbox');?></th>
							<td>
								<label><input name="mojoToolbox_options[chk_default_options_db]" type="checkbox" value="1" <?php if ( isset( $this->options['chk_default_options_db'] ) ) { checked( '1', $this->options['chk_default_options_db']); } ?> /> <?php echo _e( 'Restore defaults upon plugin deactivation/reactivation', 'mojo-toolbox' );?></label>
								<br /><span style="color:red;margin-left:2px;"><?php echo _e( 'Only check this if you want to reset plugin settings upon Plugin reactivation', 'mojo-toolbox' );?></span>
							</td>
						</tr>
					</table>
					<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'mojo-toolbox' ); ?>" />
					</p>
				</form>
			</div>
			<?php	

		}
		
		/**
		 * validate_options function.
		 * 
		 * @access public
		 * @param mixed $input
		 * @return void
		 * @since 1.0
		 */
		function validate_options( $input ) {
			$input['login_logo'] = esc_url( $input['login_logo'] );
			$input['login_url'] = esc_url( $input['login_url'] );
			$input['admin_logo'] = esc_url( $input['admin_logo'] );
			$input['avatar_image'] = esc_url( $input['avatar_image'] );
			$input['avatar_title'] = wp_filter_nohtml_kses( $input['avatar_title'] );
			$input['footer_text'] = wp_filter_kses( $input['footer_text'] );
			return $input;
		}
				
	} //end class
endif; //end class if
