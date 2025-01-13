<?php 

namespace SeasonalContent\Components\Addon;

use SeasonalContent\Components\Component;

class AddonComponent extends \SeasonalContent\Core\Singleton implements Component
{
    private array $installed = [];
    private array $aviableAddons = [];

    private array $activatedAddons = [];

    private array $addonsInformation = [];

    public function __construct() {
        $this->getAviablesAddons();
        $this->getInstalledAddons();
        $this->getActivatedAddons();
        $this->loadAddonsInformation();
    }

    private function getInstalledAddons() {
        foreach ($this->aviableAddons as $slug => $addonInformation) {
            foreach (get_plugins() as $plugin) {
                if($addonInformation['title'] == $plugin['Name']) {
                    $this->installed[$slug] = $addonInformation['title'];
                }
            }
        }
        $loadedAddons = \SeasonalContent\Core\AddonManager::getInstance()->getAddons();
        foreach ($loadedAddons as $slug => $addon) {
            if( !array_key_exists($slug, $this->installed) ) {
                $this->installed[$slug] = $addon::getTitle();
            }
        }
        // return $this->installed;
    }

    private function getActivatedAddons()  {
        // var_dump(get_option( 'active_plugins' ));
        $activatedPlugins = get_option( 'active_plugins' );
        foreach ($activatedPlugins as $plugin) {
            $pluginSlug = explode('/', $plugin)[0];
            if( array_key_exists($pluginSlug, $this->installed) ) {
                $this->activatedAddons[$pluginSlug] = $this->installed[$pluginSlug];
            }
        }
        // var_dump($this->activatedAddons);

    }

    private function loadAddonsInformation() {
        foreach ($this->aviableAddons as $slug => $addonInformation) {
            if(array_key_exists($slug, $this->activatedAddons)) {
                $this->addonsInformation['activated'][$slug] = $addonInformation; 
                continue;
            }
            if(array_key_exists($slug, $this->installed)) {
                $this->addonsInformation['installed'][$slug] = $addonInformation; 
                continue;
            }

            $this->addonsInformation['aviable'][$slug] = $addonInformation;
        }
        
    }

    private function getAviablesAddons() {
        return $this->aviableAddons = [
            'seasonal-content-sliders' => [
                'title' => 'Seasonal Content Addon - Sliders!',
                'version' => '0.1',
                'description' => 'Allows you change sliders content',
                'url' => 'https://t.me/Pekaby'
            ],
            'seasonal-content-ultimate-backgrounds' => [
                'title' => 'Seasonal Content Addon - Ultimate Backgrounds!',
                'version' => '3.1',
                'description' => 'Allows you change backgrounds',
                'url' => 'https://t.me/Pekaby',
            ],
            'seasonal-content-titles' => [
                'title' => 'Seasonal Content Addon - Titles!',
                'version' => '1.1',
                'description' => 'Allows you change backgrounds',
                'url' => 'https://t.me/Pekaby',
            ]
        ];
    }

    public function getAddonsInformation() {
        return $this->addonsInformation;
    }

}