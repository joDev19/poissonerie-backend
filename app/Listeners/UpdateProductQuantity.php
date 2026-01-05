<?php

namespace App\Listeners;

use App\Events\EntrerCreated;
use App\Models\ProductQuantity;

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
        // $productQuantity = $product->quantity;
        // au lieu de modifier la ligne existante, je vais créer une nouvelle ligne ssi il y n'y a jamais eu un approvisionnement de cette kilo_once_quantity qui a été fait
        $productQuantitie = null;
        if ($product->category != 'kilo_ou_carton') {
            // un produit qui n'est pas vendu au kilo ne peut pas avoir de quantité en carton
            $productQuantitie = ProductQuantity::where('product_id', '=', $product->id)->first();
        } else {
            $productQuantitie = ProductQuantity::where([['product_id', '=', $product->id], ['kilo_once_quantity', '=', $entrer->kilo_once_quantity],])->first();
        }


        if ($productQuantitie) {
            // modification du nombre de carton de tel kilo existant ....
            if ($product->category == 'kilo_ou_carton') {
                $oldBox = $productQuantitie->box;
                $productQuantitie->box = $oldBox + $entrer->box_quantity;
                $oldKilo = $productQuantitie->kg;
                $productQuantitie->kg = $oldKilo + ($entrer->box_quantity * $entrer->kilo_once_quantity);
            } else {
                $oldUnit = $productQuantitie->unit;
                $productQuantitie->unit = $oldUnit + $entrer->unit_quantity;
            }

            $productQuantitie->save();

        } else {
            // créer une nouvelle ligne
            if ($product->category == 'kilo_ou_carton') {
                ProductQuantity::create([
                    'product_id' => $product->id,
                    'kg' => $entrer->kilo_quantity,
                    'box' => $entrer->box_quantity,
                    'unit' => 0,
                    'kilo_once_quantity' => $entrer->kilo_once_quantity
                ]);
            } else {
                ProductQuantity::create([
                    'product_id' => $product->id,
                    'kg' => 0,
                    'box' => 0,
                    'unit' => $entrer->unit_quantity,
                    'kilo_once_quantity' => $entrer->kilo_once_quantity
                ]);
            }
        }

    }
}
