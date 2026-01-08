<?php

namespace App\Listeners;

use App\Events\SelledProductCreated;
use App\Models\Product;
use App\Models\ProductQuantity;
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
    private function findQuantitybykiloPerBox($productId, $quantity_per_box)
    {
        return ProductQuantity::where('product_id', $productId)
            ->where('kilo_once_quantity', $quantity_per_box)
            ->first();
    }
    private function updateQuantityForBoxWhenSellEnGros($selledQuantity, ProductQuantity $pq)
    {
        $newQuantityInBox = $pq->box - $selledQuantity;
        if($newQuantityInBox < 0){
            // cas ou on a vendu plus de box que disponible
            // on laisse le stock null
        }else{
            $pq->box = $newQuantityInBox;
            $pq->kg -= $selledQuantity * $pq->kilo_once_quantity;
            $pq->save();
        }


    }
    private function updateQuantityForBoxWhenSellEnDetail($selledQuantity, ProductQuantity $pq)
    {
        $oldQuanity = $pq->kg;
        $pq->kg = $oldQuanity - $selledQuantity;
        $pq->box -=  ($oldQuanity - $selledQuantity)  / $pq->kilo_once_quantity;
        $pq->save();
    }

    /**
     * Handle the event.
     */
    public function handle(SelledProductCreated $event): void
    {
        $selledProduct = $event->selledProduct;
        $product = $selledProduct->product;

        if ($product->category == 'kilo_ou_carton') {
            $pq = $this->findQuantitybykiloPerBox($product->id, $selledProduct->quantity_per_box);
            if ($pq != null) {
                if ($selledProduct->type == 'gros') {
                    $this->updateQuantityForBoxWhenSellEnGros($selledProduct->quantity, $pq);
                } else {
                    $this->updateQuantityForBoxWhenSellEnDetail($selledProduct->quantity, $pq);

                }
            }
        } else {
            //cas des produits a l unite
            $pq = $product->quantitys;
            $pq->unit -= $selledProduct->quantity;
            $pq->save();
        }
    }
}
