<?php namespace Lti\Seo;

	/**
	 * The plugin bootstrap file
	 *
	 * @wordpress-plugin
	 * Plugin Name:       LTI SEO
	 * Description:       Search engine optimization made easy: make your content more visible in search engine results.
	 * Version:           0.6.0
	 * Author:            Linguistic Team International
	 * Author URI:        http://dev.linguisticteam.org/
	 * License:           GPL-2.0+
	 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
	 * Text Domain:       lti-seo
	 * Domain Path:       /languages/
	 */

	/**
	 * LTI SEO
	 * Copyright (C) 2015, Bruno De Carvalho - decarvalho.bruno@free.fr
	 *
	 * This program is free software; you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation; either version 2 of the License, or
	 * (at your option) any later version.
	 *
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along
	 * with this program; if not, write to the Free Software Foundation, Inc.,
	 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
	 */


if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

$plugin_dir_path = plugin_dir_path( __FILE__ );
define( 'LTI_SEO_PLUGIN_DIR', $plugin_dir_path );
define( 'LTI_SEO_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'LTI_SEO_VERSION', '0.6.0' );
define( 'LTI_SEO_NAME', 'lti-seo' );

require_once $plugin_dir_path. 'vendor/autoload.php';

register_activation_hook( __FILE__, array( 'Lti\Seo\LTI_SEO', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Lti\Seo\LTI_SEO', 'deactivate' ) );

$plugin = LTI_SEO::get_instance();
$plugin->run();
