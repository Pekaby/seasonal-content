<?php 
/* 
Plugin Name: Seasonal Content
Plugin URI: http://t.me/Pekaby
Description: Seasonal Content allows you to change content by your own categories
Author: Mikhail
Author URI: https://t.me/pekaby/
Version: 2.6
Requires at least: 6.7
Tested up to: 6.7
Requires PHP: 7.4
Text Domain: seasonal-content
Domain Path: /languages
*/


require __DIR__ . '/vendor/autoload.php';

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if( !defined( 'SECOEL_PREFIX' ) ) {
    define('SECOEL_PREFIX', 'secoel_');
}

if( !defined( 'SECOEL_DIR' ) ) {
    define('SECOEL_DIR', __DIR__ . '/');
}

if( !defined( 'SECOEL_INDEX' ) ) {
    define('SECOEL_INDEX', __FILE__);
}

if( !defined( 'SECOEL_PLUGIN_URL' ) ) {
    define( 'SECOEL_PLUGIN_URL', plugins_url() );
}

SeasonalContent\Core\Plugin::getInstance()->run();
