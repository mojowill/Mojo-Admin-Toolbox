<?php
/**
 * Remove Comments
 * 
 * Removes all traces of Comments from Wordpress
 * 
 * @author Will Wilson <will@mojowill.com>
 * @version 1.2.4
 * @since 1.0
 */

if ( ! class_exists( 'mojoRemoveComments' ) ) {
	add_action( 'init', array( 'mojoRemoveComments', 'get_object' ) );
	
	
	/**
	 * mojoRemoveComments class.
	 *
	 */
	class mojoRemoveComments {
		
		
		/**
		 * classobj
		 * 
		 * (default value: NULL)
		 * 
		 * @var mixed
		 * @access private
		 * @static
		 */
		static private $classobj = NULL;
		
		
		/**
		 * __construct function.
		 * 
		 * @access public
		 * @return void
		 */
		public function __construct () {
			
			add_filter( 'the_posts',                  array( $this, 'set_comment_status' ) );
			
			add_filter( 'comments_open',              array( $this, 'close_comments'), 10, 2 );
			add_filter( 'pings_open',                 array( $this, 'close_comments'), 10, 2 );
			
			add_action( 'admin_init',                 array( $this, 'remove_comments' ) );
			add_action( 'admin_menu',                 array( $this, 'remove_menu_items' ) );
			add_filter( 'add_menu_classes',           array( $this, 'add_menu_classes' ) );
			
			add_action( 'admin_head',                 array( $this, 'remove_comments_areas' ) );
			
			add_action( 'wp_before_admin_bar_render', array( $this, 'admin_bar_render' ) );
			
			// remove comment feed
			remove_action( 'wp_head', 'feed_links', 2 );
			add_action( 'wp_head', array( $this, 'feed_links' ), 2 );
		}
		
		
		/**
		 * get_object function.
		 * 
		 * @access public
		 * @return void
		 */
		public function get_object () {
			
			if ( NULL === self :: $classobj ) {
				self :: $classobj = new self;
			}
			
			return self :: $classobj;
		}
		

		/**
		 * set_comment_status function.
		 * 
		 * @access public
		 * @param mixed $posts
		 * @return void
		 */
		public function set_comment_status ( $posts ) {
			
			if ( ! empty( $posts ) && is_singular() ) {
				$posts[0]->comment_status = 'closed';
				$posts[0]->post_status = 'closed';
			}
			
			return $posts;
		}
		
		/**
		 * close_comments function.
		 * 
		 * @access public
		 * @param mixed $open
		 * @param mixed $post_id
		 * @return void
		 */
		public function close_comments ( $open, $post_id ) {
			// if not open, than back
			if ( ! $open )
				return $open;
			
			/**
			 * post
			 * 
			 * (default value: get_post( $post_id ))
			 * 
			 * @var mixed
			 * @access public
			 */
			$post = get_post( $post_id );
			if ( $post -> post_type ) // all post types
				return FALSE;
			
			return $open;
		}
		

		/**
		 * remove_comments function.
		 * 
		 * @access public
		 * @return void
		 */
		public function remove_comments () {
			// int values
			foreach ( array( 'comments_notify', 'default_pingback_flag' ) as $option )
				update_option( $option, 0 );
			// string false
			foreach ( array( 'default_comment_status', 'default_ping_status' ) as $option )
				update_option( $option, 'false' );
			
			// all post types
			// alternative define an array( 'post', 'page' )
			foreach ( get_post_types() as $post_type ) {
				// comment status
				remove_meta_box( 'commentstatusdiv', $post_type, 'normal' );
				// remove trackbacks
				remove_meta_box( 'trackbacksdiv', $post_type, 'normal' );
				// remove all comments/trackbacks from tabels
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
			// remove dashboard meta box for recents comments
			remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
		}
		
		/**
		 * remove_menu_items function.
		 * 
		 * @access public
		 * @return void
		 */
		public function remove_menu_items () {
			// Remove menu entries with WP 3.1 and higher
			if ( function_exists( 'remove_menu_page' ) ) {
				remove_menu_page( 'edit-comments.php' );
				remove_submenu_page( 'options-general.php', 'options-discussion.php' );
			} else {
				// unset comments
				unset( $GLOBALS['menu'][25] );
				// unset menuentry Discussion
				unset( $GLOBALS['submenu']['options-general.php'][25] );
			}
		}
		
		/**
		 * add_menu_classes function.
		 * 
		 * @access public
		 * @param mixed $menu
		 * @return void
		 */
		function add_menu_classes ( $menu ) {
			
			/**
			 * menu
			 * 
			 * @var mixed
			 * @access public
			 */
			$menu[20][4] .= ' menu-top-last';
			
			return $menu;
		}
		
		/**
		 * remove_comments_areas function.
		 * 
		 * @access public
		 * @return void
		 */
		public function remove_comments_areas () {
			?>
			<script type="text/javascript">
			//<![CDATA[
			jQuery(document).ready( function($) {
				$( '.table_discussion' ).remove();
			});
			//]]>
			</script>
			<?php
		}
		
		/**
		 * admin_bar_render function.
		 * 
		 * @access public
		 * @return void
		 */
		public function admin_bar_render () {
			/**
			 * GLOBALS
			 * 
			 * @var mixed
			 * @access public
			 */
			$GLOBALS['wp_admin_bar'] -> remove_menu( 'comments' );
		}
		

		/**
		 * feed_links function.
		 * 
		 * @access public
		 * @param array $args (default: array())
		 * @return void
		 */
		public function feed_links( $args = array() ) {
			
			if ( ! current_theme_supports('automatic-feed-links') )
				return;
		
			/**
			 * defaults
			 * 
			 * @var mixed
			 * @access public
			 */
			$defaults = array(
				'separator'	=> '&raquo;',
				'feedtitle'	=> '%1$s %2$s Feed',
			);
		
			/**
			 * args
			 * 
			 * (default value: wp_parse_args( $args, $defaults ))
			 * 
			 * @var mixed
			 * @access public
			 */
			$args = wp_parse_args( $args, $defaults );
		
			echo '<link rel="alternate" type="' . feed_content_type() . '" title="' . 
				esc_attr(sprintf( $args['feedtitle'], get_bloginfo('name'), $args['separator'] )) . 
				'" href="' . get_feed_link() . "\" />\n";
		}
	
	} // end class

} // end if class exists