<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEntrer;
use App\Models\Entrer;
use App\Services\EntrerService;
use Illuminate\Http\Request;

class EntrerController extends Controller
{
    public function __construct(protected EntrerService $entrerService){

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->entrerService->all(null, $request->all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->entrerService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEntrer $request)
    {
        return $this->entrerService->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(int $entrer)
    {
        return $this->entrerService->find($entrer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entrer $entrer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateEntrer $request, int $entrer)
    {
        return $this->entrerService->update($entrer, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $entrer)
    {
        return $this->entrerService->delete($entrer);
    }
}
