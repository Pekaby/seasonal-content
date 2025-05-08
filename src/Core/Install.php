<?php 

namespace SeasonalContent\Core;

class Install
{
    public static function install() {
        if( !get_option(SEASONALCONTENT_PREFIX . 'installed', false) ) {
            $db = self::createDb();
            $cron = self::registerCron();
            if( $db && $cron ) {
                update_option(SEASONALCONTENT_PREFIX . 'installed', 1, false);
                return;
            } 
        }
    }

    public static function deactivate() {
        Cron::deleteScheduleEvent();
    }


    public static function uninstall() {
        global $wpdb;

        delete_option(SEASONALCONTENT_PREFIX . 'current_season');
        delete_option(SEASONALCONTENT_PREFIX . 'installed');

        $categoryTable = $wpdb->prefix . SEASONALCONTENT_PREFIX . 'categories';

        $wpdb->query("DROP TABLE `" . esc_sql($categoryTable) . "`");
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE `option_name` LIKE %s;",
                'seasonalcontent_%_current_season'
            )
        );
    }

    private static function createDb():bool{
        global $wpdb;

        $tableName =  esc_sql($wpdb->prefix . SEASONALCONTENT_PREFIX . 'categories');
        $charsetСollate = $wpdb->get_charset_collate();

        $sql = $sql = "CREATE TABLE $tableName (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            title VARCHAR(32) NOT NULL,
            slug VARCHAR(32) NOT NULL,
            date_start DATE NOT NULL,
            date_end DATE NOT NULL,
            created_by BIGINT(20) UNSIGNED NOT NULL,
            updated_at DATETIME NULL DEFAULT NULL,
            created_at DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (id)
        ) $charsetСollate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $result = dbDelta($sql);
        if(!empty($result)) return true;
        return false;
    }

    private static function registerCron() : bool {
        Cron::checkScheduleEvent();
        return Cron::hasScheduleEvent();
    }
}