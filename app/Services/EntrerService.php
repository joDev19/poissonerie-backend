<?php
namespace App\Services;
use App\Models\Entrer;
class EntrerService extends BaseService
{

    public function __construct(private $entrer = new Entrer())
    {
        parent::__construct($entrer);
    }
    public function create(){
        // liste des produits
        return ['products' => (new ProductService())->all(), 'marques' => (new MarqueService())->all(), 'fournisseurs' => (new FournisseurService())->all()];
    }
    public function find($id){
        return Entrer::with(['product.marque', 'fournisseur'])->find($id);
    }
}
