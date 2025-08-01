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
        $user = User::where('email', $data['email'])->first();
        if(!$user){
            abort(403, "Bad credentials");
        }
        if(!Hash::check($data['password'], $user->password)){
            abort(403, "Bad credentials");
        }
        $token = $user->createToken("");
        return ['token' => $token->plainTextToken];
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
        $request->user()->currentAccessToken()->delete();
        return 1;
    }
}
