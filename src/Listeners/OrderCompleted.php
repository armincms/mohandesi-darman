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
                    $details = collect($orderItem->details)->map->data->each(function($data) use ($order) { 
                        $credit = collect($data)->filter(function($value, $key) {
                            return ! filter_var($value, FILTER_VALIDATE_URL);
                        }); 
                      
                        $message = "کاربر گرامی {$order->customer->fullname()} عزیز\r\nضمن تشکر از خرید شما\r\nلایسنس جهت فعالسازی نرم افزار  {$credit->implode(',')} می باشد.\r\nبا احترام دکتر سید علی حسینی";
                        
                        app('qasedak')->send($message, $order->customer->mobile);
                    });
                  
                    $details->isNotEmpty() || app('qasedak')->send(
                      'مشتری گرامی سفارش شما ثبت و دریافت شد. متاسفانه موجودی لایسنس به پایالن رسیده است. برای پیگیری با پشتیبانی تماس حاصل فرمایید.' . 
                      "\r\n" . 
                      "شماره سفارش: #{$order->trackingCode()}\r\n", 
                      $order->customer->mobile
                    );
                }
            });
        }
    }
}
