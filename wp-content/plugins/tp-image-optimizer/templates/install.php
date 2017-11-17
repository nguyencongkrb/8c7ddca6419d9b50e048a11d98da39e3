<div id='tp-image-optimizer' class="tp-image-optimizer io-detail-page wrap" data-total='<?php echo esc_html($total_image);?>'>
    <h1 class="wp-heading-inline"><?php echo $title; ?></h1>
    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class='panel_install'>
                    <?php
                    do_action('tp_image_optimizer_install_panel');
                    do_meta_boxes(null, 'content_install', array());
                    ?>
                </div>
            </div>
        </div>
        <br class="clear">

    </div>
</div>
