<?php
namespace App\Services;
use App\Models\Fournisseur;
class FournisseurService extends BaseService {
    public function __construct(private $fournisseur = new Fournisseur()){
        parent::__construct($fournisseur);
    }
    public function find($id){
        return Fournisseur::with('approvisionements')->findOrFail($id);
    }
}
