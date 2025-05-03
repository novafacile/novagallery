<?php
/**
 * Simple Event Dispatcher
 * based on https://github.com/brick/event
 * @license  MIT
 */
namespace novafacile;
class SimpleEventDispatcher {

    /**
     * The event listeners, indexed by type and priority.
     * @var array
     */
    protected $listeners = [];

    /**
     * A cache of the sorted event listeners, indexed by type.
     * @var array
     */
    protected $sorted = [];

    /**
     * Adds an event listener.
     *
     * If the listener is already registered for this type, it will be registered again:
     * several instances of a same listener can be registered for a single type.
     *
     * Every listener can stop event propagation by returning `false`.
     *
     * @param string   $event    The event name.
     * @param callable $listener The event listener.
     * @param int      $priority The higher the priority, the earlier the listener will be called in the chain.
     *
     * @return void
     */
    public function addListener(string $event, callable $listener, int $priority = 0) : void {
        $this->listeners[$event][$priority][] = $listener;
        unset($this->sorted[$event]);
    }

    /**
     * Removes an event listener.
     *
     * If the listener is not registered for this type, this method does nothing.
     * If the listener has been registered several times for this type, all instances are removed.
     *
     * @param string   $event    The event name.
     * @param callable $listener The event listener.
     *
     * @return void
     */
    public function removeListener(string $event, callable $listener) : void {
        if (isset($this->listeners[$event])) {
            foreach ($this->listeners[$event] as $priority => $listeners) {
                foreach ($this->listeners[$event][$priority] as $key => $instance) {
                    if ($instance === $listener) {
                        unset($this->listeners[$event][$priority][$key]);
                        unset($this->sorted[$event]);

                        if (empty($this->listeners[$event][$priority])) {
                            unset($this->listeners[$event][$priority]);

                            if (empty($this->listeners[$event])) {
                                unset($this->listeners[$event]);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Returns all registered listeners for the given type.
     *
     * Listeners are returned in the order they will be called.
     *
     * @param string $event The event name.
     *
     * @return callable[]
     */
    public function getListeners(string $event) : array {
        if (empty($this->listeners[$event])) {
            return [];
        }

        if (! isset($this->sorted[$event])) {
            $this->sorted[$event] = $this->sortListeners($this->listeners[$event]);
        }

        return $this->sorted[$event];
    }

    /**
     * Returns all registered listeners indexed by type.
     *
     * Listeners are returned in the order they will be called for each type.
     *
     * @return callable[][]
     */
    public function getAllListeners() : array {
        foreach ($this->listeners as $event => $listeners) {
            if (! isset($this->sorted[$event])) {
                $this->sorted[$event] = $this->sortListeners($listeners);
            }
        }

        return $this->sorted;
    }

    /**
     * Dispatches an event to the registered listeners.
     *
     * The highest priority listeners will be called first.
     * If several listeners have the same priority, they will be called in the order they have been registered.
     *
     * @param string $event         The event name.
     * @param mixed  ...$parameters The parameters to pass to the listeners.
     *
     * @return void
     */
    public function dispatch(string $event, &...$parameters) : void {
        foreach ($this->getListeners($event) as $listener) {
            if ($listener(...$parameters) === false) {
                break;
            }
        }
    }

    /**
     * @param array $listenersByPriority
     *
     * @return array
     */
    private function sortListeners(array $listenersByPriority) : array {
        krsort($listenersByPriority);

        return array_merge(...$listenersByPriority);
    }

}