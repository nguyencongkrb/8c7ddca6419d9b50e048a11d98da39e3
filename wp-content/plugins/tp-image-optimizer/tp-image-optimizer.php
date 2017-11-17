<?php
/*
 * Plugin Name: TP Image Optimizer    
 * Description: A WordPress plugin that allows you to reduce image file sizes and optimize all images in the media library.    
 * Version: 1.0.4   
 * Author: ThemesPond    
 * Author URI: https://themespond.com/    
 * License: GNU General Public License v3 or later    
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html    
 *
 * Requires at least: 4.0    
 * Tested up to: 4.7    
 * Text Domain: tp-image-optimizer    
 * Domain Path: /languages/    
 *
 * @package TP_Image_Optimizer    
 */

class TP_Image_Optimizer {

	private $title;

	public function __construct() {
		$this->title = esc_html__( 'TP Image Optimizer', 'tp-image-optimizer' );
		$this->defined();
		$this->includes();
		$this->hook();
	}

	private function defined() {
		define( 'TP_IMAGE_OPTIMIZER_DIR', plugin_dir_path( __FILE__ ) );
		define( 'TP_IMAGE_OPTIMIZER_URL', plugin_dir_url( __FILE__ ) );
		define( 'TP_IMAGE_OPTIMIZER_BASE', 'tp-image-optimizer' );
		define( 'TP_IMAGE_OPTIMIZER_VER', '1.0.4' );
	}

	/**
	 * Register plugin page
	 */
	public function register_page() {
		add_menu_page( $this->title, esc_html__( 'Image Optimizer', 'tp-image-optimizer' ), 'manage_options', TP_IMAGE_OPTIMIZER_BASE, array( $this, 'plugin_load' ), 'dashicons-images-alt2', 12 );
	}

	/**
	 * Load content
	 * @since 1.0.0
	 * 
	 * @return void
	 */
	public function plugin_load() {

		$image = new TP_Image_Optimizer_Image();

		$data = array(
			'title' => $this->title,
			'total_image' => $image->count_attachment_file()
		);

		$install_check = get_option( 'tp_image_optimizer_installed' );

		if ( $install_check === 'false' ) {
			$data['title'] = __( 'Install TP Image Optimizer', 'tp-image-optimizer' );
			tp_image_optimizer_template( 'install', $data );
		} else {
			if ( function_exists( 'curl_version' ) ) {
				tp_image_optimizer_template( 'content', $data );
			} else {
				tp_image_optimizer_template( 'nocurl', $data );
			}
		}
	}

	/**
	 * Include class
	 * 
	 */
	private function includes() {
		include TP_IMAGE_OPTIMIZER_DIR . '/includes/helpers-function.php';
		tp_image_optimizer_class( 'lang' );
		tp_image_optimizer_class( 'metabox' );
		tp_image_optimizer_class( 'table' );
		tp_image_optimizer_class( 'image' );
		tp_image_optimizer_class( 'service' );
		tp_image_optimizer_class( 'stastics' );
	}

	/**
	 * Enqueue admin script
	 * @since 1.0
	 * @param string $hook
	 * @return void
	 */
	public function admin_scripts( $hook ) {
		// Drag log box
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-draggable' );
		// Popup - Tooltip 
		wp_enqueue_script( 'jbox-js', TP_IMAGE_OPTIMIZER_URL . 'assets/lib/jbox/jBox.min.js', array( 'jquery' ), true );
		// Chart
		wp_enqueue_script( 'chart-js', TP_IMAGE_OPTIMIZER_URL . 'assets/lib/easypiechart/jquery.easypiechart.min.js', array(), '2.1.7', true );
		// Process ajax 
		wp_enqueue_script( 'io-admin-js', TP_IMAGE_OPTIMIZER_URL . 'assets/js/ajax.js', array(), TP_IMAGE_OPTIMIZER_VER, true );
		// Javascript of plugin
		wp_enqueue_script( 'io-plugin-js', TP_IMAGE_OPTIMIZER_URL . 'assets/js/io.js', array(), TP_IMAGE_OPTIMIZER_VER, true );
		// Style
		wp_enqueue_style( 'jbox-css', TP_IMAGE_OPTIMIZER_URL . 'assets/lib/jbox/jBox.css' );
		wp_enqueue_style( 'io-admin-css', TP_IMAGE_OPTIMIZER_URL . 'assets/css/io.css', null, TP_IMAGE_OPTIMIZER_VER );

		wp_localize_script( 'io-admin-js', 'tp_image_optimizer_admin_js', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

		// Add language
		$lang = new TP_Image_Optimizer_Lang();
		wp_localize_script( 'io-admin-js', 'tp_image_optimizer_lang', array(
			'main' => $lang->get_main_text(),
			'success' => $lang->get_success_notice(),
			'error' => $lang->get_error_notice(),
			'load' => $lang->get_loading_notice(),
			'request' => $lang->get_request_notice(),
			'install' => $lang->get_install_notice(),
			'size' => $lang->size(),
			'faq' => $lang->faq(),
		) );
	}

	/**
	 * Load Local files.
	 * @since 1.0
	 * @return void
	 */
	public function load_plugin_textdomain() {

		// Set filter for plugin's languages directory
		$dir = TP_IMAGE_OPTIMIZER_DIR . 'languages/';
		$dir = apply_filters( 'tp_image_optimizer_languages_directory', $dir );

		// Traditional WordPress plugin locale filter
		$locale = apply_filters( 'plugin_locale', get_locale(), 'tp-image-optimizer' );
		$mofile = sprintf( '%1$s-%2$s.mo', 'tp-image-optimizer', $locale );

		// Setup paths to current locale file
		$mofile_local = $dir . $mofile;

		$mofile_global = WP_LANG_DIR . '/tp-image-optimizer/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/tp-image-optimizer folder
			load_textdomain( 'tp-image-optimizer', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/tp-image-optimizer/languages/ folder
			load_textdomain( 'tp-image-optimizer', $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( 'tp-image-optimizer', false, $dir );
		}
	}

	/**
	 * Hook
	 */
	private function hook() {

		register_activation_hook( __FILE__, array( $this, 'install' ) );
		register_deactivation_hook( __FILE__, array( $this, 'uninstall' ) );

		$service = new TP_Image_Optimizer_Service();
		$db_table = new TP_Image_Optimizer_Table();
		$stastics = new TP_Image_Optimizer_Stastics();
		$lib = new TP_Image_Optimizer_Image();

		add_action( 'admin_menu', array( $this, 'register_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 10 );

		add_action( 'wp_ajax_recheck_library', array( $lib, 'assign_all_attachment_image_to_io' ), 10 );

		// Action optimizer image
		add_action( 'wp_ajax_get_img_optimizer', array( $db_table, 'count_list_optimize_image' ), 10 );
		add_action( 'wp_ajax_process_optimize_image', array( $service, 'process_optimize' ), 10 );

		// Action update list sizes will be optimized 
		add_action( 'wp_ajax_update_sizes', array( $lib, 'update_sizes' ), 10 );

		// Get detail stastics for Attachment #ID
		add_action( 'wp_ajax_get_stastics_detail', array( $stastics, 'get_stastics_for_detail' ), 10 );

		// Get token key AJAX
		add_action( 'wp_ajax_get_token', array( $service, 'get_token' ), 10 );

		// Setting
		add_action( 'wp_ajax_update_setting', array( $db_table, 'update_setting' ), 10 );

		// Stastics from service
		add_action( 'wp_ajax_get_stastics_from_service', array( $service, 'get_stastics' ), 10 );


		// Set status plugin to installed
		add_action( 'wp_ajax_set_status_to_installed', array( $db_table, 'set_to_installed' ), 10 );

		add_action( 'wp_ajax_uninstall', array( $db_table, 'uninstall' ), 10 );

		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'add_attachment', array( 'TP_Image_Optimizer_Image', 'remove_attachment_count' ) );
		add_action( 'delete_attachment', array( 'TP_Image_Optimizer_Image', 'remove_attachment_count' ) );
	}

	/**
	 * Uninstall plugin
	 * 
	 * @global type $wpdb
	 */
	public function uninstall() {
		global $wpdb;
	}

	/**
	 * Install plugin
	 * 
	 */
	function install() {

		$table = new TP_Image_Optimizer_Table();
		$table->create();

		// Install option
		update_option( "tp_image_optimizer_installed", 'false', '', 'yes' );

		// Error option
		$key = get_option( 'tp_image_optimizer_error' );
		if ( empty( $key ) || $key == '' ) {
			add_option( 'tp_image_optimizer_error', 0, '', 'yes' );
		}
		// Size stastics
		$key = get_option( 'tp_image_optimizer_total_origin_size' );
		if ( empty( $key ) || $key == '' ) {
			add_option( 'tp_image_optimizer_total_origin_size', 0, '', 'yes' );
		}
		$key = get_option( 'tp_image_optimizer_total_current_size' );
		if ( empty( $key ) || $key == '' ) {
			add_option( 'tp_image_optimizer_total_current_size', 0, '', 'yes' );
		}
		// Select optimize all size
		$all_size = get_intermediate_image_sizes();
		array_push( $all_size, 'full' );
		$all_size = implode( ',', $all_size );

		update_option( 'tp_image_optimizer_sizes', $all_size, '', 'yes' );

		// Compress option
		update_option( 'tp_image_optimizer_compress_level', 1, '', 'yes' );
	}

}

new TP_Image_Optimizer();



