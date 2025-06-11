<?php 

namespace SeasonalContent\DTO;

class Type implements DTO
{

    public string $name;

    public string $className;

    public static function set(string $name, string $className):self {
        return new self($name, $className);
    }

    public function __construct(string $name, string $className) {
        $this->name = $name;
        $this->className = $className;
    }
    
}