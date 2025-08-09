<?php
namespace App\Services;
use App\Models\SurplusVente;
class SurplusVenteService extends BaseService{
    public function __construct(private $surplusVente = new SurplusVente())
    {
        parent::__construct($surplusVente);
    }
}
