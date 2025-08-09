<?php

namespace App\Listeners;

use App\Events\SelledProductCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateQuantityAfterSell
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SelledProductCreated $event): void
    {
        $selledProduct = $event->selledProduct;
        $product = $selledProduct->product;
        if ($product->category == 'kilo_ou_carton') {
            if ($selledProduct->type == 'gros') {
                //$product->quantity->box -= $selledProduct->quantity;
                $product->quantity->kg -= $selledProduct->quantity*$selledProduct->quantity_per_box;

            } else {
                $product->quantity->kg -= $selledProduct->quantity;
                //$product->quantity->box -= $selledProduct->quantity / 20;

            }
        } else {
            $product->quantity->unit -= $selledProduct->quantity;
        }
        $product->quantity->save();
    }
}
