<?php 

namespace SeasonalContent\Core;

class Drawer 
{
    public function __construct() {
        // exit("DRAWER");
    }

    public static function adminNotice(string $message) { 
        echo '<div class="notice notice-warning">';
        echo '<p>';
        esc_html($message );
        echo '</p>';
        echo '</div>';
    }

    public static function loadDocument(string $file, array $data = []): string {
        extract($data);
        include (string)$file . '.php';
        return SECOEL_DIR . 'assets/templates' . $file . '.php';
    }
}