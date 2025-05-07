<?php 

namespace SeasonalContent\Core;

class Cron extends Singleton
{

    const HOOK = SEASONALCONTENT_PREFIX."update_current_category";


    private HookManager $hookManager;

    public function __construct() {
    }

    public function setHookManager($hookManager) {
        $this->hookManager = $hookManager;
    }

    public function scheduleEvent( \SeasonalContent\DTO\CronAction ...$events) {
        $hooks = [];
        foreach ($events as $key => $event) {
            $hooks[] = \SeasonalContent\DTO\Hook::set(
                self::HOOK,
                $event->callback
            );
        }

        $this->hookManager->registerActions(...$hooks);

    }

    public static function checkScheduleEvent() {
        if( !wp_next_scheduled(self::HOOK ) ) {
            $timestamp = strtotime('04:00:00');
            if ($timestamp < time()) {
                $timestamp = strtotime('tomorrow 04:00:00');
            }
            wp_schedule_event($timestamp, 'daily', self::HOOK);
        }
    }

    public static function hasScheduleEvent():bool {
        if(wp_next_scheduled( self::HOOK )) {
            return true;
        }
        return false;
    }

    public static function deleteScheduleEvent() {
        wp_unschedule_hook( self::HOOK );
    }
}