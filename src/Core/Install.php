<?php 

namespace SeasonalContent\Core;

class Install
{
    public static function install() {
        if( !get_option(SECOEL_PREFIX . 'installed', false) ) {
            $db = self::createDb();
            $cron = self::registerCron();
            if( $db && $cron ) {
                update_option(SECOEL_PREFIX . 'installed', 1, false);
                return;
            } 
        }
    }

    public static function deactivate() {
        Cron::deleteScheduleEvent();
    }

    private static function createDb():bool{
        global $wpdb;

        return $wpdb->query("CREATE TABLE IF NOT EXISTS `" . $wpdb->dbname . "`.`". $wpdb->prefix . SECOEL_PREFIX . 'categories' . "` ( `id` BIGINT NOT NULL AUTO_INCREMENT , `title` VARCHAR(32) NOT NULL , `slug` VARCHAR(32) NOT NULL , `date_start` DATE NOT NULL , `date_end` DATE NOT NULL , `created_by` BIGINT NOT NULL , `updated_at` DATETIME on update CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
    }

    private static function registerCron() : bool {
        Cron::checkScheduleEvent();
        return Cron::hasScheduleEvent();
    }
}