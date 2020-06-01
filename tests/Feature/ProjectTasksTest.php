<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

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
}
