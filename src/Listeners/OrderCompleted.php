<?php

namespace Armincms\MDarman\Listeners;

class OrderCompleted
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    { 
        if($event->order->orderable_type == \Armincms\EasyLicense\License::class) {
            $order = $event->order->loadMissing('saleables.saleable', 'customer');

            $order->saleables->each(function($orderItem) use ($order) {
                app('qasedak')->send('hello', $order->customer->mobile);
            }); 
        }
    }
}
