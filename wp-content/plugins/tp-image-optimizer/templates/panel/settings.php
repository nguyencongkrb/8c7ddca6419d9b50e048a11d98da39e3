<div class='io-setting-api io-setting-wrapper'>
    <p>
    <label><?php echo esc_html__("Select image quality: ", 'tp-image-optimizer'); ?><span class='faq-quality'></span></label>
    <span class="spinner"></span>
    <select id="io-compress-level" name="tp_image_optimizer_compress_level">
        <?php foreach ($option as $key => $item) {
            ?>
            <option value="<?php echo esc_html($key); ?>" <?php if ($compress == $key) : ?> selected="selected" <?php endif; ?>><?php echo esc_html($item); ?></option>
        <?php };
        ?>
    </select>
    </p>

    

    <?php echo wp_nonce_field("api_nonce_key", 'api-check-key') ?>
    <?php submit_button("Update", "button-primary update-api-btn", "update-api", "update", false, array("type='submit'")); ?>
</div>