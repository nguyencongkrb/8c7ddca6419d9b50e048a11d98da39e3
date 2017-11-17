(function ($) {
    $(document).on('ready', function () {
        // Optimizer box
        function Optimizer() {
            this.$wrapper = $(".io-optimizer-wrapper");
            // Button
            this.$optimize_btn = this.$wrapper.find('#optimizer_btn');
            this.$update_image_btn = this.$wrapper.find("#update-image");
            this.$spinner = this.$wrapper.find('.spinner');
            // Checkbox 
            this.$force_checkbox = this.$wrapper.find('#io-reoptimized');
            // Cancel button
            this.$cancel_btn = this.$wrapper.find(".cancel_optimizer");
            // Progress 
            this.$progress_container = this.$wrapper.find('.io-progress-bar');
            this.$progress_bar = this.$wrapper.find('.io-progress-bar .progress');
            this.$label_process_bar = this.$wrapper.find('.io-label-process-bar');
            // Log progress & Notify
            this.$notify_group = this.$wrapper.find('.io-notify-group');
            this.$show_log = this.$wrapper.find(".io-show-log");
            // Progress stastics
            this.$optimized_number = this.$wrapper.find('.optimized-number');
            this.$error_detect = this.$wrapper.find(".io-error");
            this.getErrorNumber = function () {
                return parseInt(this.$error_detect.html());
            }
            this.getPositionProgress = function () {
                return parseInt(this.$optimized_number.html());
            }
            /**
             * Hide Optimizer button and show Cancel Button, active spinner
             */
            this.styleStartOptimizer = function () {
                // Hide Optimizer button and show Cancel button
                this.$cancel_btn.addClass(" is-active");
                this.$optimize_btn.css("display", 'none');
                // Hide Update image button
                this.$update_image_btn.css("display", 'none');
                // Show Spinner
                this.$spinner.addClass('is-active');
                // Show log
                this.$show_log.addClass('active');
                this.$show_log.html();
                this.$notify_group.addClass('active');
                // Show progress bar
                this.$progress_container.addClass('active');
                this.$progress_bar.css("display", "none");
                this.$progress_bar.css("display", "block");
                this.$label_process_bar.html(tp_image_optimizer_lang.load.processing);
                this.$show_log.html(tp_image_optimizer_lang.main.get_list_attachment);
                // Reset error counter
                this.$error_detect.html(0);
            }

            /**
             * Show Optimizer button and hide Cancel Button, deactive spinner
             */
            this.styleStopOptimizer = function () {
                // Hide cancel button and show Optimizer button
                this.$cancel_btn.removeClass(" is-active");
                this.$optimize_btn.css("display", 'inline-block');
                this.$update_image_btn.css("display", 'inline-block');
                // Hide spinner
                this.$spinner.removeClass('is-active');
            }
            return this;
        }

        var Optimizer = new Optimizer();
        // Stastics box
        function Stastics() {
            this.$wrapper = $(".io-stastics-wrapper");
            this.$total = this.$wrapper.find('.io-total-img');
            this.$total_uncompress = this.$wrapper.find('.io-total-uncompress');
            // Stastics data
            this.$total_number_compressed = this.$wrapper.find('.total-image');
            this.$total_size_uploaded = this.$wrapper.find('.uploaded-size');
            this.$total_size_compressed = this.$wrapper.find('.compressed-size');
            this.$total_size_saving = this.$wrapper.find('.saving-size');
            this.$service_stastics_wrapper = this.$wrapper.find('.io-service-stastics');
            this.$error_notice = this.$wrapper.find('.connect-err');
            this.getTotal = function () {
                return parseInt(this.$total.data('total'));
            }

            this.getCompressedTotal = function () {
                return parseInt(this.$total_uncompress.data('compressed'));
            }

            this.getUnCompressedTotal = function () {
                return parseInt(this.$total_uncompress.html());
            }
            return this;
        }
        var Stastics = new Stastics();
        // Size box
        function Size() {
            this.$wrapper = $(".io-sizes-option-wrapper");
            this.$spinner = this.$wrapper.find('.spinner');
            this.$submit_btn = this.$wrapper.find('.submit');
        }
        var Size = new Size();
        // Sticky box
        function Log() {
            this.$wrapper = $(".io-sticky-wrapper");
            this.$header = this.$wrapper.find(".sticky-header");
            this.$content = this.$wrapper.find(".sticky-content");
            this.$loading_box = this.$wrapper.find(".loading-sticky-box");
            this.$log = this.$wrapper.find("log");
            this.$spinner = this.$wrapper.find('.spinner');
            this.show_current_notify = function () {
                Log.$loading_box.css('display', 'block');
            }

            this.hide_loading = function () {
                Log.$loading_box.css('display', 'none');
            }

            // Collapse sticky box
            this.collapse = function () {
                this.$wrapper.addClass('collapse');
            }
            // Collapse sticky box
            this.open = function () {
                this.$wrapper.removeClass('collapse');
            }

            // Make sticky box to draggable
            this.draggable = function () {
                Log.$wrapper.draggable(
                        {
                            axis: "x",
                            containment: "window"
                        }
                );
                Log.$wrapper.css('top', '');
            }
        }
        var Log = new Log();
        var total_image = Stastics.getTotal();
        var un_optimized;
        var error_detect_number;
        var update_num;
        var number_selected_size;
        /**
         * Update stastics from server
         * 
         * @since 1.0.0
         */
        if ($('.io-stastics-wrapper').length) {
            var percent_success;
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                beforeSend: function () {
                    $('.io-stastics-wrapper .spinner').addClass('is-active');
                },
                data: {
                    action: 'get_stastics_from_service',
                },
                success: function (response) {
                    if (response.data == null) {
                        Stastics.$service_stastics_wrapper.addClass('error');
                        Stastics.$error_notice.addClass('active');
                    } else {
                        if (response.success) {
                            Stastics.$total_number_compressed.html(response.data.total_image_success);
                            Stastics.$total_size_uploaded.html(tp_image_optimizer_dislay_size(response.data.total_uploaded_success));
                            Stastics.$total_size_compressed.html(tp_image_optimizer_dislay_size(response.data.total_compressed_success));
                            Stastics.$total_size_saving.html(tp_image_optimizer_dislay_size(response.data.total_saving));
                            percent_success = parseInt(response.data.total_percent_success);
                            $('#io-chart').data('percent', percent_success);
                        }
                    }
                },
                error: function (e) {

                },
                complete: function () {
                    // Show chart
                    $('.io-stastics-wrapper .chart').addClass('active');
                    // Remove loading
                    $('.io-stastics-wrapper .spinner').removeClass('is-active');
                    // Update chart
                    $('#io-chart').data('easyPieChart').update(percent_success);
                }
            });
        }

        /**
         * UPDATE SETTING SITE
         * 
         * @since 1.0.0
         */
        $(document).on('click', '#update-api', function (e) {
            var level = $("#io-compress-level").val();
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    level: level,
                    action: 'update_setting',
                },
                beforeSend: function () {
                    $('.io-setting-wrapper .spinner').addClass('is-active');
                },
                success: function (html) {
                    $('.io-setting-wrapper .spinner').removeClass('is-active');
                },
                error: function (e) {
                }
            })
        });
        /**
         * REFRESH IMAGE LIST
         * 
         * @since 1.0.0
         */

        $(document).on('click', '.refresh-library', function (e) {
            e.preventDefault();
         
            if ($(this).attr('disabled') == undefined) {
                $(this).attr('disabled', 'disabled');
                $('.count-media, .update-image .load-speeding-wheel').css('display', 'inline-block');
                add_image_to_plugin(0);
            }
        });
        /**
         * Accept Install
         * 
         * @since 1.0.0
         */

        $(document).on('click', '#accept-install', function (e) {
            e.preventDefault();
            if (false == navigator.onLine) {
                $(".install-required").addClass('active');
                return;
            }
            var Ajax = $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'get_token',
                },
                beforeSend: function () {
                    $(".install-required").removeClass('active');
                    $(".io-progress-bar").addClass('active');
                    $(".ask-install").html(tp_image_optimizer_lang.install.generating);
                    setTimeout(function () {
                    }, 1500)
                },
                success: function (data) {
                    if (data.success) {
                        $(".io-progress-bar .progress").css('width', '0%');
                        $(".ask-install").html(tp_image_optimizer_lang.install.generated_key);
                        $('#accept-install').prop('value', tp_image_optimizer_lang.load.loading_library);
                        setTimeout(function () {
                        }, 1500)
                    } else {
                        $(".ask-install").html(tp_image_optimizer_lang.install.error);
                        Ajax.abort();
                    }
                },
                error: function (e) {
                }
            }).done(function () {
                add_image_to_plugin(0);
            })
        });
        /**
         * Add image to plugin
         * 
         * @param int count_flag Pagination
         * @since 1.0.3
         */
        function add_image_to_plugin(count_flag) {
            var total_image = parseInt($('#tp-image-optimizer').data('total'));
            var number = total_image / 800 + 1;
            var number_percent = (100 / (number)).toFixed(0);
            var percent_update;
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'recheck_library',
                    paged: count_flag
                },
                beforeSend: function () {
                    // Style
                    $('.no-media .label').html(tp_image_optimizer_lang.load.loading);
                    $('#update-image').prop('value', tp_image_optimizer_lang.load.loading);
                    $('.no-media .refresh-library').addClass('active');
                    $('.no-media .refresh-library').html('<div class ="load-speeding-wheel"></div>')
                    $(".ask-install").html(tp_image_optimizer_lang.install.getting_media);
                    setTimeout(function () {
                    }, 1000)
                },
                success: function (html) {
                },
                complete: function () {
                    // Style
                    percent_update = number_percent * count_flag;
                    if (percent_update < 100) {
                        $(".io-progress-bar .progress").css('width', percent_update + '%');
                        $(".count-media .percent-update").html(percent_update);
                    }
                    count_flag++;
                    if (count_flag < number) {
                        add_image_to_plugin(count_flag);
                    } else {
                        set_status_to_installed();
                    }
                },
                error: function (e) {
                }
            });
        }
        /**
         * Set stastus plugin to Installed
         * 
         * @since 1.0.3
         */
        function set_status_to_installed() {

            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'set_status_to_installed',
                },
                beforeSend: function () {
                },
                success: function (html) {
                },
                complete: function () {
                    // Style finish
                    $(".io-progress-bar .progress").css('width', '100%');
                    $(".count-media .percent-update").html(100);

                    $('.no-media .label').html(tp_image_optimizer_lang.load.reload);
                    $('#update-image').prop('value', tp_image_optimizer_lang.load.reload);
                    setTimeout(function () {
                        location.reload(); // Reload the page.
                    }, 2000);
                },
                error: function (e) {
                }
            });

        }

        /**
         * Ajax optimize
         * 
         * Get list attachment image will be optimized
         * Optimize all attachment media or pending attachment image
         * Call function tp_image_optimizer when load all attachment image need optimize
         * @since 1.0.0
         */

        $(document).on('click', '#optimizer_btn', function (e) {
            // Set status page to process - Usefull to prevent reload
            $(".tp-image-optimizer").data('process', 'true');
            var force = 'false';
            var list_size;
            e.preventDefault();
            if (Optimizer.$force_checkbox.is(":checked")) {
                force = 'true';
            }
            $(".io-error-notice").removeClass("active");
            var list_media;
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'get_img_optimizer',
                    force: force
                },
                beforeSend: function () {
                    // Style for Optimizer              
                    Optimizer.styleStartOptimizer();
                    // Update style and Class
                    update_style_before_optimize();
                    if (force == 'true') {
                        total_image = parseInt(Stastics.$total.data('total'));
                        Stastics.$total_uncompress.html(total_image);
                        Optimizer.$notify_group.find('.total-number').html(total_image);
                        // Uncheck Re-Optimized
                        //$("#io-reoptimized").attr('checked', false);
                    }
                },
                success: function (data) {
                    list_media = data;
                    list_size = list_media.data.list_size;
                },
                error: function (e) {

                }
            }).done(function () {
                // Optimizer with list image
                var total_image_pending = parseInt(list_media.data.count);
                // Update total process
                $('.io-total-uncompress').html(total_image_pending);
                // Number un-optimized attachment
                un_optimized = Stastics.getUnCompressedTotal();
                // Total number error detect when processing
                error_detect_number = Optimizer.getErrorNumber();
                // All image is optimized
                if (total_image_pending == 0) {
                    // Processed all
                    Optimizer.$optimized_number.html(total_image);
                    style_for_no_image_pending();
                    return;
                }

                if (force == 'true') {
                    progress_bar_update(0, total_image);
                    tp_image_optimizer(0, total_image, force, list_size);
                    // Total number image with image_size
                    $('.compressed-image').html(0);
                    // CSS
                    style_for_force_mode();
                } else {
                    // Continue optimize pending image
                    Optimizer.$optimized_number.html(total_image - total_image_pending);
                    progress_bar_update(total_image - un_optimized, total_image);
                    tp_image_optimizer(0, total_image_pending, force, list_size);
                    // Update total number image( with size) 
                    start_total_image_optimized_with_size();
                }
            });
        })


        var percent;
        var xhr;
        var data;
        var error_count;

        // Variable optimize success
        var old_val;
        var new_val;
        var text_optimize_success;
        var getUncompress;

        /**
         * Ajax Optimizer for an attachment image
         * @param double id
         * @returns {undefined}
         * @since 1.0.0
         */
        function tp_image_optimizer(number, max, force, list_size) {
            var success_flag = true;
            error_count = parseInt($(".io-error").html());
            xhr = $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    start: number,
                    action: 'process_optimize_image',
                    force: force,
                    optimize_key: $("#img_key_ajax").val(),
                    list_size: list_size,
                    error_count: error_count
                },
                success: function (result) {
                    data = result.data;
                    // If error
                    if (result.success == false) {
                        // IF detect error on load Attachemnt ID on SQL - Reload ID
                        if (data.reload == true) {
                            success_flag = false;
                            if (force == 'true') {
                                tp_image_optimizer(number, max, force, list_size);
                            } else {
                                tp_image_optimizer(0, max, force, list_size);
                            }
                        } else {
                            // If have an error, logging it to log bar
                            Optimizer.$error_detect.html(error_count + 1);
                            // Append this error to Log 
                            $error_log = tp_image_optimizer_lang.error.undefined;
                            if (data.hasOwnProperty('log')) {
                                $error_log = data.log;
                            }
                            Optimizer.$show_log.html($error_log);
                            log_error_on_compress_progress(result.data.id, $error_log);
                        }
                    } else {
                        text_optimize_success = tp_image_optimizer_lang.success.optimized + data.id;
                        Optimizer.$show_log.html(text_optimize_success);
                        getUncompress = parseInt(Stastics.$total_uncompress.html());
                        Stastics.$total_uncompress.html(getUncompress - 1);
                        // Update image optimized with size
                        old_val = $(".compressed-image").html();
                        new_val = parseInt(number_selected_size) + parseInt(old_val);


                        $(".compressed-image").html(new_val);
                        // Append this attachment to log with success status
                        append_success_compressed_to_log(data.id);

                        // Update stastics for detail table
                        if (data.hasOwnProperty('full_detail')) {
                            update_stastics_detail_after_optimized(data.id, data.full_detail.old_size, data.full_detail.new_size);
                        }
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    // check internet connection lost

                    if (XMLHttpRequest.readyState == 0) {
                        display_internet_conn_err();
                        return;
                    }
                }
            }).done(
                    function () {
                        if (success_flag == true) {
                            number++;
                            update_num = Optimizer.getPositionProgress();
                            // Increase 1 point to progress posstion
                            Optimizer.$optimized_number.html(update_num + 1);
                            if (number < max) {
                                // Update process bar +1
                                progress_bar_update(update_num + 1, total_image);
                                // Continue optimize progress with next image
                                tp_image_optimizer(number, max, force, list_size);
                            } else {
                                // Finish optmimize all
                                Optimizer.$progress_container.removeClass("active");
                                Optimizer.$label_process_bar.html(tp_image_optimizer_lang.success.success );
                                
                                // Detect error
                                if (parseInt(Optimizer.$error_detect.html()) > 0) {
                                    Optimizer.$show_log.html(tp_image_optimizer_lang.error.detect); // Detect some error, print notice
                                } else {
                                    Optimizer.$show_log.html(tp_image_optimizer_lang.success.done).addClass('finish-optimized'); // Finish all
                                    
                                }
                                // Show optimizer button and hide cancel button
                                Optimizer.styleStopOptimizer();
                                // Hide log loading
                                Log.hide_loading();
                                // Set status page to stop process - Usefull to prevent reload
                                $(".tp-image-optimizer").data('process', 'false');
                            }
                        }
                    }

            );
            /**
             * Event CANCEL when optimizing image
             */
            $(document).on("click", '.cancel_optimizer', function (e) {

                Log.hide_loading();
                Optimizer.$show_log.html(tp_image_optimizer_lang.main.pause);
                Optimizer.$label_process_bar.html(tp_image_optimizer_lang.success.success);
                Optimizer.styleStopOptimizer();
                xhr.abort();
                // Set status page to stop process - Usefull to prevent reload
                $(".tp-image-optimizer").data('process', 'false');
            });
        }

        /**
         * Update total images with size
         * 
         * @since 1.0.0
         */
        function start_total_image_optimized_with_size() {
            var number_processed = $('.optimized-number').html();
            number_selected_size = $('.compressed-image').data('number-selected-size');
            var total_image_size_optimizer = parseInt(number_selected_size) * parseInt(number_processed);
            $(".compressed-image").html(total_image_size_optimizer);
            $(".total-compressed-images").html($('.io-total-img-size span').html());
        }


        /**
         * Style for Force mode
         * 
         * @since 1.0.0
         */
        function style_for_force_mode() {
            // Re-Optimize all image
            Optimizer.$error_detect.html(0);
            Optimizer.$optimized_number.html(0);
        }
        /**
         * Style for Completed mode
         * 
         * @since 1.0.0
         */
        function style_for_no_image_pending() {
            start_total_image_optimized_with_size();
            // Hide Cancel button and show Optimizer button
            Optimizer.styleStopOptimizer();
            // Hide progress when no more image need optimized
            Optimizer.$progress_container.removeClass('active');
            // Notice all ready image have been compressed
            Optimizer.$show_log.html(tp_image_optimizer_lang.success.complete);
            // Processing -> processed
            Optimizer.$label_process_bar.html(tp_image_optimizer_lang.success.success);
            // Log
            Log.hide_loading();
            Log.collapse();
            // Set status page to stop process - Usefull to prevent reload
            $(".tp-image-optimizer").data('process', 'false');
        }

        /**
         * Update class and css when starting optimized
         * 
         * @returns void
         * @since 1.0.0
         */
        function update_style_before_optimize() {
            // Active sticky box log
            $(".io-sticky-notice").addClass("active");
            $(".io-sticky-notice .sticky-content").addClass("active");
            // Open sticky box
            Log.$wrapper.addClass("active");
            Log.draggable();
            // Show notify on Sticky box
            Log.show_current_notify();
        }

        /**
         * Update progress bar with value and total
         * 
         * @param {int} value
         * @param {int} total
         * @returns {void}
         * @since 1.0.0
         */
        function progress_bar_update(value, total) {
            percent = ((parseInt(value)) / (total)) * 100;
            Optimizer.$progress_bar.css("width", percent + "%");
        }

        /**
         * Update list size image optimize
         * 
         * @since 1.0.0
         */
        $(document).on('click', '#io-update-size', function (e) {
            e.preventDefault();
            var list_sizes = [];
            var size;
            $("input[name='io-list-size[]']:checked").each(function (e) {
                size = $(this).val();
                list_sizes.push(size);
            });
            Size.$spinner.addClass('is-active');
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    listsizes: list_sizes.toString(),
                    action: 'update_sizes',
                },
                success: function (html) {
                },
                error: function (e) {
                }
            }).done(function () {
                Size.$spinner.removeClass('is-active');
            });
        })


        /**
         * Detect lost internet connection !
         * @since 1.0.0
         */

        function display_internet_conn_err() {
            $('.io-display-notify').html(tp_image_optimizer_lang.error.connection);
            $('.io-display-notify').addClass('active');
        }

        var content_append;
        var text;
        /**
         * Append success log
         * @since 1.0.0
         */
        function append_success_compressed_to_log($attachment_id) {
            text = tp_image_optimizer_lang.success.optimized + $attachment_id;
            content_append = "<li data-id=" + $attachment_id + ">"
                    + "<span class='sticky-number-id'></span>"
                    + "<a href ='#' data-id=" + $attachment_id + ">" + text + "</a>"
                    + "</li>";
                    Log.$content.find("ul").prepend(content_append);
        }

        /**
         * 
         * @param {type} size
         * @returns {Object.size}
         * @since 1.0.0
         */
        function log_error_on_compress_progress($attachment_id, $log) {
            content_append = "<li data-id=" + $attachment_id + " >"
                    + "<span class='sticky-number-id error'></span>"
                    + "<a href ='#' data-id=" + $attachment_id + "> #" + $attachment_id + ' - ' + $log + "</a>"
                    + "</li>";
            Log.$content.find("ul").prepend(content_append);
        }


        /**
         * 
         * @param type $size
         * @return String Display size ( Byte, KB, MB )
         */
        function tp_image_optimizer_dislay_size(size) {

            var display_size;
            if (size < 1024) {
                display_size = size + tp_image_optimizer_lang.size.B;
            } else if (size < 1024 * 1024) {
                size = (size / (1024)).toFixed(2);
                display_size = size + tp_image_optimizer_lang.size.KB;
            } else {
                size = (size / (1024 * 1024)).toFixed(2);
                display_size = size + tp_image_optimizer_lang.size.MB;
            }
            return display_size;
        }



        /*
         * Uninstall
         * Not show on default panel
         * Usefull for developer
         * 
         * @since 1.0.1
         */
        $(document).on('click', '#uninstall', function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'uninstall',
                },
                beforeSend: function () {
                    $('.io-sizes-option-wrapper .spinner').addClass('is-active');
                },
                success: function (html) {
                    $('.io-sizes-option-wrapper .spinner').removeClass('is-active');
                },
                error: function (e) {
                }

            }).done(function () {
                setTimeout(function () {
                    location.reload(); // Reload the page.
                }, 2000);
            })
        })

        /**
         * Compress option for specific image
         * 
         * @since 1.0.1
         */
        $(document).on('click', '.single-compress', function (e) {
            e.preventDefault();
            Log.open();
            $(this).remove();
            var id = $(this).attr('href');
            $('.compress-' + id + ' .spinner').addClass('is-active');
            var data_result;
            $.ajax({
                type: 'POST',
                url: tp_image_optimizer_admin_js.ajax_url,
                data: {
                    action: 'process_optimize_image',
                    id: id,
                },
                success: function (result) {
                    data_result = result;
                    var data = result.data
                    if (data.hasOwnProperty('full_detail')) {
                        update_stastics_detail_after_optimized(data.id, data.full_detail.old_size, data.full_detail.new_size);
                    }

                },
                error: function (e) {

                },
                complete: function () {
                    $('.compress-' + id + ' .spinner').remove();
                    if (data_result.success) {

                    } else {
                        $('.compress-' + id).append('<span class="faq-compress_error"></span>');
                    }
                    delete id;
                }
            });
        })

        /**
         * Update stastics for an image after ajax completed
         * 
         * @param int attachment_id 
         * @param double orginal_size
         * @param double current_size
         * @returns void
         * @since 1.0.2
         */
        function update_stastics_detail_after_optimized(attachment_id, original_size, current_size) {
            // Caculator
            var new_size = tp_image_optimizer_dislay_size(current_size);
            var saving = original_size - current_size;
            var percent_raw = ((saving / original_size) * 100).toFixed(2);
            var percent = percent_raw + '%';
            // Count saving
            var saving = original_size - current_size;
            if (percent_raw > 1) {
                // New size 
                $('.current_size .table-detail-' + attachment_id).html(new_size);
                // Saving
                $('.detail-saving-' + attachment_id).html(tp_image_optimizer_dislay_size(saving));

                var percent = ((saving / original_size) * 100).toFixed(2);
                percent = percent + '%'
                $('.percent-saving-' + attachment_id).html(percent);
            }
            // Show success icon
            $('.compress-' + attachment_id).html('');
            $('.compress-' + attachment_id).append('<span class="success-optimize"></span>');
        }
    });
})(jQuery);