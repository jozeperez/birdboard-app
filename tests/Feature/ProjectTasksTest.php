<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_cannot_add_tasks_to_projects()
    {
        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks')
            ->assertRedirect('login');
    }

    public function test_only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();

        $notAuthenticatedUser = factory('App\User')->create();

        $project = factory('App\Project')->create(['owner_id' => $notAuthenticatedUser->id]);

        $this->post($project->path() . '/tasks', ['body' => 'Test Task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test Task']);
    }

    public function test_only_the_owner_of_a_project_may_update_tasks()
    {
        $notAuthenticatedUser = factory('App\User')->create();

        $project = factory('App\Project')->create(['owner_id' => $notAuthenticatedUser->id]);

        $task = $project->addTask('Test Task');

        $this->assertDatabaseHas('tasks', ['body' => 'Test Task']);

        $this->signIn();

        $this->patch($task->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseHas('tasks', ['body' => 'Test Task']);
    }

    public function test_a_project_can_have_tasks()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'This is a test task for our test project']);

        $this->get($project->path())
            ->assertSee('This is a test task for our test project');
    }

    public function test_a_task_requires_a_body()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }

    public function test_a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory('App\Project')->raw()
        );

        $task = $project->addTask('Test Task');

        $this->patch($task->path(), [
            'body'      => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks', [
            'body'      => 'changed',
            'completed' => true
        ]);

    }

    // public function test_a_task_requires_a_body()
    // {

    // }
}
