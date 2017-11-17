<div class="tp-image-optimizer io-detail-page wrap" data-url='<?php echo admin_url('admin-ajax.php'); ?>'>
    <h1 class="wp-heading-inline"><?php echo esc_html($title); ?></h1>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class='panel_no_curl'>
                    <div id="setting-error-tgmpa" class="notice-error settings-error notice is-dismissible"> 
                        <p>
                            <b><?php echo esc_html__('TP Image Optimize : Detect an error ! cURL has been disabled.', 'tp-image-optimizer'); ?></b>
                        </p>
                    </div>
                    <?php
                    do_action('tp_image_optimizer_required_curl');
                    do_meta_boxes(null, 'content_no_curl', array());
                    ?>
                </div>
            </div>
        </div>
        <br class="clear">

    </div>
</div>