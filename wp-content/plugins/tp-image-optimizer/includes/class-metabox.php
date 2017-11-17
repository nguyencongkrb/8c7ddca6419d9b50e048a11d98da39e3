<?php

/**
 * METABOX GENERATOR
 * Create metabox
 * 
 * @class TP_Image_Optimizer_Metabox
 * @package TP_Image_Optimizer/Classes
 * @category Class
 * @version 1.0
 * 
 */
if (!defined('TP_IMAGE_OPTIMIZER_BASE')) {
    exit; // Exit if accessed directly
}
if (!class_exists('TP_Image_Optimizer_Metabox')) {

    class TP_Image_Optimizer_Metabox {

        public $image_work;
        private $size_list;

        public function __construct() {
            add_action('tp_image_optimizer_panel', array($this, 'add_metabox_topbox'));
            add_action('tp_image_optimizer_panel', array($this, 'add_metabox_detail'));
            add_action('tp_image_optimizer_panel', array($this, 'add_metabox_setting'));
            add_action('tp_image_optimizer_panel_sizes', array($this, 'add_metabox_sizes'));
            add_action('tp_image_optimizer_sticky_box', array($this, 'sticky_box_show'));
            add_action('tp_image_optimizer_install_panel', array($this, 'add_metabox_install'));
            // Required CURL Enable
            add_action('tp_image_optimizer_required_curl', array($this, 'add_metabox_nocurl'));
        }

        public function metabox_do_install() {
            tp_image_optimizer_template('/panel/install', array(''));
        }

        public function metabox_detail() {
            $service = new TP_Image_Optimizer_Service();
            $tb      = new TP_Image_Optimizer_Table();

            $flag = true;

            $list_img = array();
            $image    = new TP_Image_Optimizer_Image();

            $table = $image->display_table_image();

            if ($table == "nodata") {
                $flag = false;
            }
            tp_image_optimizer_template('panel/detail', array('list_img' => $list_img, 'table' => $table, 'flag' => $flag));
        }

        public function metabox_top_box() {
            $optimize_sizes  = get_option('tp_image_optimizer_sizes');
            $this->size_list = explode(",", $optimize_sizes);
            $image    = new TP_Image_Optimizer_Image();

            echo "<div class='top-bar'>";
            // Stastics
            $stastics              = new TP_Image_Optimizer_Stastics();
            $percent_reduced       = number_format($stastics->get_total_percent_reduced(), 2);
            $total_image_with_size = count($this->size_list) * $stastics->get_total_image();

            $data = array(
                'total_current_in_media' => $image->count_attachment_file(),
                'total_file'          => $stastics->get_total_image(),
                'total_uncompress'    => $stastics->get_total_uncompress_img(),
                'total_compressed'    => $stastics->get_total_compressed_img(),
                'percent_reduced'     => $percent_reduced,
                'count_selected_size' => $total_image_with_size,
            );
            tp_image_optimizer_template('panel/stastics', $data);

            // Action
            $stastics = new TP_Image_Optimizer_Stastics();
            $data     = array(
                'total_file'             => $stastics->get_total_image(),
                'total_error'            => $stastics->get_number_image_error(),
                'total_selected_size'    => $stastics->get_total_selected_size()
            );
            tp_image_optimizer_template('panel/optimizer', $data);
            echo '</div>';
        }

        public function metabox_get_size($arr_list_size) {
            $list_img_size = get_intermediate_image_sizes();


            tp_image_optimizer_template('panel/sizes', array('sizes' => $list_img_size, 'optimize_sizes' => $this->size_list));
        }

        public function metabox_required_curl() {
            echo '<div class="required-curl">';
            echo esc_html__('OOps, TP Image Optimizer requires cURL to request Optimizer Service. Please enable cURL and reload this page to enjoy the perfect performance of Image Optimizer !', 'tp-image-optimizer');
            echo '</div>';
        }

        /**
         * Setting - API
         */
        public function metabox_setting() {

            $option_select   = array(
                1 => "Standard",
                2 => "Medium",
                3 => "High",
                4 => "Very high",
            );
            $option_compress = get_option('tp_image_optimizer_compress_level');
            $data            = array(
                'option'   => $option_select,
                'compress' => $option_compress
            );
            tp_image_optimizer_template('/panel/settings', $data);
        }

        /**
         * Sticky box - Help box to fix error
         * 
         */
        public function sticky_box_show() {
            $db = new TP_Image_Optimizer_Table();

            $list_error = $db->get_list_error_image();
            $data       = array(
                    //'list_error' => $list_error,
            );
            //if (count($list_error) > 0) {
            tp_image_optimizer_template('sticky-box', $data);
            //}
        }

        // ADD Metabox
        public function add_metabox_setting() {
            add_meta_box('tp_image_optimizer_setting_panel', __('Quality setting', 'tp-image-optimizer'), array($this, 'metabox_setting'), null, 'content_setting');
        }

        public function add_metabox_detail() {

            add_meta_box('tp_image_optimizer_image_detail', __('Detail', 'tp-image-optimizer'), array($this, 'metabox_detail'), null, 'content_detail');
        }

        public function add_metabox_topbox() {
            add_meta_box('tp_image_optimizer_image_stastics', __('Stastics', 'tp-image-optimizer'), array($this, 'metabox_top_box'), null, 'topbox');
        }

        public function add_metabox_sizes() {
            add_meta_box('tp_image_optimizer_image_sizes', __('Size settings:', 'tp-image-optimizer'), array($this, 'metabox_get_size'), null, 'sidebar');
        }

        public function add_metabox_optimizer() {
            add_meta_box('tp_image_optimizer_image_optimizer', __('Optimizer', 'tp-image-optimizer'), array($this, 'metabox_get_optimizer'), null, 'content_optimizer');
        }

        public function add_metabox_install() {
            add_meta_box('tp_image_optimizer_install', __('Installation', 'tp-image-optimizer'), array($this, 'metabox_do_install'), null, 'content_install');
        }

        public function add_metabox_nocurl() {
            add_meta_box('tp_image_optimizer_no_curl', __('Enable CURL', 'tp-image-optimizer'), array($this, 'metabox_required_curl'), null, 'content_no_curl');
        }

    }

}

new TP_Image_Optimizer_Metabox();

