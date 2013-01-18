<?php
/**
 * Mojo Admin Toolbox
 *
 * A small toolbox of functions for customising the admin dashboard, useful for hiding stuff from clients!
 *
 * @package MojoToolbox
 * @author Will Wilson <will@mojowill.com>
 * @version 1.3.2
 * @since 1.0
 */
 
if ( ! class_exists( 'mojoToolbox' ) ) :
	
	/**
	 * mojoToolbox class.
	 *
	 * @version 1.3
	 * @since 1.0
	 */
	class mojoToolbox {
		
		private $options;
		private $priority;
			
		/**
		 * __construct function.
		 * 
		 * Actions, filters and includes!
		 * @access public
		 * @return void
		 * @since 1.0
		 */
		function __construct() {
		
			new mojoToolboxOptions;
			
			$this->options = get_option( 'mojoToolbox_options' );
			
			/**
			 * Remove Comments if activated
			 */
			if ( isset( $this->options['remove_comments'] ) ) :
				if ( $this->options['remove_comments'] == 1 ) :
					include ( MOJO_BASE_PATH . '/includes/remove-comments.class.php' );
				endif;
			endif;
			
			/**
			 * Set priority
			 */
			if ( isset( $this->options['override_theme'] ) && $this->options['override_theme'] == 1 ) :
				$this->priority = 15;
			else :
				$this->priority = 10;
			endif;
			
			/**
			 * custom_login_logo action
			 */
			add_action( 'login_head', array( $this, 'custom_login_logo' ), $this->priority );
			
			/**
			 * custom_default_avatar filter
			 */
			add_filter( 'avatar_defaults', array( $this, 'custom_default_avatar' ), $this->priority );
			
			/**
			 * remove_dashboard_widgets action
			 */
			add_action( 'wp_dashboard_setup', array( $this, 'remove_dashboard_widgets' ), $this->priority );
			
			/**
			 * remove_editor_menu action
			 */
			add_action( '_admin_menu', array( $this, 'remove_editor_menu'), 1 );
			
			/**
			 * custom_admin_footer filter
			 */
			add_filter( 'admin_footer_text', array( $this, 'custom_admin_footer' ), $this->priority );
			
			/**
			 * custom_login_url filter
			 */
			add_filter( 'login_headerurl', array( $this, 'custom_login_url' ), $this->priority );
			
			/**
			 * custom_login_description filter
			 */
			add_filter( 'login_headertitle', array( $this, 'custom_login_description' ), $this->priority );
			
			/**
			 * Hide Wordpress Generator Tag
			 */
			if ( isset( $this->options['hide_wp'] ) && $this->options['hide_wp'] == 1 ) :
				remove_action( 'wp_head', 'wp_generator' );
			endif;
			
			/**
			 * custom_email_address filter
			 */
			add_filter( 'wp_mail_from', array( $this, 'custom_email_address' ), $this->priority );
			
			/**
			 * custom_email_from_name filter
			 */
			add_filter( 'wp_mail_from_name', array( $this, 'custom_email_from_name' ), $this->priority );

			/**
			 * remove Windows Live writer
			 */
			add_action( 'init', array( $this, 'remove_livewriter' ), $this->priority );
			 
		}
		
		/**
		 * custom_login_logo function.
		 * 
		 * @access public
		 * @return void
		 * @since 1.0
		 * @todo account for funny size images?
		 * @this isn't quite working!
		 */
		function custom_login_logo() {
			if ( isset( $this->options['login_logo'] ) && ( $this->options['login_logo'] != '' ) ) :
				$img = $this->options['login_logo'];
				
				echo ' <style type="text/css">
						h1 a { background-image: url(' . $img . ') !important; }
				</style>';
				
			endif;		
		}
				
		/**
		 * custom_default_avatar function.
		 * 
		 * @access public
		 * @param mixed $avatar_defaults
		 * @return void
		 * @since 1.0
		 */
		function custom_default_avatar( $avatar_defaults ) {
			if ( ( ( isset( $this->options['avatar_image'] ) ) && ( $this->options['avatar_image'] != '' ) ) && ( ( isset( $this->options['avatar_title'] ) ) && ( $this->options['avatar_title'] != '' ) ) ) :
			
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
		 * @since 1.0
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
		 * @since 1.0
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
		 * @since 1.0
		 */
		function custom_admin_footer() {
			if ( isset( $this->options['footer_text'] ) && $this->options['footer_text'] != '' ) :
				echo $this->options['footer_text'];
			else :
				echo __( 'You can edit this footer message in the Mojo Admin Toolbox Options', 'mojo-toolbox' );
			endif;
		}
		
		/**
		 * custom_login_url function.
		 * 
		 * @access public
		 * @param mixed $url
		 * @return void
		 * @since 1.0
		 */
		function custom_login_url( $url ) {
			if ( isset( $this->options['login_url'] ) && $this->options['login_url'] != '' ) :
		
				return $this->options['login_url'];
		
			else :
		
				return get_home_url();
		
			endif;
		}
		
		/**
		 * custom_login_description function.
		 * 
		 * @access public
		 * @param mixed $title
		 * @return void
		 * @since 1.0
		 */
		function custom_login_description( $title ) {
			if ( isset( $this->options['login_desc'] ) && $this->options['login_desc'] != '' ) :
			
				return $this->options['login_desc'];
			
			else :
			
				return get_bloginfo( 'description' );
			
			endif;
		}
		
		/**
		 * custom_email_address function.
		 * 
		 * @access public
		 * @param mixed $old
		 * @return void
		 * @since 1.1
		 */
		function custom_email_address( $old ) {
			if ( isset( $this->options['email_address'] ) && $this->options['email_address'] != '' ) :
				return $this->options['email_address'];
			endif;
		}
		
		/**
		 * custom_email_from_name function.
		 * 
		 * @access public
		 * @param mixed $old
		 * @return void
		 * @since 1.1
		 */
		function custom_email_from_name( $old ) {
			if ( isset( $this->options['email_from'] ) && $this->options['email_from'] != '' ) :
				return $this->options['email_from'];
			endif;
		}

		/**
		 * remove windows livewriter links in the head
		 * 
		 * @return void
		 * @since 1.3 
		 */
		function remove_livewriter() {
			if ( isset( $this->options['byebye_livewriter'] ) && $this->options['byebye_livewriter'] == 1 ) :
				remove_action( 'wp_head', 'rsd_link' );
				remove_action( 'wp_head', 'wlwmanifest_link' );
			endif;
		}
				
	} // End Class
	
endif; // End Class if
