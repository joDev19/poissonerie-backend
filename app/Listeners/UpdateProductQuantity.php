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
        $productQuantity = $entrer->product->quantity;
        $oldBoxQuantity = $productQuantity->box;
        $productQuantity->box = $entrer->box_quantity + $oldBoxQuantity;
        $oldKgQuantity = $productQuantity->kg;
        $productQuantity->kg = $entrer->kilo_quantity + $oldKgQuantity;
        $productQuantity->save();
    }
}
