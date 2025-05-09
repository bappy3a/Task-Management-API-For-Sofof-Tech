<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Interfaces\Task\TaskServiceInterface;
use Illuminate\Http\Request;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    use ApiResponse;
    public function __construct(private TaskServiceInterface $service){}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = request('per_page') ?? config('app.per_page');
        $data = $this->service->allPaginate($request,$perPage);
        
        if(!$data){
            return $this->ResponseSuccess([], null, 'No Data Found!');
        }
        return $this->ResponseSuccess($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $data = $this->service->store($request);
            return $this->ResponseSuccess($data);
        } catch (\Exception $e) {
            return $this->ResponseError($e->getMessage(). " in " . $e->getFile() . " on line " . $e->getLine(), null, 'Data Process Error! Consult Tech Team');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->service->show($id);
        if(!$data){
            return $this->ResponseSuccess([], null, 'No Data Found!');
        }
        return $this->ResponseSuccess($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        try {
            $data = $this->service->update($request,$id);
            return $this->ResponseSuccess($data);
        } catch (\Exception $e) {
            return $this->ResponseError($e->getMessage(). " in " . $e->getFile() . " on line " . $e->getLine(), null, 'Data Process Error! Consult Tech Team');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = $this->service->delete($id);
            if(!$data){
                return $this->ResponseSuccess([], null, 'Task Not Found!', 404);
            }
            return $this->ResponseSuccess([], null, 'Task successfully delete!', 200);

        } catch (\Exception $e) {
            return $this->ResponseError($e->getMessage(). " in " . $e->getFile() . " on line " . $e->getLine(), null, 'Data Not Found!');
        }
    }
}
