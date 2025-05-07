<?php 

namespace SeasonalContent\Components\Menu;

class AdminMenuComponent implements \SeasonalContent\Components\Component
{

    const NAME = 'AdminMenuComponent';

    private array $menuItems = [];
    private array $subMenuItems = [];

    public function __construct() {
       
    }

    public function addAdminMenu(MenuItem $item):self {
        $this->menuItems[] = $item;
        return $this;
    }

    public function addSubAdminMenu(SubMenuItem $item):self {
        $this->subMenuItems[] = $item;
        return $this;
    }


    public function registerMenus() {
        foreach ($this->menuItems as $item) {
            // var_dump($item);
            add_menu_page(
                $item->pageTitle,
                $item->menuTitle,
                $item->capability,
                $item->menuSlug,
                $item->callback,
                $item->iconUrl,
                $item->position
            );
        }
        foreach ($this->subMenuItems as $item) {
            add_submenu_page(
                $item->parentSlug,
                $item->pageTitle,
                $item->menuTitle,
                $item->capability,
                $item->menuSlug,
                $item->callback,
            );
        }
    }





}