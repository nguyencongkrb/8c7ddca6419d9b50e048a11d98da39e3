<?php

if (!defined('TP_IMAGE_OPTIMIZER_BASE')) {
    exit; // Exit if accessed directly
}

/**
 * STASTICS
 * Provide method to get stastics of Optimize service.
 * 
 * @class TP_Image_Optimizer_Stastics
 * @package TP_Image_Optimizer/Classes
 * @category Class
 * @version 1.0
 */


if (!class_exists('TP_Image_Optimizer_Stastics')) {

    class TP_Image_Optimizer_Stastics {
        
        /**
         * @since 1.0.0
         */

        public function get_total_attachment_img() {
            $image_list = new TP_Image_Optimizer_Image();
            return $image_list->count_attachment_file();
        }
        /**
         * 
         * @return int Total image
         * @since 1.0.0
         */
        public function get_total_image() {
            $tb = new TP_Image_Optimizer_Table();
            return $tb->get_total_image();
        }

        /**
         * Count number attachment error on compressed
         * 
         * @return int Number of error optimized attachment
         * @since 1.0.0
         */
        public function get_number_image_error() {
            $images    = new TP_Image_Optimizer_Table();
            $array_img = $images->get_list_error_image();

            return count($array_img);
        }

        /**
         * Total number uncompress image
         * 
         * @return double
         * @since 1.0.0
         */
        public function get_total_uncompress_img() {
            $db_table            = new TP_Image_Optimizer_Table();
            $total_img           = $this->get_total_image();
            $total_err           = $this->get_number_image_error();
            $total_img_optimized = $db_table->get_total_optimized_image();
            return ($total_img - $total_img_optimized);
        }

        /* Get number total compressed image assigned on Image Optimized
         *
         * @return Total compressed image
         * @since 1.0.0
         */

        public function get_total_compressed_img() {
            $db_table            = new TP_Image_Optimizer_Table();
            $total_img_optimized = $db_table->get_total_optimized_image();
            return $total_img_optimized;
        }

        /**
         * Get total origin size of all attachment assigned on Image Optimized
         * 
         * @return double total size
         * @since 1.0.0
         */
        public function get_total_origin_size() {
            $total_origin_size = get_option("tp_image_optimizer_total_origin_size");
            return tp_image_optimizer_dislay_size($total_origin_size);
        }

        /**
         * Total current size of all attachment assigned on TP Image Optimizer
         * 
         * @return double
         * @since 1.0.0
         */
        public function get_total_current_size() {
            $total_current_size = get_option("tp_image_optimizer_total_current_size");
            return tp_image_optimizer_dislay_size($total_current_size);
        }

        /**
         * Get total size save
         * 
         * @return double size reduce after optimized
         * @since 1.0.0
         */
        public function get_total_save_size() {
            $total_origin_size  = get_option("tp_image_optimizer_total_origin_size");
            $total_current_size = get_option("tp_image_optimizer_total_current_size");
            return tp_image_optimizer_dislay_size($total_origin_size - $total_current_size);
        }

        /**
         * Get total percent reduced
         * 
         * @return int Percent
         * @since 1.0.0
         */
        public function get_total_percent_reduced() {
            $percent            = 0;
            $total_origin_size  = $this->get_total_origin_size();
            $total_current_size = $this->get_total_current_size();
            if ($total_origin_size != 0) {
                $percent = ($total_origin_size - $total_current_size) * 100 / $total_origin_size;
            }
            return $percent;
        }

        /**
         * Get Stastics of image by sizes
         * 
         * @category Ajax
         * @since 1.0.0
         */
        public function get_stastics_for_detail() {
            if (isset($_GET['id'])) {
                $origin_size = 0;
                $current_size = 0;

                // List size have been choosen to optimize
                $sizes = get_option('tp_image_optimizer_sizes');
                $sizes = explode(",", $sizes);

                $table = new TP_Image_Optimizer_Table();
                $id    = $_GET['id'];

                $total_origin_size  = 0;
                $total_current_size = 0;

                echo "<table>
                <tr>
                    <th>" . esc_html__("Size name", "tp-image-optimizer") . "</th>
                    <th>" . esc_html__("Original Size ", 'tp-image-optimizer') . "</th> 
                    <th>" . esc_html__("Current size", 'tp-image-optimizer') . "</th>
                    <th>" . esc_html__("Saving", 'tp-image-optimizer') . "</th>
                  </tr>";
                foreach ($sizes as $size) {

                    $results      = $table->get_all_stastic_image($id, $size);
                    $current_size = filesize(tp_image_optimizer_scaled_image_path($id, $size));
                    if(isset($results[0]['origin_size'])){
                        $origin_size  = $results[0]['origin_size'];
                    } else{
                        // Not record on database
                        $origin_size  = filesize(tp_image_optimizer_scaled_image_path($id, $size));
                        // if file is created, record it to db
                        if($origin_size > 0){
                            $table ->assign_attachment_to_io($id, $size);
                        }
                    }

                    $total_origin_size  = $total_origin_size + $origin_size;
                    $total_current_size = $total_current_size + $current_size;

                    $reduce = 0;
                    if ($origin_size != 0) {
                        $reduce = (($origin_size - $current_size ) / $origin_size) * 100;
                        if($reduce != 0){
                            $reduce =  number_format($reduce, 2);
                        }
                    }

                    echo "<tr><td>$size</td>";
                    echo "<td>" . tp_image_optimizer_dislay_size($origin_size) . "</td>";
                    echo "<td>" . tp_image_optimizer_dislay_size($current_size) . "</td>";
                    echo "<td>" . $reduce . "%</td></tr>";
                }
                $save_size = $total_origin_size - $total_current_size;

                echo '<tr class="io-total-size-save"><td>';
                echo esc_html__('Total saving : ', 'tp-image-optimizer') . '</td><td></td><td>';
                echo '<span >' . tp_image_optimizer_dislay_size($save_size) . '<span>';
                echo '</td></tr>';
                echo '</table>';
            } else {
                echo esc_html__('Please try again... ', 'tp-image-optimizer');
            }
            wp_die();
        }
        
        /**
         * 
         * @return int Number total selected size
         * @since 1.0.0
         */
        public function get_total_selected_size(){
            $list_current_size = get_option('tp_image_optimizer_sizes');
            $list_size = explode(',', $list_current_size);
            return count($list_size);
        }

    }

}