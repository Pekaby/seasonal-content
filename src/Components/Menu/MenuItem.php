<?php 

namespace SeasonalContent\Components\Menu;

class MenuItem
{
    public string $pageTitle;
    public string $menuTitle;
    public string $capability;
    public string $menuSlug;
    public $callback;
    public ?string $iconUrl;
    public ?int $position;

    public function __construct(
        string $pageTitle,
        string $menuTitle,
        string $capability,
        string $menuSlug,
        ?callable $callback = null,
        ?string $iconUrl = null,
        ?int $position = null
    ) {
        $this->pageTitle = $pageTitle;
        $this->menuTitle = $menuTitle;
        $this->capability = $capability;
        $this->menuSlug = $menuSlug;
        $this->callback = $callback;
        $this->iconUrl = $iconUrl;
        $this->position = $position;
    }

    public static function set(
        string $pageTitle,
        string $menuTitle,
        string $capability,
        string $menuSlug,
        ?callable $callback = null,
        ?string $iconUrl = null,
        ?int $position = null
    ):self {
        return new self(
            $pageTitle,
            $menuTitle,
            $capability,
            $menuSlug,
            $callback,
            $iconUrl,
            $position
        );
    }
}