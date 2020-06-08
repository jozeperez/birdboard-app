<?php

namespace Tests\Feature;

use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
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

    public function test_only_the_owner_of_a_project_may_update_a_task()
    {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    public function test_a_project_can_have_tasks()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
             ->post($project->path() . '/tasks', ['body' => 'This is a test task for our test project']);

        $this->get($project->path())
            ->assertSee('This is a test task for our test project');
    }

    public function test_a_task_requires_a_body()
    {
        $project = ProjectFactory::create();

        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->actingAs($project->owner)
             ->post($project->path() . '/tasks', $attributes)
             ->assertSessionHasErrors('body');
    }

    public function test_a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
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
