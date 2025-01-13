<?php 

namespace SeasonalContent\Templates;

class Addons implements Template
{

    private $path = SECOEL_DIR . 'assets/view/addons_managment';


    public function render($data = []) : void {
        if (!is_array($data)) {
            $data = [];
        }
        $addons = \SeasonalContent\Components\Addon\AddonComponent::getInstance();
        $data = $addons->getAddonsInformation();
        // var_dump($data);
        \SeasonalContent\Core\Drawer::loadDocument($this->path, $data);
    }
}