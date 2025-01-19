<?php 

namespace SeasonalContent\Core;

use SeasonalContent\DTO;

class Plugin extends Singleton
{
    
    /**
     * hookManager
     *
     * @var HookManager
     */
    private $hookManager;
    private $addonManager;
    
    
    /**
     * Startup plugin
     *
     * @param  HookManager $hookManager
     * @return void
     */
    public function run(HookManager $hookManager = null):void {
        register_activation_hook(SECOEL_INDEX, [Install::class, 'install']);
        register_deactivation_hook(SECOEL_INDEX, [Install::class, 'deactivate']);

        $this->hookManager = ( null !== $hookManager ) ? $hookManager : HookManager::getInstance();
        $this->addonManager = AddonManager::getInstance();

        $this->hookManager->doAction(DTO\Hook::set(
            "SeasonalContent/beforeLoadAddons"
        ), $this->hookManager);
        
        $this->hookManager->registerActions(DTO\Hook::set(
            'SeasonalContent/loadAddons',
            [$this->addonManager, 'loadAddons']
        ));

        $this->hookManager->registerActions(
                                            DTO\Hook::set('plugins_loaded', [TypeController::class, 'init']),
                                            DTO\Hook::set('plugins_loaded', [$this->addonManager, 'initAddons']),
                                            DTO\Hook::set(
                                                'SeasonalContent/registerTypes',
                                                [TypeController::class, 'registerBasicTypes']
                                            ),
                                            DTO\Hook::set(
                                                Cron::HOOK,
                                                [\SeasonalContent\Components\Category\CategoryComponent::class, 'updateCurrentCategory']
                                            ),
                                            DTO\Hook::set('plugins_loaded', [$this, 'frontend']), );


        if(is_admin()){
            // $this->hookManager->registerActions(DTO\Hook::set('plugins_loaded', [$this, 'admin']), DTO\Hook::set('plugins_loaded', [TypeController::class, 'init']));
            $this->hookManager->registerActions(
                                         DTO\Hook::set('init', [$this, 'loadTranslations']),
                                                DTO\Hook::set('init', [$this, 'cronInit']),
                                                DTO\Hook::set('plugins_loaded', [$this, 'admin']));
        }

        // var_dump($this->addonManager->getAddons());
        // ( is_admin() ) ? $this->initAdmin() : $this->initUser();
    }


    public function cronInit()
    {
        $cron = Cron::getInstance();
        Cron::checkScheduleEvent();
        
    }
    

    public function admin():void {
        if(!$this->hookManager->didAction('elementor/loaded')){
            $this->hookManager->registerActions(DTO\Hook::set(SECOEL_PREFIX . 'elementor_disabled', [Drawer::class, 'adminNotice'], 10, 1));
            $this->hookManager->doAction(DTO\Hook::set(SECOEL_PREFIX . 'elementor_disabled'), __('You should install and enable Elementor before work with Seasonal Content!', 'seasonal-content') );
            return;
        }
        // add_action('all', function($hook_name) {
        //     if (strpos($hook_name, 'elementor') !== false) {
        //         error_log($hook_name);
        //     }
        // });
        // add_action('elementor/loaded', function() {
        //     error_log('Elementor loaded');
        // });

        // add_action('elementor/element/before_section_render', function($element) {
        //     error_log('Widget: ' . $element->get_name());
        //     error_log('Current Section: ' . $element->get_current_section());
        // });
        // var_dump(__('Category', 'seasonal-content'));
        // exit();
        // menu
        $menu = new \SeasonalContent\Components\Menu\AdminMenuComponent();
        $menu->addAdminMenu(
            \SeasonalContent\Components\Menu\MenuItem::set(
                "SeasonalContent",
                'Seasonal Content',
                '',
                SECOEL_PREFIX,
                null,
                null,
                59
            )
        );
        $menu->addSubAdminMenu(
            \SeasonalContent\Components\Menu\SubMenuItem::set(
                SECOEL_PREFIX,
                __('Manage Categories', 'seasonal-content'),
                __('Manage Categories', 'seasonal-content'),
                'manage_options',
                SECOEL_PREFIX.'categories',
                [(new \SeasonalContent\Templates\Categories()), 'render']
            )
        );

        $menu->addSubAdminMenu(
            \SeasonalContent\Components\Menu\SubMenuItem::set(
                SECOEL_PREFIX,
                    __('Addons', 'seasonal-content'),
                    __('Addons', 'seasonal-content'),
                    'manage_options',
                    SECOEL_PREFIX.'addons',
                    [(new \SeasonalContent\Templates\Addons()), 'render']
                )
            );

        $category_component = new \SeasonalContent\Components\Category\CategoryComponent();

        $ajax = \SeasonalContent\Core\Ajax::getInstance();
        $ajax->_setComponents($category_component);
        $ajax->_setComponents(\SeasonalContent\Components\Content\ContentComponent::getInstance());

        $this->hookManager->registerActions(
            DTO\Hook::set(
                'admin_menu',
                [$menu, 'registerMenus']
            ),
            DTO\Hook::set(
                'wp_ajax_season_handler',
                [$ajax, 'requestHandler']
            ),
            DTO\Hook::set(
                'elementor/documents/register_controls',
                [\SeasonalContent\Components\Elementor\ElementorComponent::class, 'addPageSettings']
            ),
            DTO\Hook::set(
                'elementor/editor/after_enqueue_scripts',
                function() {
                    wp_enqueue_script(
                        SECOEL_PREFIX.'elementor_action_handler',
                        plugins_url( '/assets/js/elementor-page.js', SECOEL_INDEX ),
                        [ 'jquery', 'elementor-editor' ],
                        '1.0',
                        true
                    );
                }
            )
        );
        // var_dump(TypeController::getRegisteredTypes());
        // exit();
        if(@$_GET['action'] == 'elementor'){
            $elementorComponent = \SeasonalContent\Components\Elementor\ElementorComponent::getInstance();
        // $elementorComponent->setCategoryComponent($category_component);
            $elementorComponent->setTypes(TypeController::getRegisteredTypes());
            $elementorComponent->setCategories(\SeasonalContent\Models\Category::getAllCategories());
            $elementorComponent->registerTypes();
        }

        // $menu->registerMenus();


    }

    public function frontend():void {
        // var_dump($this->addonManager->getAddons());
        $contentComponent = \SeasonalContent\Components\Content\ContentComponent::getInstance();
        $this->hookManager->registerActions(
            DTO\Hook::set(
                'template_redirect',
                [$contentComponent, 'content']
            ),
        );
    }

    public function loadTranslations() {
        $loaded = load_plugin_textdomain('seasonal-content', false, 'seasonal-content/languages');
    }

}