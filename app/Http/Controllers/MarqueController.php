<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarqueCreateRequest;
use App\Models\Marque;
use App\Services\MarqueService;
use Illuminate\Http\Request;

class MarqueController extends Controller
{
    public function __construct(private MarqueService $marqueService){

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->marqueService->all($request->all());
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
    public function store(MarqueCreateRequest $request)
    {
        return $this->marqueService->store($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(int $marque)
    {
        return $this->marqueService->find($marque);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marque $marque)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MarqueCreateRequest $request, int $marque)
    {
        return $this->marqueService->update($marque, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $marque)
    {
        return $this->marqueService->delete($marque);
    }
}
