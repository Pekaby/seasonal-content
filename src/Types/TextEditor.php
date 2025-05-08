<?php 

namespace SeasonalContent\Types;

class TextEditor implements Type 
{
    const HOOK = 'elementor/element/text-editor/section_editor/before_section_start';

    const CONTROL_NAME = '_category_text_editor';

    private string $elementorHook = 'elementor/element/text-editor/section_editor/before_section_start';
    private array $categories;
    
    public function setCategories($categories):void {
        $this->categories = $categories;
    }

    public function getHook():string {
        return $this->elementorHook;
    }

    public function registerElementorControls($element, $args):void {
        if(empty($this->categories)) return;

        $element->start_controls_section(
            SEASONALCONTENT_PREFIX.'text_editor',
            [
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'label' => esc_html( 'Seasonal Content' ),
            ]
        );
        foreach ($this->categories as $category) {
            $element->add_control(
                SEASONALCONTENT_PREFIX. $category->slug . self::CONTROL_NAME,
                [
                    'type' => \Elementor\Controls_Manager::WYSIWYG,
                    'label' => esc_html( $category->title ),
                ]
            );
        }
            
        $element->end_controls_section();
    }

    public function changeElement($element, \SeasonalContent\Models\Category $category):object {
        $property = SEASONALCONTENT_PREFIX . $category->slug . self::CONTROL_NAME;
        if( property_exists($element->settings, $property) && !empty(@$element->settings->$property) ) {
            $element->settings->editor = $element->settings->$property;
        }
        return $element;
    }
}