<?php 

namespace SeasonalContent\Core;

class Drawer 
{
    public function __construct() {
        // exit("DRAWER");
    }

    public static function loadDocument(string $file, array $data = []): string {
        extract($data);
        include (string)$file . '.php';
        return SEASONALCONTENT_DIR . 'assets/templates' . $file . '.php';
    }
}