<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }

        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'title'       => 'required',
            'description' => 'required',
            'notes'       => 'min:3'
        ]);

        // $attributes['owner_id'] = auth()->id();
        // Project::create($attributes);

        $project = auth()->user()->projects()->create($attributes);

        return redirect($project->path());
    }

    public function update(Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }

        $project->update([
            'notes' => request('notes')
        ]);

        return redirect($project->path());
    }
}
