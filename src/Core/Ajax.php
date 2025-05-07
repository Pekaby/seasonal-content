<?php 

namespace SeasonalContent\Core;

use Validator;

class Ajax extends Singleton
{

    protected $categories = [];
    private array $components = [];

    public function __construct() {}

    public function _setComponents(\SeasonalContent\Components\Component ...$components):void {
        foreach ($components as $component) {
            $this->components[$component::NAME] = $component;
        }
    }

    private function getComponent(string $name) {
        if(array_key_exists($name, $this->components)) {
            return $this->components[$name];
        }
        return null;
    }

    private function sanitize(mixed $data): mixed {
        if ( is_array($data) ) {
            foreach ($data as $key => &$value) {
                $value = $this->sanitize($value);
            }
            return $data;
        }
        return sanitize_text_field($data);
    }

    public function requestHandler(): void {
        $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

        if (!isset($nonce) || !wp_verify_nonce($nonce, 'seasonalcontent_security')) {
            wp_send_json_error(['message' => 'Invalid Nonce'], 400);
            return;
        }
        if (!$_POST || !isset($_POST['method'], $_POST['data']) || !is_array($_POST['data'])) {
            wp_send_json_error(['message' => 'Invalid request'], 400);
            return;
        }
    
        if (method_exists($this, sanitize_text_field(wp_unslash($_POST['method'])))) {
            $method = sanitize_text_field(wp_unslash($_POST['method']));

            $validator = new \SeasonalContent\Support\Validator($method);
            $this->$method( $validator->validate( $this->sanitize(wp_unslash($_POST['data']) )) );

            wp_send_json_success(['message' => 'Method executed successfully']);
            return;
        }
    
        wp_send_json_error(['message' => 'Method not found'], 400);
    }

    public function saveCategories($categories){
        // var_dump($categories);
        foreach ($categories as $category) {
            $this->getComponent('CategoryComponent')->categories[] = \SeasonalContent\Models\Category::init()->setTitle($category['title'])
                                                                             ->setSlug(\SeasonalContent\Support\Translitiration::translit($category['title']))
                                                                             ->setStartDate($category['date_start'])
                                                                             ->setEndDate($category['date_end'])
                                                                             ->setCreatedBy(wp_get_current_user()->ID)
                                                                             ->setId((int) $category['id']);
        }
        $this->getComponent('CategoryComponent')->saveCategories();
        wp_send_json_success((array) $this->getComponent('CategoryComponent')->categories, 200, JSON_UNESCAPED_UNICODE); 
    }

    public function deleteCategory($ids) {
        foreach ($ids as $id) {
            $category = \SeasonalContent\Models\Category::getCategory($id);
            if(isset($category->id)) $category->delete();
        }
    }

    public function restoreMainBackup($ids) {
        foreach ($ids as $id) {
            $backup = \SeasonalContent\Models\Backup::getBackup($id);
            if($backup->parentId == $id) {
                $this->getComponent('ContentComponent')->loadMainBackup($id);
                return;
            }
        }
    }

    public function updateSeasonContent($ids) {
        foreach ($ids as $id) {
            $this->getComponent('ContentComponent')->updateContent($id);
        }
    }

    public function updateMainBackup($ids) {
        foreach ($ids as $id) {
            $this->getComponent('ContentComponent')->updateMainBackup($id);
        }
    }

}