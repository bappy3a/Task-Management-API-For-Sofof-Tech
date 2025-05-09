<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;
use App\Models\User;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_authenticated_user_can_fetch_all_tasks()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        
        $task = Task::factory()->create([
            'creator_id' => $user->id,
            'title' => 'Install latest laravel',
            'description' => 'Install latest laravel & set up your work environment',
            'due_date' => now()->addDays(rand(1, 10)),
            'status' => 'todo',
            'priority' => 'medium',
        ]);
        $task->creator()->associate($user);
        $task->save();
        
        $response = $this->getJson('/api/v1/tasks');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'status_code',
            'status',
            'message',
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'creator_id',
                        'title',
                        'description',
                        'due_date',
                        'status',
                        'priority',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                        'creator' => [
                            'id',
                            'name',
                            'email',
                            'role'
                        ],
                        'assigned_users' => [
                            '*' => [
                                'id',
                                'name',
                                'email',
                                'role',
                                'pivot' => [
                                    'task_id',
                                    'user_id',
                                    'created_at',
                                    'updated_at',
                                ]
                            ]
                        ],
                    ]
                ],
                'first_page_url',
                'last_page_url',
                'next_page_url',
                'prev_page_url',
                'total',
            ],
        ]);

        $this->assertTrue($response['success']);
        $this->assertEquals('Install latest laravel', $response['data']['data'][0]['title']);
    }

    public function test_authenticated_user_can_create_task()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $taskData = [
            'title' => 'Install latest laravel',
            'description' => 'Install latest laravel & set up your work environment',
            'due_date' => '2025-05-09 11:03:44',
            'status' => 'todo',
            'priority' => 'medium',
        ];

        $response = $this->postJson('/api/v1/tasks', $taskData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'status_code',
            'status',
            'message',
            'data' => [
                'id',
                'creator_id',
                'title',
                'description',
                'due_date',
                'status',
                'priority',
                'created_at',
                'updated_at',
                'deleted_at',
                'creator' => [
                    'id',
                    'name',
                    'email',
                    'role',
                ],
                'assigned_users' => [],
            ],
            'metadata',
        ]);
        $responseData = $response->json();

        $this->assertTrue($responseData['success']);
        $this->assertEquals(200, $responseData['status']);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Install latest laravel',
            'description' => 'Install latest laravel & set up your work environment',
        ]);
    }

    public function test_authenticated_user_can_fetch_single_task()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum'); // Authenticate using Sanctum

        $task = Task::factory()->create([
            'creator_id' => $user->id,
            'title' => 'Install latest laravel',
            'description' => 'Install latest laravel & set up your work environment',
            'due_date' => now()->addDays(rand(1, 10)),
            'status' => 'todo',
            'priority' => 'medium',
        ]);
        $task->creator()->associate($user);
        $task->save();

        $response = $this->getJson("/api/v1/tasks/{$task->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'status_code',
            'status',
            'message',
            'data' => [
                'id',
                'creator_id',
                'title',
                'description',
                'due_date',
                'status',
                'priority',
                'created_at',
                'updated_at',
                'deleted_at',
                'creator' => [
                    'id',
                    'name',
                    'email',
                    'role',
                ],
                'assigned_users' => [],
            ],
            'metadata',
        ]);
        $responseData = $response->json();
        $this->assertTrue($responseData['success']);
        $this->assertEquals(200, $responseData['status']);
    }

    public function test_authenticated_user_can_update_task()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create([
            'creator_id' => $user->id,
            'title' => 'Install old laravel update',
            'description' => 'Install latest laravel & set up your work environment',
            'due_date' => now()->format('Y-m-d H:i:s'),
            'status' => 'todo',
            'priority' => 'medium',
        ]);

        $task->creator()->associate($user);
        $task->save();

        $updateData = [
            'title' => 'Install old laravel update',
            'description' => 'Install latest laravel & set up your work environment',
            'due_date' => '2025-05-09 11:03:44',
            'status' => 'todo',
            'priority' => 'medium',
        ];

        $response = $this->putJson('/api/v1/tasks/' . $task->id, $updateData);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'status_code',
            'status',
            'message',
            'data' => [
                'id',
                'creator_id',
                'title',
                'description',
                'due_date',
                'status',
                'priority',
                'created_at',
                'updated_at',
                'deleted_at',
                'creator' => [
                    'id',
                    'name',
                    'email',
                    'role'
                ],
                'assigned_users' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'pivot' => [
                            'task_id',
                            'user_id',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertTrue($response['success']);
        $this->assertEquals('Install old laravel update', $response['data']['title']);
        $this->assertEquals('todo', $response['data']['status']);
        $this->assertEquals('medium', $response['data']['priority']);
    }

    public function test_authenticated_user_can_delete_task()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $task = Task::factory()->create([
            'creator_id' => $user->id,
            'title' => 'Install old laravel update',
            'description' => 'Install latest laravel & set up your work environment',
            'due_date' => now()->format('Y-m-d H:i:s'),
            'status' => 'todo',
            'priority' => 'medium',
        ]);


        $response = $this->deleteJson('/api/v1/tasks/' . $task->id);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Task successfully delete!',
        ]);
    }

    public function test_authenticated_user_can_assign_task_to_multiple_users()
    {
        $creator = User::factory()->create([
            'name' => 'Md Allrafi Islam (Bappy)',
            'email' => 'bappy@dev.local',
            'role' => 'employee',
        ]);
        $this->actingAs($creator, 'sanctum');

        $assignedUsers = User::factory()->count(2)->sequence(
            ['email' => 'bappy3@dev.local'],
            ['email' => 'bappy5@dev.local']
        )->create();

        $allUserIds = collect($assignedUsers)->pluck('id')->toArray();
        $allUserIds[] = $creator->id;

        $task = Task::factory()->create([
            'creator_id' => $creator->id,
            'title' => 'Install old laravel update',
            'description' => 'Install latest laravel & set up your work environment',
            'due_date' => now()->format('Y-m-d H:i:s'),
            'status' => 'todo',
            'priority' => 'medium',
        ]);

        $response = $this->postJson("/api/v1/tasks/{$task->id}/assign", [
            'user_ids' => $allUserIds,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Successfully!',
        ]);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'creator_id',
                'title',
                'description',
                'due_date',
                'status',
                'priority',
                'created_at',
                'updated_at',
                'deleted_at',
                'creator' => [
                    'id',
                    'name',
                    'email',
                    'role'
                ],
                'assigned_users' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'pivot' => [
                            'task_id',
                            'user_id',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]
            ]
        ]);

        // Check that all users were assigned to the task
        foreach ($allUserIds as $userId) {
            $this->assertDatabaseHas('task_user', [
                'task_id' => $task->id,
                'user_id' => $userId,
            ]);
        }
    }
}
