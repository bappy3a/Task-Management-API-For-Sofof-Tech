<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('assigns a task to multiple users', function () {

    $users = User::factory()->count(2)->create();
    $task = Task::factory()->create();

    $task->assignedUsers()->attach($users->pluck('id'));

    $this->assertCount(2, $task->assignedUsers);
    $this->assertTrue($users->first()->tasks->contains($task));

    $this->assertTrue($task->assignedUsers->pluck('id')->contains($users->first()->id));
    $this->assertTrue($task->assignedUsers->pluck('id')->contains($users->last()->id));
});