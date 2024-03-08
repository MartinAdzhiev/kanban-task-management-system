<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $rules = [
        'name' => ['required'],
        'description' => ['required']
    ];

    public function index(Request $request)
    {
        //
        $user = $request->user();
        $projects = DB::table('projects')->where('owner_id', $user->id)->get();
        return Inertia::render('Project/Index', ['projects' =>$projects]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, $this->rules);
        $project = $request::createFromGlobals()->all();
        $user = $request->user();
        $project['owner_id'] = $user->id;
        Project::create($project);
        return Redirect::route("project.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
        return Inertia::render("Project/Show", ['project' => compact($project)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
        $this->validate($request, $this->rules);
        $data = $request::createFromGlobals()->all();
        $project->update($data);
        return Redirect::route("project.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
        $project->delete();
        return Redirect::route("project.index");
    }
}
