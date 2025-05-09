<?php

namespace App\Services\Task;

use App\Interfaces\Task\TaskServiceInterface;

class TaskService implements TaskServiceInterface
{
    public function allPaginate($request,$perPage){
    }
    public function store($request){
    }

    public function update($request, $id){
    }

    public function show($id){
    }

    public function delete($id){
        $data = $this->show($id);
        if (!$data) {
            return false;
        }
        $data->delete();

        return true;
    }

}
