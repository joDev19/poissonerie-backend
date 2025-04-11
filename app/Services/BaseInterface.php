<?php
namespace App\Services;
interface BaseInterface{
    public function all(array $data = []);
    public function find($id);
    public function store($data);
    public function update($id, $data);
    public function delete($id);
    public function filter(array $data, $queryBuilder);
}
