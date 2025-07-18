<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Builder;
class BaseService implements BaseInterface
{
    public function __construct(private $model)
    {
    }

    public function all($query = null, array $data = [], array $with = [])
    {
        $queryBuilder = $this->model->orderByDesc('created_at');
        if ($query) {
            $queryBuilder = $query->orderByDesc('created_at');
        }
        if (count($with) > 0) {
            $queryBuilder = $queryBuilder->with($with);
        }

        if (count($data) > 0) {
            $queryBuilder = $this->filter(collect($data)->except('page')->toArray(), $queryBuilder);
        }
        return $queryBuilder->paginate(15);
    }

    public function find($id, array $with = [])
    {
        if (count($with) > 0) {
            return $this->model->with($with)->findOrFail($id);
        }else{
            return $this->model->findOrFail($id);
        }
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
    public function filter(array $data, $queryBuilder)
    {
        
        foreach ($data as $key => $value) {
            if ($key == 'name') {
                $queryBuilder = $queryBuilder->where($key, 'LIKE', '%' . $value . '%');
            } else if ($key == 'created_at') {
                $queryBuilder = $queryBuilder->whereDate($key, '=', $value . '%');
            } else if ($key == 'start_date') {
                $queryBuilder = $queryBuilder->whereDate('created_at', '>=', $value);
            } else if ($key == 'end_date') {
                $queryBuilder = $queryBuilder->whereDate('created_at', '<=', $value);
            } else if ($key == 'product_name') {
                $queryBuilder = $queryBuilder->whereHas('product', function (Builder $query) use ($value) {
                    $query->where('name', 'LIKE', '%' . $value . '%');
                });
            }
            else {
                $queryBuilder = $queryBuilder->where($key, '=', $value);
            }
        }
        return $queryBuilder;
    }
}
