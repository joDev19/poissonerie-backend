<?php
namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class UserService extends BaseService
{

    public function __construct(private $user = new User())
    {
        parent::__construct($user);
    }
    public function login($data){
        if(Auth::attempt($data)){
            return Auth::user();
        }
        abort(403, "Bad credentials");
    }
    public function logout(){
        Auth::logout();
        session()->invalidate();
        session()->regenerate();
    }
}
