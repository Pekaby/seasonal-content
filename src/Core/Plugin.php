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
        register_activation_hook(SECOEL_INDEX, [Install::class, 'install']);
        register_deactivation_hook(SECOEL_INDEX, [Install::class, 'deactivate']);
        register_uninstall_hook( SECOEL_INDEX, [Install::class, 'uninstall']);

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
            $this->hookManager->registerActions(
                                         DTO\Hook::set('init', [$this, 'loadTranslations']),
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
        if( $screen && $screen->id === 'seasonal-content_page_' . SECOEL_PREFIX . 'categories' ) {
            wp_enqueue_style(SECOEL_PREFIX . 'categories', plugin_dir_url(SECOEL_DIR) . 'seasonal-content/assets/css/admin-categories.css', [], '2.1');
        }
        if( $screen && $screen->id === 'seasonal-content_page_' . SECOEL_PREFIX . 'addons') {
            wp_enqueue_style(SECOEL_PREFIX . 'categories', plugin_dir_url(SECOEL_DIR) . 'seasonal-content/assets/css/admin-addons.css', [], '2.1');
        }
    }
    
    /**
     * Enqueue scripts and define js variables
     *
     * @param  string $hook
     * @return void
     */
    public function enqueueScrips($hook): void {
        if($hook !== 'seasonal-content_page_secoel_categories') {
            return;
        }
        wp_enqueue_script( SECOEL_PREFIX . 'categories', plugin_dir_url(SECOEL_DIR ) . 'seasonal-content/assets/js/admin-categories.js', [], '2.1', ['strategy' => 'defer']);
        wp_localize_script(SECOEL_PREFIX . 'categories', SECOEL_PREFIX . 'security', [
            'nonce' => wp_create_nonce(SECOEL_PREFIX . 'security'),
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
        if(!$this->hookManager->didAction('elementor/loaded')){
            $this->hookManager->registerActions(DTO\Hook::set(SECOEL_PREFIX . 'elementor_disabled', [Drawer::class, 'adminNotice'], 10, 1));
            $this->hookManager->doAction(DTO\Hook::set(SECOEL_PREFIX . 'elementor_disabled'), __('You should install and enable Elementor before work with Seasonal Content!', 'seasonal-content') );
            return;
        }

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
                    wp_localize_script(SECOEL_PREFIX . 'elementor_action_handler', SECOEL_PREFIX . 'security', [
                        'nonce' => wp_create_nonce(SECOEL_PREFIX . 'security'),
                    ]);
                }
            ),
            DTO\Hook::set(
                'elementor/editor/after_save',
                [\SeasonalContent\Components\Content\ContentComponent::class, 'saveData']
            )
        );

        if(isset($_GET['action']) && @$_GET['action'] == 'elementor'){
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
    
    /**
     * loadTranslations
     *
     * @return void
     */
    public function loadTranslations():void {
        $loaded = load_plugin_textdomain('seasonal-content', false, 'seasonal-content/languages');
    }

}