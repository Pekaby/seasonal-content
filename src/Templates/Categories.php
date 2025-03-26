<?php 

namespace SeasonalContent\Templates;

class Categories implements Template
{

    private $path = SEASONALCONTENT_DIR . 'assets/view/categories_managment';


    public function render($data = []) : void {
        if (!is_array($data)) {
            $data = [];
        }
        \SeasonalContent\Core\Drawer::loadDocument($this->path, ['categories' => \SeasonalContent\Components\Category\CategoryComponent::getCategories()]);
    }
}