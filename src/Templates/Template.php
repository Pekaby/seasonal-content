<?php 

namespace SeasonalContent\Templates;

interface Template 
{
    public function render(array $data = []): void;
}