<?php
namespace App\Services;
use App\Models\Fournisseur;
class FournisseurService extends BaseService {
    public function __construct(private $fournisseur = new Fournisseur()){
        parent::__construct($fournisseur);
    }
}
