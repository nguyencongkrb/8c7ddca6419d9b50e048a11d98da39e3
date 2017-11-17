<?php

/**
 * IO get template
 * @param String $slug Slug namme of template
 * @param array $data Data use to get variable for template
 * @since 1.0.0
 */
function tp_image_optimizer_template($slug, $data = array()) {
    if (is_array($data)) {
        extract($data);
    }
    $template = apply_filters('tp_image_optimizer_template_path', get_template_directory() . '/tp-image-optimizer') . '/' . $slug . '.php';

    if (!file_exists($template)) {
        $template = TP_IMAGE_OPTIMIZER_DIR . 'templates/' . $slug . '.php';
    }
    include $template;
}

/**
 * Call class
 * 
 * @since 1.0.0
 */
function tp_image_optimizer_class($name) {
    include TP_IMAGE_OPTIMIZER_DIR . 'includes/class-' . $name . '.php';
}

/**
 * Insert WordPress Table
 * @param string $name of table
 * @since 1.0.0
 */
function tp_image_optimizer_table($name) {
    include TP_IMAGE_OPTIMIZER_DIR . 'includes/table/class-table-' . $name . '.php';
}

/**
 * Caculator size of file
 * 
 * @param double $size
 * @return double size of file (kb)
 * @since 1.0.0
 */
function tp_image_optimizer_caculator_size($size) {
    if ($size != 0) {
        $size = number_format($size / 1024, 2);
    }
    return $size;
}

/**
 * Display size with unit
 * 
 * 
 * @param type $size
 * @return String Display size ( Byte, KB, MB )
 * @since 1.0.0
 */
function tp_image_optimizer_dislay_size($size) {
    if ($size < 1024) {
        if ($size != 0) {
            $size = number_format($size, 2);
        }
        $size = $size . esc_html(' Byte', 'tp-image-optimizer');
    } else if ($size < 1024 * 1024) {
        $size = tp_image_optimizer_caculator_size($size);
        $size = $size . esc_html(' KB', 'tp-image-optimizer');
    } else {
        $size = number_format($size / (1024 * 1024 ), 2);
        $size = $size . esc_html(' MB', 'tp-image-optimizer');
    }
    return $size;
}

/**
 * Show message
 * 
 * @param string $notify  - Content of notify
 * @param boolean $boolean - TRUE mean as success and FALSE 
 * @since 1.0.0
 */
function tp_image_optimizer_notify($notify, $bool) {
    $message = $notify;
    $class   = "";
    if (!$bool) {
        $class = 'notice notice-error';
    } else {
        $class = 'notice notice-success';
    }
    printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
}

/**
 * Get attachment location
 * 
 * @return int Attachment ID
 * @since 1.0.0
 */
function get_image_size_location($attachment_id, $size = '') {
    $upload_folder = wp_upload_dir();
    $upload_url    = $upload_folder['baseurl'];


    $image_url = wp_get_attachment_image_url($attachment_id, $size);

    if ($size != '') {
        $output = $upload_folder['basedir'];
        $output .= str_replace($upload_url, "", $image_url);
        return $output;
    }
    return get_attached_file($attachment_id);
}

/**
 * Check IsJSON
 * 
 * 
 * @return boolean 
 * @since 1.0.0
 */
function isJSON($string) {
    return is_string($string) && is_array(json_decode($string, true)) ? true : false;
}

/* * *
 * Get image path of attachment with image size
 *  
 *  @return filepath
 *  @since 1.0.0
 */

function tp_image_optimizer_scaled_image_path($attachment_id, $size = 'thumbnail') {
    $file = get_attached_file($attachment_id, true);
    if (empty($size) || $size === 'full') {
        // for the original size get_attached_file is fine
        return realpath($file);
    }
    if (!wp_attachment_is_image($attachment_id)) {
        return false; // the id is not referring to a media
    }
    $info = image_get_intermediate_size($attachment_id, $size);
    if (!is_array($info) || !isset($info['file'])) {
        return false; // probably a bad size argument
    }

    return realpath(str_replace(wp_basename($file), $info['file'], $file));
}

/**
 * Display image name on table
 * 
 * @since 1.0.1
 */
function display_image_name($image_url) {
    $content_url = content_url();
    $image_name  = str_replace($content_url, '', $image_url);

    return "<a href='$image_url' target='_blank'><b>...$image_name</b></a>";
}
