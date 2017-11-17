<?php

if (!defined('TP_IMAGE_OPTIMIZER_BASE')) {
    exit; // Exit if accessed directly
}

/**
 * LANGUAGE LOCALIZATION
 * Provide localization for Javascript variable
 * 
 * @class TP_Image_Optimizer_Lang
 * @package TP_Image_Optimizer/Classes
 * @category Class
 * @version 1.0
 * 
 */
if (!class_exists('TP_Image_Optimizer_Lang')) {

    class TP_Image_Optimizer_Lang {
		
        /**
         * 
         * @return Array Main text
         * @since 1.0.0
         */
        public function get_main_text() {
            $notify = array(
                'detail_of'           => esc_html__('Detail of #', 'tp-image-optimizer'),
                'get_list_attachment' => esc_html__('Examining existing attachments. This may take a few moments...', 'tp-image-optimizer'),
                'pause'               => esc_html__('Paused optimize progress. Don\'t worry, when you click One-click Optimize, we will continue to compress your image.', 'tp-image-optimizer'),
                'saving'              => esc_html__('Saving', 'tp-image-optimizer'),
            );
            return $notify;
        }
		
		
        /**
         * 
         * @return Array Success text
         * @since 1.0.0
         */
        public function get_success_notice() {
            $notify = array(
                'success'   => esc_html__('Processed', 'tp-image-optimizer'),
                'optimized' => esc_html__('Success optimized  attachment ID # ', 'tp-image-optimizer'),
                'done'      => __('<strong>Congratulations!</strong> You have already optimized all image of your library ', 'tp-image-optimizer'),
                'complete'  => esc_html__('Have already processed all image.', 'tp-image-optimizer'),
            );
            return $notify;
        }
        
        /**
         * 
         * @return Array request text
         * @since 1.0.0
         */
        public function get_request_notice() {
            $notify = array(
                'valid'       => esc_html__('API Key is valid', 'tp-image-optimizer'),
                'invalid'     => esc_html__('API Key is invalid', 'tp-image-optimizer'),
                'request_api' => esc_html__('You need enter a valid API ', 'tp-image-optimizer'),
            );
            return $notify;
        }
        /**
         * 
         * @return Array loading text
         * @since 1.0.0
         */
        public function get_loading_notice() {
            $notify = array(
                'update'          => esc_html__('Update', 'tp-image-optimizer'),
                'processing'      => esc_html__('Processing', 'tp-image-optimizer'),
                'loading'         => esc_html__('Loading..', 'tp-image-optimizer'),
                'reload'          => esc_html__('Reloading..', 'tp-image-optimizer'),
                'loading_library' => esc_html__('Please wait, processing media list ..', 'tp-image-optimizer'),
                'compressed'      => esc_html__('Compressed', 'tp-image-optimizer'),
            );
            return $notify;
        }
        /**
         * 
         * @return Array Error text
         * @since 1.0.0
         */
        public function get_error_notice() {
            $notify = array(
                'connection' => esc_html__('Connection lost!', 'tp-image-optimizer'),
                'detect'     => __('<strong>Oops</strong>, I\'ve detected some errors on optimizing process, you can fix this by manual optimizer.', 'tp-image-optimizer'),
                'undefined'  => esc_html__('Unexpected error !', 'tp-image-optimizer'),
                'reload'     => esc_html__('Detect an unexpected error! Please reload page to try again ! '),
            );
            return $notify;
        }

        /**
         * Using on Install Panel
         * 
         * @return String Install notice
         * @since 1.0.0
         */
        public function get_install_notice() {
            $notify = array(
                'generated_key' => esc_html__('Token has been generated ! ', 'tp-image-optimizer'),
                'generating'    => esc_html__('Generating..', 'tp-image-optimizer'),
                'getting_media' => esc_html__('Getting all media from WordPress library ...', 'tp-image-optimizer'),
                'success'       => esc_html__('Plugin has been installed successfully, this page will reload to apply change...', 'tp-image-optimizer'),
                'error'         => esc_html__('Detect an unexpected error, please try again...', 'tp-image-optimizer'),
            );
            return $notify;
        }

        /**
         * 
         * @return String Size Unit
         */
        public function size() {
            $notify = array(
                'B'  => esc_html__(' Bytes', 'tp-image-optimizer'),
                'KB' => esc_html__(' KB', 'tp-image-optimizer'),
                'MB' => esc_html__(' MB', 'tp-image-optimizer'),
            );
            return $notify;
        }

        public function faq() {
            $notify = array(
                // Faq Service Stastics
                'stastics_service_title' => esc_html__('Stastics by IO service ', 'tp-image-optimizer'),
                'stastics_service'       => esc_html__('This data is collected by the server of TP Image Optimizer. It shows statistics of the whole image optimizing process on your site.', 'tp-image-optimizer'),
                // FAQ Quality
                'quality_title'          => esc_html__('Option quality', 'tp-image-optimizer'),
                'quality'                => esc_html__('This option allows you to select the optimized image quality. The higher the image quality is, the larger the compressed image size is', 'tp-image-optimizer'),
                // Size title
                'size_title' => esc_html__('Option size', 'tp-image-optimizer'),
                'size'       => esc_html__('You set the compressed image size in this item - Popular image sizes in website (thumbnail, large image, etc.) are recommended to speed up your website. Full Option is used for compressing the original image.', 'tp-image-optimizer'),
                
                // Compress Error
                'compress_error_title' => esc_html__('Unexpected error !', 'tp-image-optimizer'),
                'compress_error'       => esc_html__('Detect an unexpected error, please try again...', 'tp-image-optimizer'),
            );
            return $notify;
        }

    }

}
