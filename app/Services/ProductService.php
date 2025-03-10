<?php
namespace App\Services;
use App\Models\Product;
class ProductService extends BaseService {
    public function __construct(private $product = new Product()){
        parent::__construct($product);
    }

    public function all(){
        return Product::with('marque')->get();
    }
}
