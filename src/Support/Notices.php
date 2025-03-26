<?php

namespace SeasonalContent\Support;

class Notices
{
    public static function ElementorNotActivated() {
        $class = 'notice notice-error';
	    $message = __( 'You should install and activate Elementor before using Seasonal Content!', 'seasonal-content' );

	    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    }
}