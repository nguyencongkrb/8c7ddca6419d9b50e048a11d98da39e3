<div class='io-stastics-wrapper'>

    <div class="service-stastics">

        <label><?php echo esc_html__( 'Stastics about optimized data by IO service', 'tp-image-optimizer' ); ?> <span class='faq-stastics_service'></span></label>

        <div class="io-service-stastics">
            <span id="io-chart" class="chart" data-percent="0">
                <span class="percent"></span>
            </span>
            <div class="detail">
                <ul >
                    <li><?php echo esc_html__( 'Total optimized image', 'tp-image-optimizer' ); ?> <span class='total-image'></span><p class="spinner"></p></li>
                    <li><?php echo esc_html__( 'Total uploaded size ', 'tp-image-optimizer' ); ?> <span class='uploaded-size'></span><p class="spinner"></p></li>
                    <li><?php echo esc_html__( 'Total size after being optimized', 'tp-image-optimizer' ); ?> <span class='compressed-size'></span><p class="spinner "></p></li>
                    <hr/>
                    <li><b><?php echo esc_html__( 'Total saving size  ', 'tp-image-optimizer' ); ?></b> <span class='saving-size'></span><p class="spinner"></p></li>
                </ul>
            </div>

        </div>
        <div class="connect-err">
			<?php echo esc_html__( 'Cannot connect to service, please reload page to try again !', 'tp-image-optimizer' ); ?>
        </div>
    </div>

    <div class='local-analytics'>
        <label><?php echo esc_html__( 'Stastics of your library', 'tp-image-optimizer' ); ?> </label>
        <ul>
            <li class='io-total-img' data-total='<?php echo esc_html( $total_file ); ?>'><?php echo __( "All images ", 'tp-image-optimizer' ); ?> <span><?php echo esc_html( $total_file ); ?></span></li>
            <li class='io-total-img-size'><?php echo __( "Total image with selected size", 'tp-image-optimizer' ); ?> <span><?php echo esc_html( $count_selected_size ); ?></span></li>
            <li ><?php echo esc_html__( 'Uncompressed image ', 'tp-image-optimizer' ); ?> <span class='io-total-uncompress' data-compressed='<?php echo esc_html( $total_compressed ); ?>'><?php echo esc_html( $total_uncompress ); ?></span></li>
        </ul>
		<?php if ( $total_current_in_media != $total_file ) : ?>
			<div class='update-image'>
				<label><?php echo esc_html__( 'UPDATE IMAGE', 'tp-image-optimizer' ); ?> <div class='count-media'><span class='percent-update'>0</span>%</div> </label>
				<p><?php echo esc_html__( 'When you upload new images, click this button to update images to pending data of Image Optimizer', 'tp-image-optimizer' ); ?></p>
				<div class="update-image-btn">
					<input type="submit" name="re-check" id="update-image" class="refresh-library button button-secondary" value="<?php echo esc_html( "Update Image", "tp-image-optimizer" ); ?>">
					<div class="load-speeding-wheel"></div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>