<?php 
/* 
Plugin Name: Seasonal Content
Plugin URI: https://seasonalcontent.com
Description: Seasonal Content allows you to change (seasonal) content by your own categories
Author: Mikhail
Author URI: https://t.me/pekaby/
License: GPL v3.0
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Version: 1.0.0
Requires at least: 5.8
Tested up to: 6.7.2
Elementor tested up to: 3.28.1
Requires PHP: 7.4
Text Domain: seasonal-content
*/


require __DIR__ . '/vendor/autoload.php';

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if( !defined( 'SEASONALCONTENT_PREFIX' ) ) {
    define('SEASONALCONTENT_PREFIX', 'seasonalcontent_');
}

if( !defined( 'SEASONALCONTENT_DIR' ) ) {
    define('SEASONALCONTENT_DIR', __DIR__ . '/');
}

if( !defined( 'SEASONALCONTENT_INDEX' ) ) {
    define('SEASONALCONTENT_INDEX', __FILE__);
}

SeasonalContent\Core\Plugin::getInstance()->run();
