<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    public function store()
    {
        $attributes = request()->validate([
            'title'       => 'required',
            'description' => 'required'
            
        ]);

        if (FALSE)
        {
            $attributes['owner_id'] = auth()->id();

            Project::create($attributes);
        }
        else
        {
            auth()->user()->projects()->create($attributes);
        }


        return redirect('/projects');
    }
}
