<?php

namespace App\Interfaces;

interface BaseInterface
{
    public function allPaginate($perPage, $request);

    public function store($data);

    public function update($data, $id);

    public function show($id);

    public function delete($id);
}
