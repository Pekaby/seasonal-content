<?php 

namespace SeasonalContent\Types;

class Slides implements Type
{
    // const HOOK = 'elementor/element/before_section_start';
    const HOOK = 'elementor/element/slides/section_slides/before_section_start';
    const CONTROL_NAME = '_category_carusel';
    const SETTINGS_KEY = 'carusel';

    private string $elementorHook = 'elementor/element/slides/section_slides/before_section_start';

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
            $repeater = new \Elementor\Repeater();

            $repeater->add_control(
                'heading',
                [
                    'label' => __('Title', 'seasonal-content'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Slide Title', 'seasonal-content'),
                ]
            );
            
            $repeater->add_control(
                'background_color',
                [
                    'label' => __('Background Color', 'seasonal-content'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#fff',
                ]
            );
            $repeater->add_control(
                'background_image',
                [
                    'label' => __('Image', 'seasonal-content'),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                ]
            );
            $repeater->add_control(
                'button_text',
                [
                    'label' => __('Button Text', 'seasonal-content'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __('Click Here', 'seasonal-content'),
                ]
            );
            $repeater->add_control(
                'description',
                [
                    'name' => 'description',
                    'label' => __('Description', 'seasonal-content'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'default' => __('Description', 'seasonal-content'),
                ]
            );

            $element->add_control(
                SECOEL_PREFIX . $category->slug . self::CONTROL_NAME,
                [
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'label' => esc_html__( $category->title, 'seasonal-content' ),
                    'fields' => $repeater->get_controls(),
                ]
            );
        }
        $element->end_controls_section();

    }


    public function changeElement($element, \SeasonalContent\Models\Category $category):object {
        $property = SECOEL_PREFIX . $category->slug . self::CONTROL_NAME;
        if(property_exists($element->settings, $property) && !empty(@$element->settings->$property) ) {
            $element->settings->slides = $element->settings->$property;
        }
        return $element;
    }


    public function getHook() : string {
        return $this->elementorHook;
    }

}