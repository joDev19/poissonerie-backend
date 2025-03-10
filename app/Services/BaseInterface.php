<?php
namespace App\Services;
interface BaseInterface{
    public function all();
    public function find($id);
    public function store($data);
    public function update($id, $data);
    public function delete($id);
    public function filter($data);
}
