<?php 

namespace SeasonalContent\Types;

interface Type
{
    public function getHook():string;
    public function registerElementorControls($element, $section_id):void;
    public function setCategories($categories):void;

    public function changeElement($element, \SeasonalContent\Models\Category $category):object;
}