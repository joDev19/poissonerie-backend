<?php
namespace App\Services;
use App\Models\Entrer;
use App\Models\Fournisseur;
use App\Models\Marque;
use App\Models\Product;
class EntrerService extends BaseService
{

    public function __construct(private $entrer = new Entrer())
    {
        parent::__construct($entrer);
    }
    public function all($query = null, array $data = [], array $with = ["product"], $intern = false){
        return parent::all(null, $data, $with, $intern = false);
    }

    public function create(){
        // liste des produits
        return ['products' => Product::where('user_id', connectedBtqId())->get(), 'marques' => Marque::where('user_id', connectedBtqId())->get(), 'fournisseurs' => Fournisseur::where('user_id', connectedBtqId())->get()];
    }
    public function find($id, array $with = ['product.marque', 'fournisseur']){
        return parent::find($id, $with);
    }
}
