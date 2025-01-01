<?php 

namespace SeasonalContent\Components\Elementor;

class ElementorComponent extends \SeasonalContent\Core\Singleton implements \SeasonalContent\Components\Component
{
    private array $keyTypes;
    private array $categories;

    public function setTypes(array $instances): void {
        // foreach ($instances as $instance) {
        //     if(!$instance instanceof \SeasonalContent\Types\Type) {
        //         throw new \InvalidArgumentException("Instance of Elementor Type should be \\Types\\Type, " . gettype($instance) . " given" );
        //     }
        // }
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
            SECOEL_PREFIX.'seasonal_content',
            [
                'label' => esc_html__( 'Сезонный контент', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
            ]
        );
    
        $document->add_control(
            SECOEL_PREFIX.'restore_main_backup',
            [
                'label' => esc_html__( 'Восстановить последнюю прежнюю версию', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::BUTTON,
                'text' => "Восстановить",
                'event' => SECOEL_PREFIX . 'restore_main_backup'
            ]
        );
        $document->add_control(
            SECOEL_PREFIX.'set_as_main_backup',
            [
                'label' => esc_html__( 'Установить как основу', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::BUTTON,
                'text' => "Установить",
                'event' => SECOEL_PREFIX . 'set_as_main_backup'
            ]
        );
        $document->add_control(
            SECOEL_PREFIX.'update_content',
            [
                'label' => esc_html__( 'Обновить контент', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::BUTTON,
                'text' => "Обновить",
                'event' => SECOEL_PREFIX . 'update_content'
            ]
        );
        // $document->add_control(
        //     SECOEL_PREFIX.'update_current_category',
        //     [
        //         'label' => esc_html__( 'Обновить текущую категорию', 'textdomain' ),
        //         'type' => \Elementor\Controls_Manager::BUTTON,
        //         'text' => "Обновить",
        //         'event' => SECOEL_PREFIX . 'update_current_category'
        //     ]
        // );

    
        $document->end_controls_section();
    }

}
