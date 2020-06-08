<?php

namespace Tests\Feature;

use App\Project;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use withFaker, RefreshDatabase;

    public function test_guest_cannot_manage_project()
    {
        $project = factory('App\Project')->create();

        $this->post('/projects', $project->toArray())->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get('/projects')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
    }

    public function test_a_user_can_create_a_project()
    {
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title'       => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes'       => 'General notes here.'
        ];

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    public function test_a_user_can_update_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
             ->patch($project->path(), $changeAttributes = ['notes' => 'General notes here. New note here.'])
             ->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $changeAttributes);
    }

    public function test_an_authenticated_user_cannot_view_projects_of_others()
    {
        $notUs = factory('App\User')->create();

        $this->signIn();

        $project = factory('App\Project')->create(['owner_id' => $notUs->id]);

        $this->get($project->path())->assertStatus(403);
    }

    public function test_an_authenticated_user_cannot_update_projects_of_others()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->patch($project->path())->assertStatus(403);
    }

    public function test_a_user_can_view_their_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
             ->get($project->path())
             ->assertSee($project->title)
             ->assertSee($project->description);
    }

    public function test_a_project_requires_a_title()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    public function test_a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
