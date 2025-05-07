<?php 

namespace SeasonalContent\Models;

class Backup
{
    public int $postId;

    public int $parentId;

    public string $name;

    public $createdAt;

    public function save():self  {
        $backups = get_option(SEASONALCONTENT_PREFIX.'elementor_main_data_backups', false);
        if(!$backups) {
            $data = [];
            $data[] = serialize($this);
            update_option(SEASONALCONTENT_PREFIX.'elementor_main_data_backups', json_encode($data, JSON_UNESCAPED_UNICODE));
            return $this;
        }

        $backups = json_decode($backups);
        $backups[] = serialize($this);
        update_option(SEASONALCONTENT_PREFIX.'elementor_main_data_backups', json_encode($backups, JSON_UNESCAPED_UNICODE));
        return $this;
    }

    public static function getBackup($postId):self {
        $backups = get_option(SEASONALCONTENT_PREFIX.'elementor_main_data_backups', false);
        if(!$backups) {
            return new self();
        }

        $backups = json_decode($backups);
        foreach ($backups as &$backup) {
            $tmpBackup = unserialize($backup);
            if($tmpBackup->parentId == $postId) {
                return $tmpBackup;
            }
        }   

    }


    public static function init():self {
        return new self();
    }

    public function setPostId(int $postId):self {
        $this->postId = $postId;
        return $this;
    }

    public function setParentId(int $parentId):self {
        $this->parentId = $parentId;
        return $this;
    }

    public function setName(string $name):self {
        $this->name = $name;
        return $this;
    }

    public function setCreatedAt($createdAt):self {
        $this->createdAt = $createdAt;
        return $this;
    }

}