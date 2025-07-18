<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVente;
use App\Http\Requests\EncaisserPaiementRequest;
use App\Models\Vente;
use App\Services\VenteService;
use Illuminate\Http\Request;

class VenteController extends Controller
{
    public function __construct(private VenteService $venteService){
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->venteService->all(null, $request->all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->venteService->create();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateVente $request)
    {
        return $this->venteService->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(int $vente)
    {
        return $this->venteService->find($vente);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vente $vente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateVente $request, int $vente)
    {
        return $this->venteService->update($vente, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        return $this->venteService->delete($id);
    }

    public function statVente(Request $request)
    {
        return $this->venteService->statVente($request->startDate, $request->endDate);
    }
    public function encaisser($id, EncaisserPaiementRequest $request)
    {
        return $this->venteService->encaisser($id, $request->validated('amount_paid_this_time'));
    }
    
}
