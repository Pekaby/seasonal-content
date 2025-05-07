<?php 

namespace SeasonalContent\Core;

abstract class Singleton
{
    /**
     * Instance of parent class
     * @var 
     */
    private static array $instances = [];


    public static function getInstance():self {
        $class = static::class;

        if ( !isset(self::$instances[$class]) ) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }

    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}



}