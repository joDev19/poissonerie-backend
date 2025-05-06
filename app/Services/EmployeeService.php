<?php
namespace App\Services;
use App\Models\User;
use App\Services\BaseService;
class EmployeeService extends BaseService{
    public function __construct(private $user = new User()){
        parent::__construct($user);
    }
    public function all($query = null, array $data = [], array $with = []){
        $querybuilder = User::where('role', 'employee');
        return parent::all($querybuilder, $data, $with);
    }
    public function checkIfUserIsEmployee($id){
        if(User::find($id)->role != 'employee'){
            return abort(403, 'Cet utilisateur n\'est pas un employé');
        }
    }
    public function find($id, array $with = []){
        $this->checkIfUserIsEmployee($id);
        return parent::find($id, $with);
    }
    
    public function delete($id)
    {
        $this->checkIfUserIsEmployee($id);
        return parent::delete($id);
    }
}
