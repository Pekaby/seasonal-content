<?php 

namespace SeasonalContent\Types;

class Carusel implements Type
{
    // const HOOK = 'elementor/element/before_section_start';
    const HOOK = 'elementor/element/image-carousel/section_image_carousel/before_section_start';
    const CONTROL_NAME = '_category_carusel';
    const SETTINGS_KEY = 'carusel';

    private string $elementorHook = 'elementor/element/image-carousel/section_image_carousel/before_section_start';

    private array $categories;

    public function setCategories($categories):void {
        $this->categories = $categories;
    }

    public function registerElementorControls($element, $section_id):void
    {
        if(empty($this->categories)) return;

            $element->start_controls_section(
                SECOEL_PREFIX . SELF::SETTINGS_KEY,
                [
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'label' => esc_html__( 'Seasonal Content', 'seasonal-content' ),
                ]
                );
                foreach ($this->categories as $category) {
                    $element->add_control(
                        SECOEL_PREFIX . $category->slug . self::CONTROL_NAME,
                        [
                            'type' => \Elementor\Controls_Manager::GALLERY,
                            'label' => esc_html__( $category->title, 'seasonal-content' ),
                        ]
                    );
                }
                $element->end_controls_section();
    }


    public function changeElement($element, \SeasonalContent\Models\Category $category):object {
        $property = SECOEL_PREFIX . $category->slug . self::CONTROL_NAME;
        if( property_exists($element->settings, $property) && !empty(@$element->settings->$property) ) {
            $element->settings->carousel = $element->settings->$property;
        }
        return $element;
    }


    public function getHook() : string {
        return $this->elementorHook;
    }

}