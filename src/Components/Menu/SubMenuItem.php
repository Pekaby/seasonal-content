<?php 

namespace SeasonalContent\Components\Menu;

class SubMenuItem
{
    public string $parentSlug;
    public string $pageTitle;
    public string $menuTitle;
    public string $capability;
    public string $menuSlug;
    public $callback;

    public function __construct(
        string $parentSlug,
        string $pageTitle,
        string $menuTitle,
        string $capability,
        string $menuSlug,
        ?callable $callback = null
    ) {
        $this->parentSlug = $parentSlug;
        $this->pageTitle = $pageTitle;
        $this->menuTitle = $menuTitle;
        $this->capability = $capability;
        $this->menuSlug = $menuSlug;
        $this->callback = $callback;
    }

    public static function set(
        string $parentSlug,
        string $pageTitle,
        string $menuTitle,
        string $capability,
        string $menuSlug,
        ?callable $callback = null
    ):self {
        return new self(
             $parentSlug,
            $pageTitle,
            $menuTitle,
            $capability,
            $menuSlug,
            $callback,
        );
    }

}