<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use function Symfony\Component\String\u;

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
        $memberIn = DB::table('project_member')->where('user_id', $user->id)->get();
        $memberInProjects = [];
        foreach ($memberIn as $member){
            $tmp = DB::table('projects')->where('id', $member->project_id)->get();
            if(!$tmp->isEmpty()){
                foreach ($tmp as $t){
                    $memberInProjects[] = $t;
                }
            }
        }
        return Inertia::render('Project/Index', ['projects' =>$projects, 'memberInProjects' => $memberInProjects]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, $this->rules);
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
        //$data = $request::createFromGlobals()->all();
        $user = $request->user();
        $data['owner_id'] = $user->id;
        Project::create($data);
        return Redirect::route("project.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Request $request)
    {
        //owner or member
        $boards = $project->boards;
        $owner = $project->owner;
        $userLoggedIn = $request->user();
        $members = DB::table('project_member')->where("project_id", $project->id)->get();
        $this->authorize('view', [Project::class, $project, $members]);
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
        //owner
        $this->authorize('update', [Project::class, $project]);
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
        //owner
        $this->authorize('delete', [Project::class, $project]);
        $project->delete();
        return Redirect::route("project.index");
    }

    public function addMemberToProject(Project $project, Request $request){
        //owner
        $this->authorize('addMember', [Project::class, $project]);
        $this->validate($request, $this->rulesMember);
        $data = $request::createFromGlobals()->all();
        $user = DB::table('users')->where('email',$data['email'])->first();
        DB::table("project_member")->insert([
            "project_id" => $project->id,
            "user_id" => $user->id,
            "role" => $data['role'],
            "created_at" => date("Y-m-d H-i-s"),
            "updated_at" => date("Y-m-d H-i-s")
        ]);
        return Redirect::route("project.show", ['project' => $project]);
    }
}
