<?php

namespace App\Listeners;

use App\Events\EntrerCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProductQuantity
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
    public function handle(EntrerCreated $event): void
    {
        $entrer = $event->entrer;
        $product = $entrer->product;
        $productQuantity = $product->quantity;
        if($product->category == 'kilo_ou_carton'){
            $oldBoxQuantity = $productQuantity->box;
            $productQuantity->box = $entrer->box_quantity + $oldBoxQuantity;
            $oldKgQuantity = $productQuantity->kg;
            $productQuantity->kg = $entrer->kilo_quantity + $oldKgQuantity;
        }else{
            $oldQuantity = $productQuantity->unit;
            $productQuantity->unit = $entrer->unit_quantity + $oldQuantity;
        }
        $productQuantity->save();
    }
}
