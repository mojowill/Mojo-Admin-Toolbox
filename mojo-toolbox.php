<?php
/*
Plugin Name: The Mojo Admin Toolbox
Plugin URI: http://www.mojowill.com/developer/the-mojo-admin-toolbox/
Description: A small toolbox of functions for customising the admin dashboard, useful for hiding stuff from clients!
Version: 1.3.2
Author: mojowill
Author URI: http://www.mojowill.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/


/**
 * Define Plugin URL
 */
 if ( ! defined( 'MOJO_BASE_URL' ) )
	 define( 'MOJO_BASE_URL', plugin_dir_url( __FILE__ ) );
	 
/**
 * Define Plugin Path
 */
 if ( ! defined( 'MOJO_BASE_PATH' ) )
 	define( 'MOJO_BASE_PATH', plugin_dir_path( __FILE__ ) );
 
/**
 * Bring in the classes
 */
require_once( MOJO_BASE_PATH . '/includes/mojo-toolbox.class.php' );
require_once( MOJO_BASE_PATH . '/includes/mojo-toolbox-options.class.php' );

/**
 * Start Plugin
 */ 
$mojoToolbox = new mojoToolbox();