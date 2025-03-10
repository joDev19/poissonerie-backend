<?php

namespace App\Http\Controllers;

use App\Http\Requests\FournisseurCreateRequest;
use App\Models\Fournisseur;
use App\Services\FournisseurService;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function __construct(private FournisseurService $fournisseurService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->fournisseurService->all();
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
    public function store(FournisseurCreateRequest $request)
    {
        return $this->fournisseurService->store($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(int $fournisseur)
    {
        return $this->fournisseurService->find($fournisseur);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fournisseur $fournisseur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FournisseurCreateRequest $request, int $fournisseur)
    {
        return $this->fournisseurService->update($fournisseur, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $fournisseur)
    {
        return $this->fournisseurService->delete($fournisseur);
    }
}
