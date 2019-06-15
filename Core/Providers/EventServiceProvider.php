<?php

namespace Core\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Core\Events\RepositoryEntityCreated' => [
            'Core\Listeners\CleanCacheRepository',
        ],
        'Repository\Events\RepositoryEntityUpdated' => [
            'Repository\Listeners\CleanCacheRepository',
        ],
        'Repository\Events\RepositoryEntityDeleted' => [
            'Repository\Listeners\CleanCacheRepository',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $events = app('events');

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        //
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens()
    {
        return $this->listen;
    }
}
