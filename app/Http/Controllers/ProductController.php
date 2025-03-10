<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProduct;
use App\Services\MarqueService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService){

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->productService->all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $marques = (new MarqueService())->all();
        return [
            "marques" => $marques
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProduct $request)
    {
        return $this->productService->store($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(int $product)
    {
        return $this->productService->find($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(CreateProduct $request, int $product)
    {
        return $this->productService->update($product, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $product)
    {
        return $this->productService->delete($product);
    }
}
