<?php 

namespace SeasonalContent\DTO;

class CronAction implements DTO
{

    public string $hookName;

    public int $priority;
    public int $acceptedArgs;

    public $callback;

    public static function set($hookName, $callback = [], $priority = 10, $acceptedArgs = 1):self {
        return new self($hookName, $callback, $priority = 10, $acceptedArgs = 1);
    }

    public function __construct($hookName, $callback, $priority = 10, $acceptedArgs = 1) {
        $this->hookName = $hookName;
        $this->callback = $callback;
        $this->priority = $priority;
        $this->acceptedArgs = $acceptedArgs;
    }
}