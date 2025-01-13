<?php 

namespace SeasonalContent\Core;

interface Addon
{
    public function getSlug():string;
    public function getTitle():string;
}