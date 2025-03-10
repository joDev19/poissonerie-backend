<?php
namespace App\Services;
use App\Models\User;
class AdminService extends UserService
{
    public function __construct()
    {
        parent::__construct();
    }
}
