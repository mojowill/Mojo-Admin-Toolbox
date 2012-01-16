<?php
/**
 * Mojo Admin Toolbox
 *
 * A small toolbox of functions for customising the admin dashboard, useful for hiding stuff from clients!
 *
 * @package MojoToolbox
 * @author Will Wilson <will@mojowill.com>
 * @version 1.0
 * @since 1.0
 */
 
if ( ! class_exists( 'mojoToolbox' ) ) :
	
	/**
	 * mojoToolbox class.
	 *
	 * @version 1.0
	 * @since 1.0
	 */
	class mojoToolbox {
		
		private $options;
			
		/**
		 * __construct function.
		 * 
		 * Actions, filters and includes!
		 * @access public
		 * @return void
		 */
		function __construct() {
		
			new mojoToolboxOptions;
			
			$this->options = get_option( 'mojoToolbox_options' );
			
			/**
			 * Remove Comments if activated
			 */
			if ( isset( $options['remove_comments'] ) ) :
				if ( $options['remove_comments'] == 1 ) :
					include ( MOJO_BASE_PATH . '/includes/remove-comments.class.php' );
				endif;
			endif;
			
			/**
			 * custom_login_logo action
			 */
			add_action( 'admin_head', array( &$this, 'custom_login_logo' ) );
			
			/**
			 * custom_login_logo action
			 */
			add_action( 'admin_head', array( &$this, 'custom_admin_logo' ) );
			
			/**
			 * custom_default_avatar filter
			 */
			add_filter( 'avatar_defaults', array( &$this, 'custom_default_avatar' ) );
			
			/**
			 * remove_dashboard_widgets action
			 */
			add_action( 'wp_dashboard_setup', array( &$this, 'remove_dashboard_widgets' ) );
			
			/**
			 * remove_editor_menu action
			 */
			add_action( '_admin_menu', array( &$this, 'remove_editor_menu'), 1 );
			
			/**
			 * custom_admin_footer filter
			 */
			add_filter( 'admin_footer_text', array( &$this, 'custom_admin_footer' ) );
			
			/**
			 * custom_login_url filter
			 */
			add_filter( 'login_headerurl', array( &$this, 'custom_login_url' ) );
			
			/**
			 * custom_login_description filter
			 */
			add_filter( 'login_headertitle', array( &$this, 'custom_login_description' ) );
			
			/**
			 * Hide Wordpress Generator Tag
			 */
			if ( isset( $this->options['hide_wp'] ) && $this->options['hide_wp'] == 1 ) :
				remove_action( 'wp_head', 'wp_generator' );
			endif;
			 
		}
		
		/**
		 * custom_login_logo function.
		 * 
		 * @access public
		 * @return void
		 * @todo account for funny size images?
		 */
		function custom_login_logo() {
			if ( isset( $this->options['login_logo'] ) && ( $this->options['login_logo'] != '' ) ) : ?>
				
				<style type="text/css">
						h1 a { background-image: url('<?php echo $this->options['login_logo'];?> ') !important; }
				</style>
				
			<?php endif;		
		}
		
		/**
		 * custom_admin_logo function.
		 * 
		 * @access public
		 * @return void
		 * @todo account for funny size images.
		 */
		function custom_admin_logo() {
			if ( isset( $this->options['admin_logo'] ) && ( $this->options['admin_logo'] != '' ) ) : ?>
				
				<style type="text/css">
					#header-logo { background-image: url('<?php echo $this->options['admin_logo'];?> ') !important; }
				</style>
			
			<?php endif;
		}
		
		/**
		 * custom_default_avatar function.
		 * 
		 * @access public
		 * @param mixed $avatar_defaults
		 * @return void
		 */
		function custom_default_avatar( $avatar_defaults ) {
			if ( ( isset( $this->options['avatar_image'] ) ) && ( $this->options['avatar_image'] != '' ) && ( isset( $this->options['avatar_title'] ) ) && ( $this->options['avatar_title'] != '' ) ) :
			
				$mojo_avatar = $this->options['avatar_image'];
				$avatar_defaults[ $mojo_avatar ] = $this->options['avatar_title'];
			
				return $avatar_defaults;
			
			else :
			
				return $avatar_defaults;
			
			endif;
		}
		
		/**
		 * remove_dashboard_widgets function.
		 * 
		 * @access public
		 * @return void
		 */
		function remove_dashboard_widgets() {
			global $wp_meta_boxes;
						
			if ( isset( $this->options['dashboard_plugins'] ) && $this->options['dashboard_plugins'] == 1 ) :
				unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
			endif;
			
			if ( isset( $this->options['dashboard_primary'] ) && $this->options['dashboard_primary'] == 1 ) :
				unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
			endif;
			
			if ( isset( $this->options['dashboard_links'] ) && $this->options['dashboard_links'] == 1 ) :
				unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
			endif;
			
			if ( isset( $this->options['dashboard_secondary'] ) && $this->options['dashboard_secondary'] == 1 ) :
				unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
			endif;
		}
		
		/**
		 * remove_editor_menu function.
		 * 
		 * @access public
		 * @return void
		 */
		function remove_editor_menu() {
			if ( isset( $this->options['remove_editor'] ) && $this->options['remove_editor'] == 1 ) :
				remove_action( 'admin_menu', '_add_themes_utility_last', 101 );
			endif;
		}
		
		/**
		 * custom_admin_footer function.
		 * 
		 * @access public
		 * @return void
		 */
		function custom_admin_footer() {
			if ( isset( $this->options['footer_text'] ) && $this->options['footer_text'] != '' ) :
				echo $this->options['footer_text'];
			endif;
		}
		
		/**
		 * custom_login_url function.
		 * 
		 * @access public
		 * @param mixed $url
		 * @return void
		 */
		function custom_login_url( $url ) {
			if ( isset( $this->options['login_url'] ) && $this->options['login_url'] != '' ) :
		
				return $this->options['login_url'];
		
			else :
		
				return home_url();
		
			endif;
		}
		
		/**
		 * custom_login_description function.
		 * 
		 * @access public
		 * @param mixed $title
		 * @return void
		 */
		function custom_login_description( $title ) {
			if ( isset( $this->options['login_desc'] ) && $this->options['login_desc'] != '' ) :
			
				return $this->options['login_desc'];
			
			else :
			
				return bloginfo( 'description' );
			
			endif;
		}
				
	} // End Class
	
endif; // End Class if
