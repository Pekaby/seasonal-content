<?php 

namespace SeasonalContent\Components\Content;

class ContentChanger
{

    private static array $types;
    private static array $elementChangeInfo;

    public static function setTypes(array $types) {
        self::$types = $types;
    }
    public static function change($elementorContent, $category) {
        // var_dump($elementorContent);
        foreach (@$elementorContent as &$element) {
            if(gettype($element->settings) === 'object'){
                foreach (self::$types as $type => $class) {
                    $type_instance = \SeasonalContent\Core\TypeController::getInstance($type);
                    $element = $type_instance->changeElement($element, $category);
                }
            }
            // $element->settings = self::escape($element->settings);

            if(isset($element->elements) && !empty($element->elements)){
                $element->elements = self::change($element->elements, $category);
            }
        }
        return $elementorContent;
    }

    public static function changeSettings($elementorContent, $backupContent) {
        foreach($elementorContent as $contentKey => &$contentData) {
            if(isset($backupContent[$contentKey]) && array_key_exists( 'settings', $contentData ) && array_key_exists( 'settings', $backupContent[$contentKey] )) {
                
                foreach ($contentData['settings'] as $param => $value) {
                    if(strncmp($param, SEASONALCONTENT_PREFIX, strlen(SEASONALCONTENT_PREFIX)) === 0) {
                        $backupContent[$contentKey]['settings'][$param] = $value;
                    }
                }

            }

            if( ( is_array($contentData['elements']) && !empty($contentData['elements']) )
                && ( is_array($backupContent[$contentKey]['elements']) && !empty($backupContent[$contentKey]['elements']) )) {
                    $backupContent[$contentKey]['elements'] = self::changeSettings($contentData['elements'], $backupContent[$contentKey]['elements']);
            }
        }

        return $backupContent;
    }

    public static function escape($object) {
        if( empty($object) ) return $object;

        foreach ($object as $key => &$value) {
            if(is_string($value)) {
                if (preg_match('/<[^>]*>/', $value)) {
                    $value = str_replace('"', "'", $value);
                    $value = str_replace("\n", "\\n", $value);
                    continue;
                }
                if (self::isJson($value)) {
                    $value = preg_replace('/([\"\\\\])/', '\\\\$1', $value);
                    // $decoded = json_decode($value, true);
                    // $value = json_encode(self::escape($decoded), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    continue;
                }
                $value = preg_replace('/([\"\\\\])/', '\\\\$1', $value);
                continue;
            }
            if(is_array($value) || is_object($value)) {
                $value = self::escape($value);
            }
        }

        return $object;

    }

    private static function isJson($string) {
        if (!is_string($string) && empty($string)) {
            return false;
        }
        
        $string = trim($string);
        if (!in_array(@$string[0], ['{', '[']) || !in_array(@$string[strlen($string) - 1], ['}', ']'])) {
            return false;
        }
        
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }

    public static function fixContent($content) {
        if( empty($content) ) return $content;

        foreach ($content as $key => &$value) {
            if(is_string($value)) {
                if (preg_match('/<[^>]*>/', $value)) {
                    $value = str_replace('"', "'", $value);
                    $value = str_replace("\n", "\\n", $value);
                    continue;
                }
                if (self::isJson($value)) {
                    $value = preg_replace('/([\"\\\\])/', '\\\\$1', $value);
                    // $decoded = json_decode($value, true);
                    // $value = json_encode(self::escape($decoded), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    continue;
                }
                $value = preg_replace('/([\"\\\\])/', '\\\\$1', $value);
                continue;
            }
            if(is_array($content->$key) || is_object($content->$key)) {
                $value = self::fixContent($content->$key);
            }
        }
        return $content;
    }

}