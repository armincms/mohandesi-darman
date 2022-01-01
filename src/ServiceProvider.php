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
        $this->app->booted(function() {
            \Event::listen(OrderCompleted::class, Listeners\OrderCompleted::class);   
        });       
    } 
}
