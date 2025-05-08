<?php 

namespace SeasonalContent\Components\Content;

use SeasonalContent\Components\Component;

class ContentComponent extends \SeasonalContent\Core\Singleton implements Component
{
    const NAME = 'ContentComponent';

    private int $postId;

    private int $currentSeason;
    private int $pageSeason;

    public function __construct() {
        $this->currentSeason = get_option(SEASONALCONTENT_PREFIX.'current_season', 0);
    }

    public function setPostId(int $post_id): void {
        $this->postId = $post_id;
    }

    public function content() {
        if(is_singular()) {
            $this->postId = get_the_ID();
            if(\Elementor\Plugin::$instance->documents->get( $this->postId )->is_built_with_elementor()){
                return (self::changeNeed()) ? self::changeContent() : null;
            }
        }
    }

    public function updateContent($postId) {
        if(\Elementor\Plugin::$instance->documents->get( $postId )->is_built_with_elementor()) {
            $this->postId = $postId;
            $this->getPageSeason();
            self::changeContent();
        }
    }

    private function getPageSeason() {
        if(isset($this->pageSeason)) return $this->pageSeason;
        $this->pageSeason = get_option(SEASONALCONTENT_PREFIX.$this->postId."_current_season", 0);
        return $this->pageSeason;
    }

    private function changeNeed():bool {
        $this->getPageSeason();
        return ($this->currentSeason == $this->pageSeason) ? false : true;
    }

    private function changeContent() {
        if(!BackupContent::hasMainBackup($this->postId)) {
            BackupContent::createMainBackup($this->postId);
        }
        BackupContent::createBackup($this->postId);
        $category = \SeasonalContent\Models\Category::getCategory($this->currentSeason);
        if( !isset($category->id) ) return BackupContent::loadBackup($this->postId);

        
        $elementor_data = get_post_meta($this->postId, '_elementor_data', true);

        ContentChanger::setTypes(\SeasonalContent\Core\TypeController::getRegisteredTypes());
        $changed_elementor_data = ContentChanger::change(wp_unslash(json_decode($elementor_data)), $category);
        
        update_post_meta($this->postId, '_elementor_data', wp_slash(json_encode($changed_elementor_data, JSON_UNESCAPED_UNICODE)));

        update_option(SEASONALCONTENT_PREFIX.$this->postId."_current_season", $category->id, true);
    }

    public static function saveData($postId) {
        if( !get_option(SEASONALCONTENT_PREFIX . 'update_backup_settings', true) ) {
            return;
        }
        if(!BackupContent::hasMainBackup($postId)) {
            BackupContent::createMainBackup($postId);
        }
        $backup = BackupContent::getMainBackup($postId);
        if(!$backup) return;

        $backupData = get_post_meta($backup->postId, '_elementor_data', true);
        $currentData = get_post_meta( $postId, '_elementor_data', true );
        error_log("backup_data ");
        error_log(print_r($backupData, true));
        error_log("Current Data");
        error_log(print_r($currentData, true));
        $backupWithNewSettings = ContentChanger::changeSettings( wp_unslash(json_decode($currentData, true)), wp_unslash(json_decode($backupData, true)));
    
        update_post_meta($backup->postId, '_elementor_data', wp_slash(json_encode($backupWithNewSettings, JSON_UNESCAPED_UNICODE)));
    }

    public static function getMainBackups() {
        return BackupContent::getAllMainBackups();
    }

    public function loadMainBackup($postId) {
        return BackupContent::loadBackup($postId);
    }

    public function updateMainBackup($postId) {
        $this->postId = $postId;
        return (!BackupContent::hasMainBackup($this->postId)) ? BackupContent::createMainBackup($this->postId) : BackupContent::updateMainBackup($this->postId);
    }

}