<?php
namespace App\Services;
use App\Models\Buyer;
use App\Models\Vente;
class VenteService extends BaseService
{
    public function __construct(private Vente $vente)
    {
        parent::__construct($vente);
    }

    public function store($data)
    {
        $buyerService = new BuyerService();
        $buyerData = null;
        if (isset($data['buyer_informations'])) {
            $buyerData = $buyerService->store($data['buyer_informations']);
        }
        $dataCollection = collect($data)->except('buyer_informations');
        return parent::store($dataCollection->toArray());
    }
    public function create(){
        return ['products' =>(new ProductService())->all()];
    }
}
