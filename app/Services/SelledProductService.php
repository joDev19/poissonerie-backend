<?php
namespace App\Services;

use App\Models\SelledProduct;
class SelledProductService extends BaseService
{
    public function __construct(private SelledProduct $selledProduct = new SelledProduct())
    {
        parent::__construct($selledProduct);
    }
}
