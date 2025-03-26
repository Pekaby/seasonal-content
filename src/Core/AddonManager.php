<?php 

namespace SeasonalContent\Core;

class AddonManager extends Singleton
{

    private $hookManager;

    private array $addons = [];

    public function setHookManager(HookManager $hookManager):void  {
        $this->hookManager = $hookManager;
    }

    public function init() {

    }

    public function getAddons(): array {
        return $this->addons;
    }


    public function initAddons() {
        do_action('SeasonalContent/initAddons');
    }

    public function loadAddons(Addon $addon) {
        if( !in_array($addon->getSlug(), $this->addons)){
            $this->addons[$addon->getSlug()] = $addon;
        }
    }

}