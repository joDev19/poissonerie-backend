<?php
namespace App\Services;
use App\Models\Marque;
class MarqueService extends BaseService {
    public function __construct(private $marque = new Marque()){
        parent::__construct($marque);
    }
}
