<?php
namespace App\Services;
use App\Models\Product;
use App\Models\ProductQuantity;
class ProductQuantityService extends BaseService {
    public function __construct(private $product = new ProductQuantity()){
        parent::__construct($product);
    }
    public function setBoxPrice(int $productId, array $data){
        $productQuantity = ProductQuantity::where('product_id', $productId)
            ->where('kilo_once_quantity', $data['kilo_once_quantity'])
            ->first();
        if($productQuantity){
            $productQuantity->price = $data['price'];
            $productQuantity->save();
        } else {
            abort('404', 'Product quantity not found');
        }
        return 1;
    }
}
