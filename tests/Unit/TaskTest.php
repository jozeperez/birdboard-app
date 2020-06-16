<?php

namespace Tests\Unit;

use App\Project;
use App\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase; // using this instead because we need to use a factory

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_task_belongs_to_a_project()
    {
        $task = factory('App\Task')->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }

    function test_it_has_a_path()
    {
        $task = factory('App\Task')->create();

        $this->assertEquals('/projects/' . $task->project->id . '/tasks/' . $task->id, $task->path());
    }

    public function test_can_be_completed()
    {
        $task = factory('App\Task')->create();

        $this->assertFalse($task->completed);

        $task->complete();

        $this->assertTrue($task->fresh()->completed);
    }
}
