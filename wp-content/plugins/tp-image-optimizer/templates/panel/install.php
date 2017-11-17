<div class='io-install'>
    <div class="io-progress-bar"><div class="progress"></div></div>
    <div class='io-load-image-bar'>
        <div class='accept_panel'>
            <h2 class='title-io'><?php echo esc_html("Welcome to the world of TP Image Optimizer! ", "tp-image-optimizer"); ?></h2>
            <div class='desc-install'>
                <?php echo wp_kses_post("Thank you for choosing <b>TP Image Optimizer</b> to optimize your library. this quick setup wizard will help you to autocomplete all basic settings.", "tp-image-optimizer"); ?>
            </div>

            <div class='feature'>
                <label><?php echo esc_html("During installation, the plugin will:", "tp-image-optimizer"); ?></label>
                <ul>
                    <li><?php echo esc_html("- Get a token key free.", "tp-image-optimizer"); ?></li>
                    <li><?php echo esc_html("- Basic image optimizing options are auto-selected, you can change them after the installation is completed", "tp-image-optimizer"); ?></li>
                    <li><?php echo esc_html("- Add all image data in the Media to the pending list for optimizing", "tp-image-optimizer"); ?></li>
                </ul>
            </div>
            <br/>
            <input type="submit" name="accept-install" id="accept-install" class="button button-primary" value="<?php echo esc_html("Get Started", "tp-image-optimizer"); ?>">
            <div class='install-required'>
                <ul><li><?php echo esc_html("Oops!!! Internet Connection is required to get the token key of our service.", "tp-image-optimizer"); ?></li></ul>
            </div>
        </div>
    </div>
</div>