<?php

namespace App\Interfaces;

interface BaseRepositoryInterface {
    public function index();
    public function show($id);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function indexWithPaginate($perPage);
}
