<?php 

namespace SeasonalContent\Core;


class HookManager extends Singleton
{
    
    /**
     * WordPress did_action with DTO supporting
     *
     * @param  string | DTO\Hook | DTO\Filter $action
     * @return bool
     */
    public function didAction($action):bool {
        if(is_string($action)){
            return did_action($action);
        } elseif ($action instanceof \SeasonalContent\DTO\Hook && $action instanceof \SeasonalContent\DTO\Filter) {
            return did_action($action->name);
        }else {
            throw new \InvalidArgumentException("Paramenr should be a string or DTO\\Hook or DTO\\Filter.");
        }
    }
    
    /**
     * WordPress do_action with DTO support
     *
     * @param  string | DTO\Hook | DTO\Filter $action
     * @param  mixed $args
     * @return void
     */
    public function doAction($action, ...$args):void {
        if(is_string($action)){
            do_action($action, ...$args);
            return;
        } elseif ($action instanceof \SeasonalContent\DTO\Hook || $action instanceof \SeasonalContent\DTO\Filter) {
            do_action($action->name, ...$args);
            return;
        }else {
            throw new \InvalidArgumentException("Paramenr should be a string or DTO\\Hook or DTO\\Filter.");
        }
    }
    
    /**
     * Multiply registering actions
     *
     * @param  \DTO\Hook | \DTO\Filter $hooks
     * @return void
     */
    public function registerActions(...$hooks):void {
        foreach ($hooks as $hook) {
            if(!$hook instanceof \SeasonalContent\DTO\Hook && !$hook instanceof \SeasonalContent\DTO\Filter) {
                throw new \InvalidArgumentException("Hook should be DTO\\Hook or DTO\\Filter.");
            }
            if($hook instanceof \SeasonalContent\DTO\Hook) $this->registerHook($hook->name, $hook->callback, $hook->priority, $hook->acceptedArgs);
            if($hook instanceof \SeasonalContent\DTO\Filter) $this->registerFilter($hook->name, $hook->callback, $hook->priority, $hook->acceptedArgs);
        }
    }
    
    /**
     * Registering action with WordPress add_action
     *
     * @param  string $hook
     * @param  callable $callback
     * @param  int $priority
     * @param  int $acceptedArgs
     * @return void
     */
    private function registerHook(string $hook, $callback, int $priority = 10, int $acceptedArgs = 1):void {
        add_action($hook, $callback, $priority, $acceptedArgs);
    }
    
    /**
     * Registering Filter with WordPress add_filter
     *
     * @param  string $filter
     * @param  callable $callback
     * @param  int $priority
     * @param  int $acceptedArgs
     * @return void
     */
    private function registerFilter(string $filter, $callback, int $priority = 10, int $acceptedArgs = 1):void {
        add_filter($filter, $callback, $priority, $acceptedArgs);
    }
}