<?php

/**
 * Fired during plugin activation
 *
 * @link       www.codepa.de
 * @since      1.0.0
 *
 * @package    Comment_Guardian
 * @subpackage Comment_Guardian/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Comment_Guardian
 * @subpackage Comment_Guardian/includes
 * @author     Codepa <info@codepa.de>
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-comment-guardian-admin.php';
Comment_Guardian_Admin::add_notice();
class Comment_Guardian_Activator {
	

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
	}

}
