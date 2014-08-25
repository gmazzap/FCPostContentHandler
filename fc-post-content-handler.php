<?php
/*
  Plugin Name: Post Content Handler for Fragment Cache
  Plugin URI: https://github.com/Giuseppe-Mazzapica/fcpch
  Description: Cache content for selected posts in singular view. Based upon <a href="https://github.com/Rarst/fragment-cache">Fragment Cache</a> by <a href="http://www.rarst.net/">Andrey "Rarst" Savchenko</a>, can work as an extension of that plugin or in standalone mode.
  Author: Giuseppe Mazzapica
  Version:
  Author URI: http://gm.zoomlab.it
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace GM\FCPCH;

if (
    ! class_exists( 'GM\FCPCH\Admin' )
    && file_exists( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' )
 ) {
    require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
}

add_action( 'plugins_loaded', function() {
    if ( ! class_exists( 'Rarst\Fragment_Cache\Plugin' ) ) {
        return;
    }
    load_plugin_textdomain( 'fcpch', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
    add_filter( 'update_blocker_blocked', function( $blocked ) {
        $blocked['plugins'][] = plugin_basename( dirname( __DIR__ ) . '/fc-post-content-handler.php' );
        return $blocked;
    } );
    custom_init_fragment_cache();
    registerServices();
} );

add_action( 'after_setup_theme', function() {
    if ( FRAGMENT_CACHE_WORKS ) {
        registerCoreHandlers();
        fcpch()->add_fragment_handler( 'fcpch', 'GM\\FCPCH\\Handler' );
    }
} );

if ( is_admin() ) {
    require plugin_dir_path( __FILE__ ) . 'fcpch-admin.php';
}
