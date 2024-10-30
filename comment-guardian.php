<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.codepa.de
 * @since             1.0.0
 * @package           Comment_Guardian
 *
 * @wordpress-plugin
 * Plugin Name:       Comment Guardian – Remove Spam By Language Detection
 * Plugin URI:        https://www.codepa.de/
 * Description:       Comment Guardian is an innovative and intelligent spam protection that eliminates comment spam 99.9% of the time.
 * Version:           1.0.0
 * Author:            Codepa
 * Author URI:        www.codepa.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       comment-guardian
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'COMMENT_GUARDIAN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-comment-guardian-activator.php
 */
function activate_comment_guardian() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-comment-guardian-activator.php';
	Comment_Guardian_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_comment_guardian' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-comment-guardian.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_comment_guardian() {

	$plugin = new Comment_Guardian();
	$plugin->run();

}
run_comment_guardian();
