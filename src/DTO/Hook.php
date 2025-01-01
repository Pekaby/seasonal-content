<?php 

namespace SeasonalContent\DTO;

class Hook 
{

    public string $name;

    public int $priority;
    public int $acceptedArgs;

    public $callback;

    public static function set($name, $callback = [], $priority = 10, $acceptedArgs = 1):self {
        return new self($name, $callback, $priority = 10, $acceptedArgs = 1);
    }

    public function __construct($name, $callback, $priority = 10, $acceptedArgs = 1) {
        $this->name = $name;
        $this->callback = $callback;
        $this->priority = $priority;
        $this->acceptedArgs = $acceptedArgs;
    }
}