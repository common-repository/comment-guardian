<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.codepa.de
 * @since      1.0.0
 *
 * @package    Comment_Guardian
 * @subpackage Comment_Guardian/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Comment_Guardian
 * @subpackage Comment_Guardian/admin
 * @author     Codepa <info@codepa.de>
 */
class Comment_Guardian_Admin
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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Comment_Guardian_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Comment_Guardian_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/comment-guardian-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Comment_Guardian_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Comment_Guardian_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
		
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/multiselect-dropdown.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/comment-guardian-admin.js', array('jquery'), $this->version, false);

    }

    public function cg_menu_page()
    {

        add_submenu_page(
            'options-general.php',
            'Comment Guardian',
            'Comment Guardian',
            'manage_options',
			'comment-guardian-options',
			array($this, 'cg_admin_page_content'),
            10
        );

	}

	function cg_admin_page_content() {
		require "partials/comment-guardian-admin-display.php";
	}

	public function cg_setup_sections() {
		add_settings_section( 'cg_main_section', __("Language Settings", "comment-guardian"), array(), 'comment-guardian-options' );
	}
	
	public function cg_setup_fields() {
		$fields = array(
			array(
				'label' => __("Choose Language", "comment-guardian"),
				'id' => 'cg-language',
				'type' => 'multiselect',
				'section' => 'cg_main_section',
				'options' => array(
					'ab' => 'Abkhaz',
					'af' => 'Afrikaans',
					'am' => 'Amharic',
					'ar' => 'Arabic',
					'ay' => 'Aymara',
					'bg' => 'Bulgarian',
					'lt' => 'Lithuanian',
					'lv' => 'Latvian',
					'nb' => 'Norwegian, Bokmål',
					'nl' => 'Dutch',
					'pl' => 'Polish',
					'pt-BR' => 'Portuguese (Brazil)',
					'pt-PT' => 'Portuguese (Portugal)',
					'ro' => 'Romanian',
					'ru' => 'Russian',
					'et' => 'Estonian',
					'fa' => 'Persian',
					'fi' => 'Finnish',
					'fr' => 'French',
					'tr' => 'Turkish',
					'uk' => 'Ukrainian',
					'hi' => 'Hindi',
					'hr' => 'Croatian',
					'hu' => 'Hungarian',
					'hy' => 'Armenian',
					'id' => 'Indonesian',
					'it' => 'Italian',
					'ja' => 'Japanese',
					'ka' => 'Georgian',
					'ko' => 'Korean',
					'ku' => 'Kurdish',
					'cs' => 'Czech',
					'de' => 'German',
					'da' => 'Danish',
					'el-monoton' => 'Greek',
					'en' => 'English',
					'es' => 'Spanish',
					'sk' => 'Slovak',
					'sl' => 'Slovene',
					'so' => 'Somali',
					'sq' => 'Albanian',
					'sv' => 'Swedish',
					'ta' => 'Tamil',
					'th' => 'Thai',
					'vi' => 'Vietnamese',
					'zh-Hans' => 'Chinese',
					'sw' => 'Swahili/Kiswahili',
				),
				'desc' => __("What languages do you want to allow for comments?", "comment-guardian"),
			),
		);
		foreach( $fields as $field ){
			add_settings_field( $field['id'], $field['label'], array( $this, 'cg_field_callback' ), 'comment-guardian-options', $field['section'], $field );
			register_setting( 'comment-guardian-options', $field['id'] );
		}
	}

	public function cg_field_callback( $field ) {
		$value = get_option( $field['id'] );
		$placeholder = '';
		if ( isset($field['placeholder']) ) {
			$placeholder = $field['placeholder'];
		}
		switch ( $field['type'] ) {
				case 'multiselect':
					if( ! empty ( $field['options'] ) && is_array( $field['options'] ) ) {
						$attr = '';
						$options = '';
						
						
						foreach( $field['options'] as $key => $label ) {

							$options.= sprintf('<option value="%s" %s>%s</option>',
								$key,
								!empty($value) && in_array($key, $value) ? "selected" : "",
								// selected($value, $key, false),
								$label
							);
						}
						if( $field['type'] === 'multiselect' ){
							$attr = ' multiple="multiple" ';
						}
						printf( '<select name="%1$s[]" id="%1$s" %2$s multiselect-search="true" class="cg-select">%3$s</select>',
							$field['id'],
							$attr,
							$options
						);
					}
					break;
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$placeholder,
					$value
				);
		}
		if( isset($field['desc']) ) {
			if( $desc = $field['desc'] ) {
				printf( '<p class="description">%s </p>', $desc );
			}
		}
	}

	public function admin_notice() {
	if ($notices= get_option('cg_deferred_admin_notices')) {
		foreach ($notices as $notice) {
			echo "$notice";
		}
		delete_option('cg_deferred_admin_notices');
		}
	}

	public static function add_notice() {

		$notices= get_option('cg_deferred_admin_notices', array());
		$notices[] = '<div class="notice notice-success is-dismissible"><p><strong>Comment Guardian:</strong> ' . sprintf(__('Great! Just edit your %s and you are protected against spam!', 'comment-guardian'), '<a href="/wp-admin/options-general.php?page=comment-guardian-options">'. __("Preferences", "comment-guardian") .'</a>') . '</p></div>';
		update_option('cg_deferred_admin_notices', $notices);
	
	}
	
	public function admin_init() {
		$current_version = 1;
		$version= get_option('cg_version');
		if ($version != $current_version) {
		// Do whatever upgrades needed here.
		update_option('cg_version', $current_version);
		$this->add_notice();
		}
	}

}