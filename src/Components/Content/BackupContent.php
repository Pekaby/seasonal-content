<?php 

namespace SeasonalContent\Components\Content;

class BackupContent
{
    public static function hasMainBackup($postId):bool {
        $backups = get_option(SECOEL_PREFIX.'elementor_main_data_backups', false);
        if(!$backups) return false;
        $backups = json_decode($backups);

        foreach ($backups as $backup_serelized) {
            $backup = unserialize($backup_serelized, [\SeasonalContent\Models\Backup::class]);
            if($backup->parentId === $postId){
                return true;
            }
        }
        
        return false;
    }

    private static function getBackupRawData():array {
        $backups = get_option(SECOEL_PREFIX.'elementor_main_data_backups', false);
        if(!$backups) return [];
        $backups = json_decode($backups);
        return $backups;
    }

    public static function getAllMainBackups():array {
        $backups = get_option(SECOEL_PREFIX.'elementor_main_data_backups', false);
        if(!$backups) return [];
        $backups = json_decode($backups);

        $mainBackups = [];
        foreach ($backups as $backup_serelized) {
            $mainBackups[] = unserialize($backup_serelized, [\SeasonalContent\Models\Backup::class]);
        }
        
        return $mainBackups;
    }

    public static function getMainBackup($postId) {
        $backups = self::getAllMainBackups();

        foreach ($backups as $key => $backup) {
            if($backup->parentId == $postId) {
                return $backup;
            }
        }

    }

    public static function createMainBackup($postId): \SeasonalContent\Models\Backup {
        $elementor_data = wp_unslash( get_post_meta($postId, '_elementor_data', true) );

        $postData = get_post($postId);

        $postData->post_status = 'inherit';
        $postData->comment_status = 'closed';
        $postData->ping_status = 'closed';
        $postData->post_name = (string) $postData->ID . '-revision-v1';
        $postData->post_parent = $postId;
        unset($postData->ID);

        $backupId = wp_insert_post( (array) $postData);
        if(!is_int($backupId) || !$backupId) throw new \SeasonalContent\Core\Exceptions\BackupException('Cannot create Main backup');

        update_post_meta($backupId, '_elementor_data', wp_slash( $elementor_data ));
        $prefix = bin2hex(random_bytes(10));
        $backup = \SeasonalContent\Models\Backup::init()->setPostId($backupId)
                                                    ->setParentId($postId)
                                                    ->setName("_main_backup_".$prefix)
                                                    ->setCreatedAt(date("Y-m-d H:i:s"))
                                                    ->save();


        
        return $backup;                                                
    }

    public static function updateMainBackup($postId):  \SeasonalContent\Models\Backup {
        $backup = \SeasonalContent\Models\Backup::getBackup($postId);
        if(!isset($backup->postId)) throw new \SeasonalContent\Core\Exceptions\BackupException('Can\'t update main backup. Main backup didn\'t find.'); 

        $elementor_data = get_post_meta($postId, '_elementor_data', true);

        $postData = get_post($postId);

        $postData->post_status = 'inherit';
        $postData->comment_status = 'closed';
        $postData->ping_status = 'closed';
        $postData->post_name = (string) $postData->ID . '-revision-v1';
        $postData->post_parent = $postId;
        $postData->ID = $backup->postId;

        $backupId = wp_insert_post( (array) $postData);

        if(!is_int($backupId) || !$backupId) throw new \SeasonalContent\Core\Exceptions\BackupException('Cannot create Main backup');

        update_post_meta($backup->postId, '_elementor_data', wp_slash( $elementor_data ));

        return $backup;  
    }


    public static function createBackup($postId): \SeasonalContent\Models\Backup {
        $elementor_data = get_post_meta($postId, '_elementor_data', true);

        $postData = get_post($postId);

        $postData->post_status = 'inherit';
        $postData->comment_status = 'closed';
        $postData->ping_status = 'closed';
        $postData->post_name = (string) $postData->ID . '-revision-v1';
        $postData->post_parent = $postId;
        unset($postData->ID);

        $backupId = wp_insert_post( (array) $postData);
        if(!is_int($backupId) || !$backupId) throw new \SeasonalContent\Core\Exceptions\BackupException('Cannot create backup');

        $el_bk_id = update_post_meta($backupId, '_elementor_data', $elementor_data);
        $prefix = bin2hex(random_bytes(10));
        $backup = \SeasonalContent\Models\Backup::init()->setPostId($backupId)
                                                    ->setParentId($postId)
                                                    ->setName("_main_backup_".$prefix)
                                                    ->setCreatedAt(date("Y-m-d H:i:s"));


        
        return $backup;
    }

    public static function loadBackup($postId) {
        $backup = \SeasonalContent\Models\Backup::getBackup($postId);

        if(!isset($backup->postId)) throw new \SeasonalContent\Core\Exceptions\BackupException("Backup didn't find.");

        $backupPost = get_post($backup->postId);
        $backupElementor = get_post_meta($backup->postId, '_elementor_data', true);

        $file = fopen(SECOEL_DIR . 'inputLoadBackup.json', 'w');
        fwrite($file, $backupElementor);

        $postData = get_post($postId);

        $postData->post_content = $backupPost->post_content;
        $postData->post_title = $backupPost->post_title;
        $backuped = wp_update_post($postData);

        update_post_meta($backup->parentId, '_elementor_data', wp_slash( $backupElementor ));
        if(!$backuped) throw new \SeasonalContent\Core\Exceptions\BackupException('Can\'t load backup' . PHP_EOL);

        // update_option(SECOEL_PREFIX.$postId."_current_season", 0); // it should be current season
    }


}