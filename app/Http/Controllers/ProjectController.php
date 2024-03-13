<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
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

    protected $rulesMember = [
        'email' => ['required'],
        'role' => ['required']
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
    public function show(Project $project, Request $request)
    {
        //
        $boards = $project->boards;
        $owner = $project->owner;
        $userLoggedIn = $request->user();
        $members = DB::table('project_member')->where("project_id", $project->id)->get();
        $users = [];
        foreach ($members as $member) {
            /*if($member->id === $userLoggedIn->id){
                $permission = true;
            }*/
            $users[] = (object)[
                'user_id' => $member->user_id,
                'name' => User::find($member->user_id)->name,
                'role' => $member->role,
            ];
        }
        return Inertia::render("Project/Show", ['project' => $project, 'boards' => $boards, 'owner' => $owner,
            'loggedInUser' => $userLoggedIn, 'members' => $users]);
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

    public function addMemberToProject(Project $project, Request $request){
        $this->validate($request, $this->rulesMember);
        $data = $request::createFromGlobals()->all();
        $user = DB::table('users')->where('email',$data['email'])->first();
        DB::table("project_member")->insert([
            "project_id" => $project->id,
            "user_id" => $user->id,
            "role" => $data['role']
        ]);
        return Redirect::route("projects.show", ['project' => $project]);
    }
}
