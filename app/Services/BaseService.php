<?php
namespace App\Services;
class BaseService implements BaseInterface{
    public function __construct(private $model){}

    public function all()
    {
        return $this->model->orderByDesc('created_at')->get();
        // return $this->model->paginate(25);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $model = $this->model->findOrFail($id);
        return $model->update($data);
    }

    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }
    public function filter($data)
    {
        return $this->model->where($data)->get();
    }
}
