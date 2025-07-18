<?php
namespace App\Services;
use App\Http\Resources\VenteRessource;
use App\Models\Product;
use App\Models\SelledProduct;
use App\Models\Vente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
class VenteService extends BaseService
{

    public function __construct(private Vente $vente)
    {
        parent::__construct($vente);
    }
    public function all($query = null, array $data = [], array $with = [])
    {
        // dd($data);
        $list = parent::all(null, $data, $with);
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
            if ($dataCollection->get('type') == 'gros') {
                $qte = (float) $product->quantity->kg / (float) $dataCollection->get("quantity_per_box");
                //dd($qte);
                $uniteDeMesure = 'carton(s)';
            } else {
                $qte = $product->quantity->kg;
                $uniteDeMesure = 'kg';
            }
        } else {
            $qte = $product->quantity->unit;
            $uniteDeMesure = '';
        }
        if ($qte < $dataCollection->get("quantity")) {
            abort(500, 'Il ne reste ' . $qte . ' ' . $uniteDeMesure . ' de ' . $product->name . '. vous ne pouvez donc pas en vendre ' . $dataCollection->get('quantity'));
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
                //dd($value);
                $selledProductService->store(array_merge($value, ['vente_id' => $vente->id]));
            }
            if ($dataCollection->has("contains_gros") && $dataCollection->get("contains_gros") == true) {
                // dd($vente->id);
                $this->storeBuyerInformations($dataCollection->get('buyer_informations'), $vente->id);
            }
            $this->generateInvoice($vente);
            Vente::where('id', $vente->id)->update([
                'invoice' => "invoices/invoice_" . $vente->id . ".pdf"
            ]);

            return $vente;
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
        return ['products' => Product::with('marque')->get()];
    }
    protected function generateInvoice(Vente $vente)
    {
        $pdf = Pdf::loadView('invoice', [
            'vente' => Vente::with('selledProducts.product')->find($vente->id),
        ])->setPaper('a5', 'portrait');
        // return $pdf->save('invoices/');
        return $pdf->save('invoice_' . $vente->id . '.pdf', 'invoices');
    }
    public function find($id, $with = ['selledProducts.product'])
    {
        $vente = parent::find($id, $with);
        return (new VenteRessource($vente))->toJson();
    }
    public function filter(array $data = [], $queryBuilder)
    {
        $ids = [];
        if (array_key_exists('product_name_vente', $data)) {
            $ids = $queryBuilder
                ->join('selled_products', 'ventes.id', '=', 'selled_products.vente_id')
                ->join('products', 'selled_products.product_id', '=', 'products.id')
                ->where('products.name', 'LIKE', '%' . $data['product_name_vente'] . '%')
                ->select('ventes.*')
                ->distinct()->pluck('id');
            $queryBuilder = Vente::whereIn('id', $ids);
        }

        return parent::filter(collect($data)->except(['product_name_vente'])->toArray(), $queryBuilder);
    }
    public function statVente($startDate = null, $endDate = null)
    {
        $queryBuilder = Vente::query();
        if ($startDate) {
            $queryBuilder = $queryBuilder->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $queryBuilder = $queryBuilder->whereDate('created_at', '<=', $endDate);
        }
        // Nombre total de vente
        $nombreTotalVente = $queryBuilder->count();
        // Nombre total des ventes en gros
        $nombreTotalVenteGros = $queryBuilder->join('selled_products', 'ventes.id', '=', 'selled_products.vente_id')
            ->where('selled_products.type', '=', 'gros')
            ->count();
        $nombreTotalVenteDetail = $queryBuilder->join('selled_products as sp', 'ventes.id', '=', 'selled_products.vente_id')
            ->where('sp.type', '=', 'detail')->distinct('ventes.id')
            ->count();
        // Liste des produits vendu respectivement avec les quantité vendus
        // $produitsVendus = $queryBuilder
        //     ->join('selled_products', 'ventes.id', '=', 'selled_products.vente_id')
        //     ->join('products', 'selled_products.product_id', '=', 'products.id')
        //     ->select('products.name as product_name', DB::raw('SUM(selled_products.quantity) as total_quantity'))
        //     ->groupBy('products.name')
        //     ->get();
        return [
            "nombre_total_vente" => $nombreTotalVente,
            // "produitsVendus" => $produitsVendus,
            "nombre_total_vente_gros" => $nombreTotalVenteGros,
            "nombre_total_vente_detail" => $nombreTotalVenteDetail,
        ];
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
            if($vente->type=="au comptant"){
                abort(500, 'Vous ne pouvez pas encaisser un montant inférieur au prix de la vente pour une vente au comptant');
            }
            $vente->is_paid = false;
        }
        $vente->amount_paid = $amountPaid;
        $vente->save();
        return ['message' => 'Paiement enregistré avec succès', 'vente' => new VenteRessource($vente)];
    }
}
