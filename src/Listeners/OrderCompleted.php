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
                if($orderItem->saleable->delivery === 'card') {
                    collect($orderItem->details)->map->data->each(function($data) use ($order) { 
                        $credit = collect($data)->map(function($value, $key) {
                            return "{$key}: {$value}";
                        });

                        app('qasedak')->send($credit->implode("\r\n"), $order->customer->mobile);
                    });
                }
            });
        }
    }
}
