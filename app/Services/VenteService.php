<?php
namespace App\Services;
use App\Models\Buyer;
use App\Models\Product;
use App\Models\Vente;
use Illuminate\Support\Collection;
class VenteService extends BaseService
{

    public function __construct(private Vente $vente)
    {
        parent::__construct($vente);
    }
    public function all(array $data = [], array $with = ["product"]){
        return parent::all($data, $with);
    }
    public function storeBuyerInformations(array $data){
        $buyerService = new BuyerService();
        $buyerData = null;
        if (isset($data['buyer_informations'])) {
            $buyerData = $buyerService->store($data['buyer_informations']);
        }
    }
    public function verifiyIfYouCanSell(Collection $dataCollection){
        $productService = new ProductService();
        $qte = null;
        $uniteDeMesure = null;
        if($dataCollection->get('type') == 'gros'){
            $qte = $productService->find($dataCollection->get('product_id'))->quantity->box;
            $uniteDeMesure = 'carton(s)';
        }else{
            $qte = $productService->find($dataCollection->get('product_id'))->quantity->kg;
            $uniteDeMesure = 'kg';
        }
        if($qte < $dataCollection->get("quantity")){
            abort(500, 'Il ne reste '.$qte.' '.$uniteDeMesure.' de ce produit. vous ne pouvez donc pas en vendre '.$dataCollection->get('quantity'));
        }
        return true;
    }

    public function store($data)
    {
        $this->storeBuyerInformations($data);
        $dataCollection = collect($data)->except('buyer_informations');
        if($this->verifiyIfYouCanSell($dataCollection)){
            return parent::store($dataCollection->toArray());
        }
    }
    public function create(){
        return ['products' => Product::with('marque')->get()];
    }
    public function find($id){
        return Vente::with('product.marque')->find($id);
    }
}
