
<div class='io-optimizer-wrapper'>

    <div class="io-progress-bar"><div class="progress"></div></div>
    <div class='io-notify-group'>
        <ul>
            <li><p class='io-label-process-bar'>
                    <?php print esc_html__("Processing ", 'tp-image-optimizer'); ?></p>
                <p class='optimized-number'>0</p> / <p class='total-number'><?php echo esc_html($total_file); ?></p>
            </li>
            
            <li><p class=""> <?php print esc_html__("Optimized", 'tp-image-optimizer'); ?></p></p>
                <p class="compressed-image" data-number-selected-size="<?php echo esc_html($total_selected_size);?>">0</p> / <p class="total-compressed-images">0</p> <?php print esc_html__("images", 'tp-image-optimizer'); ?>
            </li>
            
            <li><?php print esc_html__("Error ", 'tp-image-optimizer'); ?>  <p class='io-error'><?php echo esc_html($total_error); ?></p></li>
        </ul>
        <div class="io-show-log"><?php print esc_html__("Getting Started ...", 'tp-image-optimizer'); ?> </div>
    </div>



    <?php wp_nonce_field("tp_image_optimizer_key_img", "img_key_ajax"); ?>
    
    <?php
    wp_nonce_field('auto_data_nonce', 'set_auto_key');
    ?>
    <div class='submit-optimizer'>
        <button type="submit" name="optimizer_btn" id="optimizer_btn" class="button button-primary">
            <?php echo esc_html__("One Click OPTIMIZE ", 'tp-image-optimizer'); ?>
        </button>
        <input type="button" name="cancel_btn" id="cancel_optimizer" class="button cancel_optimizer" value="<?php echo esc_html__("PAUSE ", 'tp-image-optimizer'); ?>">

    </div>
    <label><input type="checkbox" name="force-re-optiomizer" id="io-reoptimized"> <?php echo esc_html__('Force Re-Optimize', 'tp-image-optimizer'); ?> </label>
    <div class='force-label'>
        <?php echo esc_html__('If selecting "Force Re-Optimize", the plugin will auto re-optimize all image of your library.', 'tp-image-optimizer'); ?></div>
</div>
