<?php
namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class UserService extends BaseService
{

    public function __construct(private $user = new User())
    {
        parent::__construct($user);
    }
    public function login($data)
    {
        if (Auth::attempt($data)) {
            return Auth::user();
        }
        abort(403, "Bad credentials");
    }
    public function changeInfos($data)
    {
        Auth::user()->name = $data['name'];
        Auth::user()->email = $data['email'];
        Auth::user()->save();
        return 1;
    }
    public function changePassword($data)
    {
        $user = Auth::user();
        if (Hash::check($data['oldPassword'], $user->password)) {
            $user->password = Hash::make($data['password']);
            $user->save();
            return 1;
        } else {
            return abort(403, "L'ancien mot de passe est incorrect");
        }
    }
    public function logout($request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return 1;
    }
}
