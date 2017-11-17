<div class='io-sizes io-sizes-option-wrapper'>
    <label>
        <?php
        echo esc_html__('The following image sizes will be optimized  by TP Image Optimizer', 'tp-image-optimizer') . ' <span class="faq-size"></span><br/>';
        ?>
    </label> 

    <label><input type="checkbox" name="io-list-size[]" value="full" checked=""><b><?php echo esc_html__('Full', 'tp-image-optimizer'); ?></b></label><br/>
    <?php
    if (!empty($sizes)):foreach ($sizes as $size):
            ?>
            <label><input type="checkbox" name="io-list-size[]" value='<?php echo $size ?>' <?php
                if (in_array($size, $optimize_sizes)): echo esc_html("checked");
                endif;
                ?>><?php echo $size ?></label><br/>
                <?php
            endforeach;
        endif;
        ?>
    <p class="spinner"></p>
    <?php
    submit_button("Update", "button-primary", "io-update-size", "delete", false, array("type='submit'"));
    ?>
</div>