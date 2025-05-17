<?php

use App\Models\Entrer;
use App\Models\Vente;
class StatService {
    public function index(){

        $totalVentes = $this->getTotalVentes(Vente::query());
        $totalEntrers = $this->getTotalEntrers(Entrer::query());
        $totalProducts = \App\Models\Product::count();
        $totalFournisseurs = \App\Models\Fournisseur::count();
        $totalMarques = \App\Models\Marque::count();
        return response()->json([
            'totalVentes' => $totalVentes,
            'totalEntrers' => $totalEntrers,
            'totalProducts' => $totalProducts,
            'totalFournisseurs' => $totalFournisseurs,
            'totalMarques' => $totalMarques
        ]);
    }
    public function getTotalVentes($queryBuilder){
        return $queryBuilder->count();
    }
    public function getTotalEntrers($queryBuilder){
        return $queryBuilder->count();
    }
}
