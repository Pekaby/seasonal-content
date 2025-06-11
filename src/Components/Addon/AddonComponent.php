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
                $this->installed[$slug] = [
                    'title' => $addon->getTitle(),
                    'version' => $addon->getVersion(),
                    'description' => $addon->getDescription(),
                    'url' => 'https://t.me/Pekaby'
                ];
            }
        }
    }

    private function getActivatedAddons()  {
        $activatedPlugins = get_option( 'active_plugins' );
        foreach ($activatedPlugins as $plugin) {
            $pluginSlug = explode('/', $plugin)[0];
            if( array_key_exists($pluginSlug, $this->installed) ) {
                $this->activatedAddons[$pluginSlug] = $this->installed[$pluginSlug];
            }
        }
    }

    private function loadAddonsInformation() {
        foreach ($this->aviableAddons as $slug => $addonInformation) {
            if(array_key_exists($slug, $this->activatedAddons)) {
                $this->addonsInformation[__('activated', 'seasonal-content')][$slug] = $addonInformation; 
                continue;
            }
            if(array_key_exists($slug, $this->installed)) {
                $this->addonsInformation[__('installed', 'seasonal-content')][$slug] = $addonInformation; 
                continue;
            }

            $this->addonsInformation[__('aviable', 'seasonal-content')][$slug] = $addonInformation;
        }
        foreach ($this->installed as $slug => $addonInformation) {
            if(!array_key_exists($slug, $this->aviableAddons)) {
                $this->addonsInformation[__('activated', 'seasonal-content')][$slug] = $addonInformation; 
            }
        }
        ksort($this->addonsInformation);
        
    }

    private function getAviablesAddons() {
        return $this->aviableAddons = [
            'seasonal-content-addon-slides' => [
                'title' => 'Seasonal Content Addon - Slides!',
                'version' => '0.1',
                'description' => 'Easily manage and update content for sliders and carousels based on seasonal categories. This addon allows you to modify text, images, and other content within sliders and carousels automatically, keeping your website fresh and up-to-date with minimal effort.',
                'url' => 'https://t.me/Pekaby'
            ],
        ];
    }

    public function getAddonsInformation() {
        return $this->addonsInformation;
    }

}