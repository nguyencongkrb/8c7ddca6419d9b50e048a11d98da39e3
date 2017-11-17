<?php

if (!defined('TP_IMAGE_OPTIMIZER_BASE')) {
    exit; // Exit if accessed directly
}

/**
 * SERVICE COMPRESS
 * Provide featured to request optimize service.
 * 
 * @class TP_Image_Optimizer_Service
 * @package TP_Image_Optimizer/Classes
 * @category Class
 * @version 1.0
 */
if (!class_exists('TP_Image_Optimizer_Service')) {

    class TP_Image_Optimizer_Service {

        /**
         * 
         * 
         */
        private $option;

        /**
         * Address of service 
         * 
         * @type String 
         */
        private $service;

        /**
         * Info of website, used to validate action
         * 
         * @var Object 
         */
        private $authentication;

        /**
         * Token
         * 
         */
        private $token;
        private $address;
        private $compress_level;

        public function __construct() {
            $check_curl    = function_exists('curl_version');
            $this->service = "http://api.themespond.com/io/";
            if ($check_curl) {
                $this->option   = array(
                    CURLOPT_URL            => $this->service . 'compress',
                    //CURLOPT_SAFE_UPLOAD    => false,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                );
                $this->token    = get_option('tp_image_optimizer_token');
                $authentication = array(
                    'token' => $this->token
                );

                $authentication       = json_encode($authentication);
                $this->authentication = base64_encode($authentication);
                $this->address        = get_home_url();

                $this->compress_level = get_option('tp_image_optimizer_compress_level');
            }
        }

        /**
         * Get token from server
         * Update token key to WP Option
         * 
         * @category Ajax
         * @since 1.0.0
         */
        public function get_token() {
            $token = get_option('tp_image_optimizer_token');
            // Check key exist
            if (($token != false) && (strlen($token) == 35 )) {
                $data['log'] = esc_html__('Detect token of service has already created before !', 'tp-image-optimizer');
                wp_send_json_success($data);
            }
            try {
                $data = array(
                    'action' => 'request_token',
                );

                $ch = curl_init();
                if (FALSE === $ch)
                    throw new Exception('failed to initialize');

                $option = array(
                    CURLOPT_URL            => $this->service . "request",
                    CURLOPT_POST           => count($data),
                    CURLOPT_POSTFIELDS     => $data,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                );

                curl_setopt_array($ch, $option);

                $response = curl_exec($ch);

                if (FALSE === $response)
                    throw new Exception(curl_error($ch), curl_errno($ch));
                $response = json_decode($response);
                if (isset($response->key)) {
                    update_option('tp_image_optimizer_token', $response->key);
                }
                curl_close($ch);
                wp_send_json_success($response);
            } catch (Exception $e) {
                trigger_error(sprintf(
                                'Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
            }
            wp_send_json_error();
        }

        /**
         * Get Stastics from ThemesPond service
         * 
         * @category Ajax
         * @since 1.0.0
         */
        public function get_stastics() {
            // Get cache 
            $data = get_transient('tp_image_optimizer_stastics_service');
            if (FALSE !== $data) {
                wp_send_json_success($data);
            }

            // If no cache or expired
            try {
                $data = array(
                    'action'         => 'request_stastics',
                    'authentication' => $this->authentication
                );

                $ch = curl_init();
                if (FALSE === $ch)
                    throw new Exception('failed to initialize');

                $option = array(
                    CURLOPT_URL            => $this->service . 'stastics',
                    CURLOPT_POST           => count($data),
                    CURLOPT_POSTFIELDS     => $data,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                );

                curl_setopt_array($ch, $option);
                $response = curl_exec($ch);

                if (FALSE === $response)
                    throw new Exception(curl_error($ch), curl_errno($ch));
                curl_close($ch);
                $response = json_decode($response, true);


                // Send response
                if (isset($response['success'])) {
                    wp_send_json_success($response);
                    // Set value to transient
                    set_transient('tp_image_optimizer_stastics_service', $response, 24 * 60 * 60);
                } else {
                    wp_send_json_error();
                }

                wp_die();
            } catch (Exception $e) {
                trigger_error(sprintf(
                                'Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
            }
            wp_send_json_error();
        }

        /**
         * CURL PostImage service
         * Send image to optimize by PostImage Service via Curl
         * 
         * @category Ajax
         * @param double $id - ID of attachment image
         * @param string $size - Size of attachment will be optimized
         * @param boolean $for_validate Use when validate API key
         * 
         * @return string  - Data for display notification
         * @throws Exception
         * @since 1.0.0
         */
        public function request_service($attachment_id = '', $size_name = '') {
            $db_table = new TP_Image_Optimizer_Table();
            $data     = array(
                'authentication' => $this->authentication,
                'action'         => 'compress',
                'compress_level' => $this->compress_level
            );

            // Data return to debug
            $data_return = array(
                'id'      => $attachment_id,
                'success' => false,
                'log'     => '',
                'size'    => $size_name
            );

            $file_size_old = 0;

            try {
                // Init CURL
                $ch = curl_init();
                if (FALSE === $ch)
                    throw new Exception('failed to initialize');

                if (wp_attachment_is_image($attachment_id)) {

                    $image_file    = tp_image_optimizer_scaled_image_path($attachment_id, $size_name);
                    $file_size_old = filesize($image_file);

                    $check_image_on_db = $db_table->check_image_size_on_db($attachment_id, $size_name);
                    if (!$check_image_on_db && $file_size_old > 0) {
                        $db_table->assign_attachment_to_io($attachment_id, $size_name);
                    }


                    if (file_exists($image_file)) {
                        // Image is too small
                        if (filesize($image_file) < 10240) {
                            $data_return['success'] = true;
                            $data_return['log']     = esc_html__("Image is too small", "tp-image-optimizer");
                            return $data_return;
                        }
                        
                        if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
                            // Add CURL File
                            $data['image'] = new CURLFile($image_file);
                        } else {
                            $data['image'] = '@' . $image_file;
                        }
                    }
                } else {
                    $data_return['success']   = false;
                    $data_return['error_log'] = esc_html__("404 not found - File is removed, you need to refresh image data of TP Image Optimizer by clicking UPDATE IMAGE", "tp-image-optimizer");
                    return $data_return;

                    // Attachment image has been deleted, need remove this ID from IO Database Table
                    $db_table->remove_deleted_attachment_image($attachment_id);
                }


                // After get attachment and select compress option, set it to postfield of CURL
                $this->option[CURLOPT_POST]       = count($data);
                $this->option[CURLOPT_POSTFIELDS] = $data;

                // Set option to Curl
                curl_setopt_array($ch, $this->option);

                // Excute CURL
                $response_from_service = curl_exec($ch);

                if (FALSE === $response_from_service)
                    throw new Exception(curl_error($ch), curl_errno($ch));

                $origin_path = tp_image_optimizer_scaled_image_path($attachment_id, $size_name);

                // Close CURL connection
                curl_close($ch);

                /*                 * ****************************************   
                 * VALIDATE DATA RESPONSE IS IMAGE or NOT *
                 * *************************************** */
                $check = isJSON($response_from_service);
                // If $check == true, it mean server return an error

                if (!$check) {
                    /**
                     *  Replace original attachment image by optimized file
                     *  Override original image by response image from PostImage Service
                     */
                    $img_origin_load   = @fopen($origin_path, "w");
                    $result_write_file = fwrite($img_origin_load, $response_from_service);

                    $db_table->update_status_for_attachment($attachment_id, $size_name, "optimized");

                    // Result
                    $data_return['old_size']   = $file_size_old;
                    $data_return['new_size']   = $result_write_file;
                    $data_return['success']    = true;
                    $data_return['log']        = esc_html__("Succcess optimizer #", 'tp-image-optimizer') . $attachment_id . ' - ' . $size_name;
                    $data_return['compressed'] = true;
                    // Update current size after optimized
                    $db_table->update_current_size_for_attachment($attachment_id, $size_name, $result_write_file);

                    // Total current
                    $total_current_size = get_option('tp_image_optimizer_total_current_size');
                    // Caculator new size
                    $save_size          = $file_size_old - $result_write_file;
                    update_option('tp_image_optimizer_total_current_size', $total_current_size - $save_size);

                    return $data_return;
                } else {
                    /**
                     * Catch error
                     */
                    $error_data = json_decode($response_from_service);

                    if ($error_data->status == 400 || $error_data->status = 400) {
                        $data_return['log']     = esc_html__("Succcess optimizer #", 'tp-image-optimizer') . $attachment_id . ' - ' . $size_name;
                        $data_return['success'] = true;
                        return ($data_return);
                    }

                    // Logging
                    $data_return['error_log'] = $error_data->error;
                    $data_return['success']   = false;
                    return ($data_return);
                }
            } catch (Exception $e) {
                $data_return['success']   = false;
                $data_return['error_log'] = esc_html__("Lost connnection to service !", 'tp-image-optimizer');
                return $data_return;
            }
            $data_return['success']   = false;
            $data_return['error_log'] = esc_html__("Unexpected error!", 'tp-image-optimizer');
            return $data_return;
        }

        /**
         * Process optimize attachment by service of Post Image
         * 
         * @category Ajax
         * @return void
         * @since 1.0.0
         */
        public function process_optimize() {
            delete_transient('tp_image_optimizer_stastics_service');

            $db_table = new TP_Image_Optimizer_Table();

            $attachment_id = '';
            if (!isset($_POST['id'])) {
                /**
                 * MULTI OPTIMIZER
                 */
                // Remove cache
                $number = esc_html($_POST['start']);

                $error_count = intval(esc_html($_POST['error_count']));

                $force         = esc_html($_POST['force']);
                $attachment_id = $db_table->get_pre_optimize_image($number, $force, $error_count);

                // Get list image size
                $list_size = $_POST['list_size'];
                $result    = array(
                    'id'      => $attachment_id,
                    'success' => false,
                    'number'  => $number,
                    'reload'  => false,
                    'count'   => $error_count
                );

                if ($attachment_id == '' || $attachment_id == null) {
                    $result['reload'] = true;
                    wp_send_json_error($result);
                }
            } else {
                /**
                 * SINGLE OPTIMIZE
                 */
                $list_size = get_option('tp_image_optimizer_sizes');
                $list_size = preg_split("/[\s,]+/", $list_size);

                $attachment_id = esc_html($_POST['id']);
                $result        = array(
                    'id'      => $attachment_id,
                    'sizes'   => $list_size,
                    'success' => false,
                );
            }
            foreach ($list_size as $size_name) {
                $rs = $this->request_service($attachment_id, $size_name);
                if (isset($rs['success']) && ($rs['success'] != 1)) {
                    $result['success'] = false;
                    $result['log']     = $rs['error_log'];
                    $db_table->update_status_for_attachment($attachment_id, "full", "error");
                } else {
                    $result['success'] = true;
                    $result['url']     = wp_get_attachment_thumb_url($attachment_id);
                    // Set stastus for flag to exclude this attachment id from pre-optimize list
                    $db_table->update_status_for_attachment($attachment_id, "full", "optimized");
                    if (($rs['size'] == 'full') && (isset($rs['compressed'])) && ( $rs['compressed'] == true)) {
                        $result['full_detail'] = $rs;
                    }
                }
            }

            if (isset($result['success']) && ($result['success'] == false)) {
                $err                 = intval(get_option('tp_image_optimizer_error'));
                update_option('tp_image_optimizer_error', $err + 1);
                $error_num           = $err + 1;
                $result['error_num'] = $error_num;
                wp_send_json_error($result);
            }
            // If success
            wp_send_json_success($result);
        }

    }

}