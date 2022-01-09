<?php

namespace Armincms\MDarman;
 
use Illuminate\Support\ServiceProvider as LaravelServiceProvider; 
use Armincms\Orderable\Events\OrderCompleted;

class ServiceProvider extends LaravelServiceProvider 
{ 

    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot()
    {  
        $this->app->booted(function($app) {
            \Event::listen(OrderCompleted::class, Listeners\OrderCompleted::class); 

            $component = $app['site']->get('easy-license')->components()->first(function($component) {
                return $component->name() == 'checkout';
            });

            $component->config([
                'layout' => 'phoenix'
            ]); 
          
          
            $component = $app['site']->get('easy-license')->components()->first(function($component) {
                return $component->name() == 'credit';
            });

            $component->config([
                'layout' => 'mdarman-license-order'
            ]);
        });    
    } 
}
