<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase; // using this instead because we need to use a factory

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_a_path()
    {
        $project = factory('App\Project')->create();

        $this->assertSame('/projects/' . $project->id, $project->path());
    }
}
