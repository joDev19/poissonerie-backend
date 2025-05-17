<?php
namespace App\Services;
use App\Http\Resources\VenteRessource;
use App\Models\Product;
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
        $list = parent::all(null, $data, $with);
        return VenteRessource::collection($list);
    }
    public function storeBuyerInformations(array $data, $venteId)
    {
        // update the vente to set buyers_ifo to data
        $vente = Vente::find($venteId);
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
                $qte = $product->quantity->box;
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
        return DB::transaction(function () use ($dataCollection) {
            $vente = parent::store($dataCollection->only('date')->toArray());
            $selledProductService = new SelledProductService();
            foreach ($dataCollection->get('selled_products') as $key => $value) {
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
        }
        $queryBuilder = Vente::whereIn('id', $ids);
        return parent::filter(collect($data)->except('product_name_vente')->toArray(), $queryBuilder);
    }
}
