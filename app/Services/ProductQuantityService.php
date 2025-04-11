<?php
namespace App\Services;
use App\Models\Product;
use App\Models\ProductQuantity;
class ProductQuantityService extends BaseService {
    public function __construct(private $product = new ProductQuantity()){
        parent::__construct($product);
    }
}
