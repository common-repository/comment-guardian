<?php
use LanguageDetection\Language;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.codepa.de
 * @since      1.0.0
 *
 * @package    Comment_Guardian
 * @subpackage Comment_Guardian/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Comment_Guardian
 * @subpackage Comment_Guardian/public
 * @author     Codepa <info@codepa.de>
 */
class Comment_Guardian_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    public function filter_comments_by_language($approved, $commentdata)
    {
        // Set different status for comment approvals.
        $is_spam = "spam";

        // The comment itself
		$comment_content = $commentdata['comment_content'];
		
		// User Settings
		$permitted_languages = get_option("cg-language");

        // Language Detection Logic
        $ld = new Language;
		$detected_language = $ld->detect($comment_content)->bestResults();

        $is_legit = in_array(strval($detected_language), $permitted_languages);

        if (!$is_legit) {
            // Its not a permitted comment
            $approved = $is_spam;
        }

        // If all checks are done, approve the comment
        return $approved;
    }
}