<?php
namespace App\Services;
use App\Models\Product;
use App\Models\ProductQuantity;
use App\Http\Resources\ProductQuantityCollection;
class ProductQuantityService extends BaseService {
    public function __construct(private $product = new ProductQuantity()){
        parent::__construct($product);
    }
    public function getProductBoxes(){
        $query = ProductQuantity::with('product')->whereHas('product', function($productQuery){
            $productQuery->where('user_id', auth()->id())->whereNotNull('kilo_once_quantity');
        });
        return new ProductQuantityCollection($query->paginate());
    }
    public function setBoxPrice(int $id, array $data){
        $productQuantity = ProductQuantity::find($id);
        if($productQuantity){
            $productQuantity->price = $data['price'];
            $productQuantity->save();
        } else {
            abort('404', 'Product quantity not found');
        }
        return 1;
    }
}
