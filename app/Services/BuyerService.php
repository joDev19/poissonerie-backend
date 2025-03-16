<?php
namespace App\Services;
use App\Models\Buyer;
class BuyerService extends BaseService
{
    public function __construct(private Buyer $buyer = new Buyer())
    {
        parent::__construct($buyer);
    }
}
