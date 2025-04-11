<?php

namespace App\Listeners;

use App\Events\VenteCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProductQuantityAfterSelling
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
    public function handle(VenteCreated $event): void
    {
        $vente = $event->vente;
        $productQuantity = $vente->product->quantity;
        $oldBoxQuantity = $productQuantity->box;
        $oldkgQuantity = $productQuantity->kg;
        if($vente->type=='gros'){
            $productQuantity->box = $oldBoxQuantity - $vente->quantity;
            $productQuantity->kg = 20 * $productQuantity->box;
        }else{
            $productQuantity->kg = $oldkgQuantity - $vente->quantity;
            $productQuantity->box = $productQuantity->kg / 20;
        }
        $productQuantity->save();
    }
}
