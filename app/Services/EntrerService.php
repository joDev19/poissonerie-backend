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
    public function all($query = null, array $data = [], array $with = ["product"]){
        return parent::all(null, $data, $with);
    }
    public function create(){
        // liste des produits
        return ['products' => Product::where('user_id', auth()->user()->id)->get(), 'marques' => Marque::where('user_id', auth()->user()->id)->get(), 'fournisseurs' => Fournisseur::where('user_id', auth()->user()->id)->get()];
    }
    public function find($id, array $with = ['product.marque', 'fournisseur']){
        return parent::find($id, $with);
    }
}
