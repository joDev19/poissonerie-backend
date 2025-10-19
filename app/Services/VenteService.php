<?php
namespace App\Services;
use App\Http\Resources\VenteRessource;
use App\Models\Product;
use App\Models\ProductQuantity;
use App\Models\SelledProduct;
use App\Models\SurplusVente;
use App\Models\Vente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
class VenteService extends BaseService
{
    private $haveMostThan = false;
    private $productSurplusIds = [];
    public function __construct(private Vente $vente)
    {
        parent::__construct($vente);
    }
    public function all($query = null, array $data = [], array $with = ["selledProducts"], $intern = false)
    {
        $query = Vente::query();
        $queryBuilder = $this->venteFilter($data, $query);
        $list = parent::all($queryBuilder, collect($data)->except(['product_name_vente', 'page'])->toArray(), ['selledProducts']);
        return VenteRessource::collection($list);
    }
    public function storeBuyerInformations(array $data, $venteId)
    {
        // update the vente to set buyers_ifo to data
        $vente = parent::find($venteId);
        $vente->buyer_infos = json_encode($data);
        $vente->save();

    }
    public function verifiyIfYouCanSell(Collection $dataCollection)
    {
        $productService = new ProductService();
        $qte = null;
        $uniteDeMesure = null;
        $product = $productService->find($dataCollection->get('product_id'));
        if ($product->category == 'kilo_ou_carton') {
            $pQ = ProductQuantity::where('product_id', $product->id)
                ->where('kilo_once_quantity', $dataCollection->get("quantity_per_box"))
                ->first();
            if ($dataCollection->get('type') == 'gros') {
                $qte = $pQ->box;
                $uniteDeMesure = 'carton(s)';
            } else {
               $qte = $pQ->kg;
                $uniteDeMesure = 'kg';
            }
        } else {
            $qte = $product->quantitys->unit;
            $uniteDeMesure = '';
        }
        if ($qte < $dataCollection->get("quantity")) {
            // on va ajouter dans la table surplus_vendus une ligne ( produit_id - vente_id - kilo_en_surplus ) ou bien
            // changer une variable à true
            $this->haveMostThan = true;
            array_push($this->productSurplusIds, ["productId" => $product->id, "excessQuantity" => ($dataCollection->get('quantity') - $qte)]);
            // abort(500, 'Il ne reste ' . $qte . ' ' . $uniteDeMesure . ' de ' . $product->name . '. vous ne pouvez donc pas en vendre ' . $dataCollection->get('quantity'));
        }
        return true;
    }

    public function store($data)
    {

        $dataCollection = collect($data);
        foreach ($dataCollection->get('selled_products') as $key => $value) {
            $this->verifiyIfYouCanSell(collect($value));
        }
        $vente = $this->createVente($dataCollection);
        return $vente;
    }
    protected function createVente(Collection $dataCollection)
    {
        //dd($dataCollection->only('date'));
        return DB::transaction(function () use ($dataCollection) {
            // calculer le prix total de la vente et vérifier...
            $vente_price = $this->calculatePrice($dataCollection->get('selled_products'), $dataCollection->get('price'));
            $vente = parent::store(array_merge($dataCollection->only(['date', 'is_paid', 'amount_paid', 'type', 'contains_gros'])->toArray(), ['price' => $vente_price]));
            $selledProductService = new SelledProductService();
            foreach ($dataCollection->get('selled_products') as $key => $value) {
                $selledProductService->store(array_merge($value, ['vente_id' => $vente->id]));
            }
            if ($dataCollection->has("contains_gros") && $dataCollection->get("contains_gros") == true) {
                $this->storeBuyerInformations($dataCollection->get('buyer_informations'), $vente->id);
            }
            $this->generateInvoice($vente);
            // si notre variable avait été modifié on ajoute une ligne dans la table vente_en_surplus
            if ($this->haveMostThan) {
                $this->storeSurplus($vente->id);
            }
            Vente::find($vente->id)->update([
                'invoice' => "invoices/invoice_" . $vente->id . ".pdf"
            ]);
            return ["message" => "Vente créé avec succès. Code de la vente: $vente->id", $vente];
        });
    }
    public function calculatePrice(array $selledProducts, float $price = null)
    {
        //dd($selledProducts, $price);
        $c = collect($selledProducts);
        $cPrice = $c->reduce(function ($precedent, $selledProduct) {
            // sell_price est le prix d'un carton - d'un kilo ou d'une unité
            return $precedent + ($selledProduct['quantity'] * $selledProduct['sell_price']);
        }, 0);
        if ($cPrice == $price) {
            return $cPrice;
        }
        abort(500, 'un soucis avec les prix');

    }
    public function create()
    {
        return ['products' => Product::with('marque')->where('user_id', connectedBtqId())->get()];
    }
    protected function generateInvoice(Vente $vente)
    {
        $pdf = Pdf::loadView('invoice', [
            'vente' => Vente::with('selledProducts.product')->find($vente->id),
        ])->setPaper('a5', 'portrait');
        // return $pdf->save('invoices/');
        return $pdf->save('invoice_' . $vente->id . '.pdf', 'invoices');
    }
    public function find($id, $with = ['selledProducts.product',])
    {
        $vente = parent::find($id, $with);
        return (new VenteRessource($vente))->toJson();
    }
    public function venteFilter(array $data = [], $queryBuilder = null)
    {
        if (array_key_exists('product_name_vente', $data)) {
            $queryBuilder->whereHas('selledProducts.product', function ($query) use ($data) {
                $query->where('name', 'LIKE', '%' . $data['product_name_vente'] . '%');
            });
        }
        return $queryBuilder;

    }
    public function statVente($startDate = null, $endDate = null)
    {
        //return $this->getSelledProductWithTheirQuantities($startDate, $endDate);
        return [
            "nombre_total_vente" => $this->getCountAllVentes($startDate, $endDate),
            "produitsVendus" => $this->getSelledProductWithTheirQuantities($startDate, $endDate),
            "nombre_total_vente_gros" => $this->getCountVenteEnGros($startDate, $endDate),
            "nombre_total_vente_detail" => $this->getCountVenteEnDetail($startDate, $endDate),
            "nombre_total_vente_payer" => $this->getCountVentePayer($startDate, $endDate),
            "nombre_total_vente_impayer" => $this->getCountVenteImpayer($startDate, $endDate),
            "nombre_total_vente_au_comptant" => $this->getCountVenteComptant($startDate, $endDate),
            "nombre_total_vente_a_terme" => $this->getCountVenteATerme($startDate, $endDate),
            "total_amount_ventes" => $this->totalAmountVente($startDate, $endDate),
        ];
    }
    public function totalAmountVente($startDate, $endDate)
    {
        $queryBuilder = Vente::where('user_id', auth()->user()->id);
        $queryBuilder = $this->filterDateQueryBuilder($startDate, $endDate, $queryBuilder);
        return $queryBuilder->sum('price');
    }
    public function getCountVenteComptant($startDate, $endDate)
    {
        $queryBuilder = Vente::where('user_id', auth()->user()->id);
        $queryBuilder = $this->filterDateQueryBuilder($startDate, $endDate, $queryBuilder);
        return $queryBuilder->where('type', '=', "au comptant")->count();
    }
    public function getCountVenteATerme($startDate, $endDate)
    {
        $queryBuilder = Vente::where('user_id', auth()->user()->id);
        $queryBuilder = $this->filterDateQueryBuilder($startDate, $endDate, $queryBuilder);
        return $queryBuilder->where('type', '=', "à terme")->count();
    }
    public function getCountVentePayer($startDate, $endDate)
    {
        $queryBuilder = Vente::where('user_id', auth()->user()->id);
        $queryBuilder = $this->filterDateQueryBuilder($startDate, $endDate, $queryBuilder);
        return $queryBuilder->where('is_paid', '=', 1)->count();
    }
    public function getCountVenteImpayer($startDate, $endDate)
    {
        $queryBuilder = Vente::where('user_id', auth()->user()->id);
        $queryBuilder = $this->filterDateQueryBuilder($startDate, $endDate, $queryBuilder);
        return $queryBuilder->where('is_paid', '=', 0)->count();
    }
    public function getCountVenteEnGros($startDate, $endDate)
    {
        $queryBuilder = Vente::where('user_id', auth()->user()->id);
        $queryBuilder = $this->filterDateQueryBuilder($startDate, $endDate, $queryBuilder);
        return $queryBuilder->where('contains_gros', '=', 1)->count();
    }
    public function getCountVenteEnDetail($startDate, $endDate)
    {
        $queryBuilder = Vente::where('user_id', auth()->user()->id);
        $queryBuilder = $this->filterDateQueryBuilder($startDate, $endDate, $queryBuilder);
        return $queryBuilder->where('contains_gros', '=', 0)->count();
    }
    public function getCountAllVentes($startDate, $endDate)
    {
        $queryBuilder = Vente::where('user_id', auth()->user()->id);
        $queryBuilder = $this->filterDateQueryBuilder($startDate, $endDate, $queryBuilder);
        return $queryBuilder->count();
    }
    public function getSelledProductWithTheirQuantities($startDate, $endDate)
    {

        $q = $this->filterDateQueryBuilder(
            $startDate,
            $endDate,
            Vente::where('ventes.user_id', auth()->user()->id)
        )
            ->join('selled_products', 'selled_products.vente_id', 'ventes.id');
        $queryBuilder = $q->where('selled_products.type', 'detail');
        $queryBuilder = $queryBuilder->join('products', 'selled_products.product_id', '=', 'products.id')
            ->join('marques', 'products.marque_id', '=', 'marques.id')
            ->select(
                'products.name as product_name',
                'marques.name as product_marque_name',
                DB::raw('SUM(selled_products.quantity) as sum_kg'),
                DB::raw('SUM(selled_products.quantity*selled_products.sell_price) as sum_price')
            );
        $details = $queryBuilder->groupBy(
            'products.id',
            'marques.id',
            'products.name',
            'marques.name'
        )->get();

        $qG = $this->filterDateQueryBuilder(
            $startDate,
            $endDate,
            Vente::where('ventes.user_id', auth()->user()->id)
        )
            ->join('selled_products', 'selled_products.vente_id', 'ventes.id');
        $queryBuilderGros = $qG->where('selled_products.type', 'gros');
        $queryBuilderGros = $queryBuilderGros->join('products', 'selled_products.product_id', '=', 'products.id')
            ->join('marques', 'products.marque_id', '=', 'marques.id')
            ->select(
                'products.name as product_name',
                'marques.name as product_marque_name',
                'selled_products.quantity_per_box as product_quantity_per_box',
                DB::raw('SUM(selled_products.quantity) as selled_box'),
                DB::raw('SUM(selled_products.quantity*selled_products.sell_price) as sum_price')
            );
        $gros = $queryBuilderGros->groupBy(
            'products.id',
            'marques.id',
            'products.name',
            'marques.name',
            'selled_products.quantity_per_box'
        )->get();
        return [
            "detail" => $details,
            "gros" => $gros
        ];
    }
    public function filterDateQueryBuilder($startDate, $endDate, $queryBuilder)
    {
        if ($startDate && $startDate != "null") {
            $queryBuilder = $queryBuilder->whereDate('ventes.created_at', '>=', $startDate);
        }
        if ($endDate && $endDate != "null") {
            $queryBuilder = $queryBuilder->whereDate('ventes.created_at', '<=', $endDate);
        }
        return $queryBuilder;

    }
    public function encaisser($id, $amount)
    {
        $vente = parent::find($id);
        if ($vente->is_paid) {
            abort(500, 'Cette vente a déjà été payée');
        }
        if ($amount <= 0) {
            abort(500, 'Le montant doit être supérieur à 0');
        }
        $amountPaid = $vente->amount_paid + $amount;
        if ($amountPaid == $vente->price) {
            $amountPaid = $vente->price;
            $vente->is_paid = true;
        } else if ($amountPaid > $vente->price) {
            abort(500, 'Le montant payé ne peut pas être supérieur au prix de la vente');
        } else {
            // Si le montant payé est inférieur au prix de la vente, on laisse is_paid à false
            // et on met à jour amount_paid
            if ($vente->type == "au comptant") {
                abort(500, 'Vous ne pouvez pas encaisser un montant inférieur au prix de la vente pour une vente au comptant');
            }
            $vente->is_paid = false;
        }
        $vente->amount_paid = $amountPaid;
        $vente->save();
        return ['message' => 'Paiement enregistré avec succès', 'vente' => new VenteRessource($vente)];
    }
    public function storeSurplus($venteId)
    {
        $surplusVenteService = new SurplusVenteService();
        $selledProductService = new SelledProductService();
        foreach ($this->productSurplusIds as $key => $productSurplusId) {
            $sP = $selledProductService->filter(['vente_id' => $venteId, 'product_id' => $productSurplusId['productId']])->first();
            $surplusVenteService->store([
                "vente_id" => $venteId,
                "selled_product_id" => $sP->id,
                "excess_quantity" => $productSurplusId['excessQuantity']
            ]);
        }
    }
}
