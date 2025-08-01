<?php
namespace App\Services;
use App\Models\Marque;
use App\Models\Product;
class ProductService extends BaseService {
    public function __construct(private $product = new Product()){
        parent::__construct($product);
    }

    public function all($query = null, array $data = [], array $with = []){
        // return Product::with('marque')->paginate();
        return parent::all(null, $data, ['marque']);
    }
    public function create(){
        return ['marques' => Marque::where('user_id', auth()->user()->id)->get()];
    }
}
