<?php
defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

define( 'WP_ROCKET_ADVANCED_CACHE', true );
$rocket_cache_path = 'D:\sources\xampp/wp-content/cache/wp-rocket/';
$rocket_config_path = 'D:\sources\xampp/wp-content/wp-rocket-config/';

if ( file_exists( 'D:\sources\xampp\wp-content\plugins\wp-rocket\inc\front/process.php' ) ) {
	include( 'D:\sources\xampp\wp-content\plugins\wp-rocket\inc\front/process.php' );
} else {
	define( 'WP_ROCKET_ADVANCED_CACHE_PROBLEM', true );
}