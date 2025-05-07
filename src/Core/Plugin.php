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

    /**
     * addonManager
     *
     * @var AddonManager
     */
    private $addonManager;
    
    
    /**
     * Startup plugin
     *
     * @param  HookManager $hookManager
     * @return void
     */
    public function run(HookManager $hookManager = null):void {
        register_activation_hook(SEASONALCONTENT_INDEX, [Install::class, 'install']);
        register_deactivation_hook(SEASONALCONTENT_INDEX, [Install::class, 'deactivate']);
        register_uninstall_hook( SEASONALCONTENT_INDEX, [Install::class, 'uninstall']);

        $this->hookManager = ( null !== $hookManager ) ? $hookManager : HookManager::getInstance();
        $this->addonManager = AddonManager::getInstance();

        if(!$this->hookManager->didAction('elementor/loaded')){
            $this->hookManager->registerActions(DTO\Hook::set('admin_notices', [\SeasonalContent\Support\Notices::class, 'ElementorNotActivated']));
            return;
        }

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
            $this->hookManager->registerActions(
                                         // DTO\Hook::set('init', [$this, 'loadTranslations']),
                                                DTO\Hook::set('init', [$this, 'cronInit']),
                                                DTO\Hook::set('plugins_loaded', [$this, 'admin']),
                                                DTO\Hook::set('admin_enqueue_scripts', [$this, 'enqueueScrips']),
                                                DTO\Hook::set('admin_enqueue_scripts', [$this, 'enqueueStyles'])
                                                );
        }
    }
    
    /**
     * Enqueue Styles for specific pages
     *
     * @return void
     */
    public function enqueueStyles(): void {
        $screen = get_current_screen();
        if( $screen && $screen->id === 'seasonal-content_page_' . SEASONALCONTENT_PREFIX . 'categories' ) {
            wp_enqueue_style(SEASONALCONTENT_PREFIX . 'categories', plugin_dir_url(SEASONALCONTENT_DIR) . 'seasonal-content/assets/css/admin-categories.css', [], '1.0.0');
        }
        if( $screen && $screen->id === 'seasonal-content_page_' . SEASONALCONTENT_PREFIX . 'addons') {
            wp_enqueue_style(SEASONALCONTENT_PREFIX . 'categories', plugin_dir_url(SEASONALCONTENT_DIR) . 'seasonal-content/assets/css/admin-addons.css', [], '1.0.0');
        }
    }
    
    /**
     * Enqueue scripts and define js variables
     *
     * @param  string $hook
     * @return void
     */
    public function enqueueScrips($hook): void {
        if($hook !== 'seasonal-content_page_seasonalcontent_categories') {
            return;
        }
        wp_enqueue_script( SEASONALCONTENT_PREFIX . 'categories', plugin_dir_url(SEASONALCONTENT_DIR ) . 'seasonal-content/assets/js/admin-categories.js', [], '1.0.0', ['strategy' => 'defer']);
        wp_localize_script(SEASONALCONTENT_PREFIX . 'categories', SEASONALCONTENT_PREFIX . 'security', [
            'nonce' => wp_create_nonce(SEASONALCONTENT_PREFIX . 'security'),
            'translation' => [
                'title' => esc_html__('Title', 'seasonal-content'),
            ]
        ]);
    }
    
    /**
     * Init Cron
     *
     * @return void
     */
    public function cronInit(): void
    {
        $cron = Cron::getInstance();
        Cron::checkScheduleEvent();
        
    }
    
    
    /**
     * Method for executing in WordPress admin
     *
     * @return void
     */
    public function admin():void {
        // if(!$this->hookManager->didAction('elementor/loaded')){
        //     $this->hookManager->registerActions(DTO\Hook::set('admin_notices', [\SeasonalContent\Support\Notices::class, 'ElementorNotActivated']));
        //     return;
        // }

        // menu
        $menu = new \SeasonalContent\Components\Menu\AdminMenuComponent();
        $menu->addAdminMenu(
            \SeasonalContent\Components\Menu\MenuItem::set(
                "SeasonalContent",
                'Seasonal Content',
                '',
                SEASONALCONTENT_PREFIX,
                null,
                null,
                59
            )
        );
        $menu->addSubAdminMenu(
            \SeasonalContent\Components\Menu\SubMenuItem::set(
                SEASONALCONTENT_PREFIX,
                __('Manage Categories', 'seasonal-content'),
                __('Manage Categories', 'seasonal-content'),
                'manage_options',
                SEASONALCONTENT_PREFIX.'categories',
                [(new \SeasonalContent\Templates\Categories()), 'render']
            )
        );

        $menu->addSubAdminMenu(
            \SeasonalContent\Components\Menu\SubMenuItem::set(
                SEASONALCONTENT_PREFIX,
                    __('Addons', 'seasonal-content'),
                    __('Addons', 'seasonal-content'),
                    'manage_options',
                    SEASONALCONTENT_PREFIX.'addons',
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
                        SEASONALCONTENT_PREFIX.'elementor_action_handler',
                        plugins_url( '/assets/js/elementor-page.js', SEASONALCONTENT_INDEX ),
                        [ 'jquery', 'elementor-editor' ],
                        '1.0',
                        true
                    );
                    wp_localize_script(SEASONALCONTENT_PREFIX . 'elementor_action_handler', SEASONALCONTENT_PREFIX . 'security', [
                        'nonce' => wp_create_nonce(SEASONALCONTENT_PREFIX . 'security'),
                    ]);
                }
            ),
            DTO\Hook::set(
                'elementor/editor/after_save',
                [\SeasonalContent\Components\Content\ContentComponent::class, 'saveData']
            )
        );

        if(isset($_GET['action']) && sanitize_text_field(wp_unslash($_GET['action'])) == 'elementor'){
            $elementorComponent = \SeasonalContent\Components\Elementor\ElementorComponent::getInstance();
            $elementorComponent->setTypes(TypeController::getRegisteredTypes());
            $elementorComponent->setCategories(\SeasonalContent\Models\Category::getAllCategories());
            $elementorComponent->registerTypes();
        }

    }
    
    /**
     * Execute in Frontend side of site
     *
     * @return void
     */
    public function frontend():void {
        $contentComponent = \SeasonalContent\Components\Content\ContentComponent::getInstance();
        $this->hookManager->registerActions(
            DTO\Hook::set(
                'template_redirect',
                [$contentComponent, 'content']
            ),
        );
    }

}