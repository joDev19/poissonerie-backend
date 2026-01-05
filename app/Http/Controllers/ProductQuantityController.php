<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBoxPrice;
use App\Models\ProductQuantity;
use App\Services\ProductQuantityService;
use Illuminate\Http\Request;

class ProductQuantityController extends Controller
{
    public function __construct(private ProductQuantityService $productQuantityService){

    }

    public function setBoxPrice(int $productId, UpdateBoxPrice $request){
        return $this->productQuantityService->setBoxPrice($productId, $request->validated());
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductQuantity $productQuantity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductQuantity $productQuantity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductQuantity $productQuantity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductQuantity $productQuantity)
    {
        //
    }
}
