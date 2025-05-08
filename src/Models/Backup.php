<?php 

namespace SeasonalContent\Models;

class Backup
{
    public int $postId;

    public int $parentId;

    public string $name;

    public $createdAt;

    public function save():self  {
        $status = update_post_meta($this->parentId, '_seasonalcontent_main_backup', wp_slash( json_encode($this, JSON_UNESCAPED_UNICODE) ));
        if(!$status) throw new \SeasonalContent\Core\Exceptions\BackupException('Cannot save backup');
        return $this;
    }

    public static function getBackup(int $postId) {
        $data = wp_unslash( get_post_meta($postId, '_seasonalcontent_main_backup', true) );
        if( !$data ) return false;
  
        $std_backup = json_decode($data);
        $backup = self::init();
        foreach ($std_backup as $field => $value) {
            $backup->$field = $value;
        }
        return $backup;
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