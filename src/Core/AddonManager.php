<?php 

namespace SeasonalContent\Core;
use SeasonalContent\DTO;

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
        $this->hookManager->doAction(DTO\Hook::set(
            "SeasonalContent/beforeLoadAddons"
        ), $this->hookManager);

        $this->hookManager->doAction( 'SeasonalContent/loadAddons', $this->hookManager );

        // $this->hookManager->registerActions(DTO\Hook::set(
        //     'SeasonalContent/loadAddons',
        //     [$this, 'loadAddons']
        // ));
        do_action('SeasonalContent/initAddons');
    }

    public function loadAddon(Addon $addon) {
        if( !in_array($addon->getSlug(), $this->addons)){
            $this->addons[$addon->getSlug()] = $addon;
        }
    }

}