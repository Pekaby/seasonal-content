<?php 

namespace SeasonalContent\Types;

class ColumnBackground implements Type
{
    const HOOK = 'elementor/element/column/section_style/before_section_start';
    const CONTROL_NAME = '_category_column_background';
    const SETTINGS_KEY = 'column_background_image';

    private string $elementorHook = 'elementor/element/column/section_style/before_section_start';

    private array $categories;

    public function setCategories($categories):void {
        $this->categories = $categories;
    }

    public function registerElementorControls($element, $section_id):void
    {
        if(empty($this->categories)) return;
        $element->start_controls_section(
            SEASONALCONTENT_PREFIX.'background',
            [
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'label' => esc_html( 'Seasonal Content' ),
            ]
        );
        foreach ($this->categories as $category) {
            $element->add_control(
                SEASONALCONTENT_PREFIX . $category->id . self::CONTROL_NAME,
                [
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'label' => esc_html( $category->title ),
                ]
            );
        }
        $element->end_controls_section();
    }


    public function changeElement($element, \SeasonalContent\Models\Category $category):object {
        $property = SEASONALCONTENT_PREFIX . $category->id . self::CONTROL_NAME;
        if( property_exists($element->settings, $property) && !empty(@$element->settings->$property) ) {
            $element->settings->background_image = $element->settings->$property;
        }
        return $element;
    }


    public function getHook() : string {
        return $this->elementorHook;
    }

}