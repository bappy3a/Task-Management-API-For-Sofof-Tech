<?php

namespace App\Services\Task;

use App\Interfaces\Task\TaskServiceInterface;
use App\Models\Task;

class TaskService implements TaskServiceInterface
{
    public function allPaginate($request,$perPage)  
    {
        return Task::withRelations()
        ->when($request->has('search'), function ($query) use ($request) {
            $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);
    }
    public function store($request){
        $task = new Task();
        $task->creator_id = $request->user()->id;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->status = $request->status;
        $task->priority = $request->priority;
        $task->save();

        return $this->show($task->id);
    }

    public function update($request, $id){
        $task = $this->show($id);
        if (!$task) {
            return false;
        }
        $task->creator_id = $request->user()->id;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->status = $request->status;
        $task->priority = $request->priority;
        $task->save();

        return $this->show($id);

    }

    public function show($id){
        $task = Task::withRelations()->find($id);
        if (!$task) {
            return false;
        }
        return $task;
    }

    public function delete($id){
        $data = $this->show($id);
        if (!$data) {
            return false;
        }
        $data->delete();

        return true;
    }

    public function assign($request, $id){
        $task = $this->show($id);
        if (!$task) {
            return false;
        }
        $task->assignedUsers()->sync($request->user_ids);

        return $this->show($id);
    }

}
