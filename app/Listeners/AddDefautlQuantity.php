<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use App\Services\ProductQuantityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddDefautlQuantity
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
    public function handle(ProductCreated $event, ProductQuantityService $productQuantityService = new ProductQuantityService()): void
    {
        $productQuantityService->store([
            'product_id' => $event->product->id,
            'kg' => 0,
            'box' => 0
        ]);
    }
}
