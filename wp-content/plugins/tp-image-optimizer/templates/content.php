<div id='tp-image-optimizer' class="tp-image-optimizer io-detail-page wrap" data-process='false'  data-total='<?php echo esc_html($total_image);?>'>
    <h1 class="wp-heading-inline"><?php echo esc_html($title); ?></h1>
    <?php do_action('tp_image_optimizer_panel'); ?>
    <div id="poststuff">
        <div class='content'>
                <div class='io-top-panel'>
                    <div class='panel-settings'>
                        <?php
                        //do_meta_boxes(null, 'content_optimizer', array());
                        do_meta_boxes(null, 'topbox', array());
                        ?>
                    </div>
                </div>
                
            </div>
        
        <div id="post-body" class="metabox-holder columns-2">
            
            <div id="post-body-content">
                
                <div class='panel_stastics'>
                    <?php do_meta_boxes(null, 'content_detail', array()); ?>
                </div>
            </div>
            <div id="postbox-container-1" class="io-sidebar postbox-container">
                
                <?php
                do_action('tp_image_optimizer_panel_sizes');
                do_meta_boxes(null, 'content_setting', array());
                do_meta_boxes(null, 'sidebar', array());
                ?>
            </div>
            <?php do_action('tp_image_optimizer_sticky_box');?>

        </div>
        <br class="clear">

    </div>
</div>
