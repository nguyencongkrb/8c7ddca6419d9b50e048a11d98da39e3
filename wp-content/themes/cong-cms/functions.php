<?php
// Add custom Theme Functions here
//Copy từng phần và bỏ vào file functions.php của theme:


//Tùy chỉnh admin footer
function custom_admin_footer()
{
    echo 'Thiết kế bởi <a href="http://cong-cms.net/" target="blank">Công CMS</a>';
}

add_filter('admin_footer_text', 'custom_admin_footer');


//Ẩn các panel không cần thiết
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');

function my_custom_dashboard_widgets()
{
    global $wp_meta_boxes;

    // Right Now - Comments, Posts, Pages at a glance
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);

    // Recent Comments
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);

    // Incoming Links
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);

    // Plugins - Popular, New and Recently updated WordPress Plugins
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);

    // WordPress Development Blog Feed
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);

    // Other WordPress News Feed
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);

    // Quick Press Form
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);

    // Recent Drafts List
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
}


//Ẩn Welcome Panel:
add_action('load-index.php', 'hide_welcome_panel');

function hide_welcome_panel()
{
    $user_id = get_current_user_id();

    if (1 == get_user_meta($user_id, 'show_welcome_panel', true))
        update_user_meta($user_id, 'show_welcome_panel', 0);
}


//Thêm panel


//Xóa logo wordpress
add_action('admin_bar_menu', 'remove_wp_logo', 999);

function remove_wp_logo( $wp_admin_bar )
{
    $wp_admin_bar->remove_node('wp-logo');
}


//Ẩn cập nhật woo

//Remove WooCommerce's annoying update message
remove_action('admin_notices', 'woothemes_updater_notice');

// REMOVE THE WORDPRESS UPDATE NOTIFICATION FOR ALL USERS EXCEPT ADMIN
global $user_login;
get_currentuserinfo();
if (!current_user_can('update_plugins')) {
    // checks to see if current user can update plugins
    add_action('init', create_function('$a', "remove_action( 'init', 'wp_version_check' );"), 2);
    add_filter('pre_option_update_core', create_function('$a', "return null;"));
}

//xoa mã bưu điện thanh toán
add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
function custom_override_checkout_fields( $fields )
{
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_company']);


    return $fields;
}

// Custom Dashboard
function my_custom_dashboard()
{
    $screen = get_current_screen();
    if ($screen->base == 'dashboard') {
        include 'admin/dashboard-panel.php';
    }
}

add_action('admin_notices', 'my_custom_dashboard');


add_filter('woocommerce_empty_price_html', 'custom_call_for_price');

function custom_call_for_price()
{
    return '<span class="lien-he-price">'. __('Liên hệ').'</span>';
}

function register_my_menu()
{
    register_nav_menu('product-menu', __('Menu Danh mục'));
}

add_action('init', 'register_my_menu');


//Doan code thay chữ giảm giá bằng % sale

add_filter('woocommerce_sale_flash', 'dvd_woocommerce_sale_flash', 10, 2);
function dvd_woocommerce_sale_flash( $post, $product )
{
    global $product;
    $sale_price    = $product->get_sale_price();
    $regular_price = $product->get_regular_price();
    $tmp           = ($sale_price * 100) / $regular_price;

    return '<div class="onsale-div "><span class="onsale-giam">- ' . number_format(100 - $tmp, 2) . '%</div></span>';
}

//* Add stock status to archive pages
add_filter('woocommerce_get_availability', 'custom_override_get_availability', 1, 2);

// The hook in function $availability is passed via the filter!
function custom_override_get_availability( $availability, $_product )
{
    if ($_product->is_in_stock()) $availability['availability'] = __('Còn hàng', 'woocommerce');

    return $availability;
}

// Thay doi duong dan logo admin
function wpc_url_login()
{
    return "http://cong-cms.net/"; // duong dan vao website cua ban
}

add_filter('login_headerurl', 'wpc_url_login');
// Thay doi logo admin wordpress
function login_css()
{
    wp_enqueue_style('login_css', get_stylesheet_directory_uri() . '/_assets/css/login.css'); // duong dan den file css moi
}

add_action('login_head', 'login_css');