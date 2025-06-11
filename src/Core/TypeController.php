<?php 

namespace SeasonalContent\Core;

use \SeasonalContent\DTO;

class TypeController {
    private static array $classes = [];

    public static function registerType(string $key, string $className): void {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Class {$className} does not exist.");
        }
        self::$classes[$key] = $className;
    }

    public static function registerTypes(DTO\Type ...$types): void {
        foreach ($types as $type) {
            if(!class_exists($type->className)){
                throw new \ErrorException("Type {$type->className} does not exist.");
            }
            self::$classes[$type->name] = $type->className;
        }
    }

    public static function getRegisteredTypes(): array {
        return self::$classes;
    }

    public static function getInstance(string $key, ...$args): object {
        if (!isset(self::$classes[$key])) {
            throw new \InvalidArgumentException("Class with key '{$key}' is not registered.");
        }
        $className = self::$classes[$key];
        return new $className(...$args);
    }

    public static function init(): void {
        do_action('SeasonalContent/registerTypes', self::class);
        self::registerBasicTypes();
        self::registerAddonsTypes();
    }

    private static function registerAddonsTypes() {
        $addons = AddonManager::getInstance()->getAddons();
        foreach ($addons as $addon) {
            self::registerTypes(
                ...$addon->getTypes()
            );
        }
    }

    private static function registerBasicTypes() {
        self::registerType('TextEditor', \SeasonalContent\Types\TextEditor::class);
        self::registerType('Title', \SeasonalContent\Types\Title::class);
    }
}
