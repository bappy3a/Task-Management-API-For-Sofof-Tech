<?php

namespace App\Interfaces\Task;
use App\Interfaces\BaseInterface;

interface TaskServiceInterface extends BaseInterface
{
    public function assign($request, $id);
}
