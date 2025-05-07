<?php 

namespace SeasonalContent\Components\Elementor;

class ElementorComponent extends \SeasonalContent\Core\Singleton implements \SeasonalContent\Components\Component
{
    private array $keyTypes;
    private array $categories;

    public function setTypes(array $instances): void {
        $this->keyTypes = $instances;
    }

    public function registerTypes():void {
        foreach ($this->keyTypes as $key => $class) {
            $instance = \SeasonalContent\Core\TypeController::getInstance($key);
            $instance->setCategories($this->categories);
            add_action($instance::HOOK, [$instance, 'registerElementorControls'], 10, 2);
        }
    }

    public function setCategories($categories):void {
        $this->categories = $categories;
    }

    public static function addPageSettings($document) {
        if ( ! $document instanceof \Elementor\Core\DocumentTypes\PageBase || ! $document::get_property( 'has_elements' ) ) {
            return;
        }

        $document->start_controls_section(
            SEASONALCONTENT_PREFIX.'seasonal_content',
            [
                'label' => esc_html__( 'Seasonal Content', 'seasonal-content' ),
                'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
            ]
        );
    
        $document->add_control(
            SEASONALCONTENT_PREFIX.'restore_main_backup',
            [
                'label' => esc_html__( 'Restore main version', 'seasonal-content' ),
                'type' => \Elementor\Controls_Manager::BUTTON,
                'text' => esc_html__("Restore", 'seasonal-content'),
                'event' => SEASONALCONTENT_PREFIX . 'restore_main_backup'
            ]
        );
        $document->add_control(
            SEASONALCONTENT_PREFIX.'set_as_main_backup',
            [
                'label' => esc_html__( 'Set as main version', 'seasonal-content' ),
                'type' => \Elementor\Controls_Manager::BUTTON,
                'text' => esc_html__("Set", 'seasonal-content'),
                'event' => SEASONALCONTENT_PREFIX . 'set_as_main_backup'
            ]
        );
        $document->add_control(
            SEASONALCONTENT_PREFIX.'update_content',
            [
                'label' => esc_html__( 'Update content', 'seasonal-content' ),
                'type' => \Elementor\Controls_Manager::BUTTON,
                'text' => esc_html__("Update", 'seasonal-content'),
                'event' => SEASONALCONTENT_PREFIX . 'update_content'
            ]
        );
    
        $document->end_controls_section();
    }

}
