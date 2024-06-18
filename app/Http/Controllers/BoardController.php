<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Models\Board;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use function PHPUnit\Framework\isEmpty;

class BoardController extends Controller
{

    protected $rules = [
        'name' => ['required'],
        'description' => ['required']
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        //owner
        $this->authorize('create', [Board::class, $project]);
        $this->validate($request, $this->rules);
        //$board = $request::createFromGlobals()->all();
        $board = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'defaultCols' => 'required',
        ]);
        $board['project_id'] = $project->id;
        $defCols = $board['defaultCols'];
        unset($board['defaultCols']);
        $board = Board::create($board);
        if($defCols){
            $colNames = array('To Do', 'In Progress', 'Done');
            foreach ($colNames as $item) {
                DB::table("columns")->insert([
                   "name" => $item,
                   "board_id" =>  $board->id,
                    "created_at" => date("Y-m-d H-i-s"),
                    "updated_at" => date("Y-m-d H-i-s")
                ]);
            }
        }
        return Redirect::route("project.show", ['project' => $project]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board)
    {
        //owner or member
        $columns = $board->columns;
        $tasks = [];
        foreach ($columns as $col){
//            $tmp = DB::table("tasks")->join("users", 'tasks.assigned_to', '=', 'users.id')->where("column_id", $col->id)->get();
            //za da se zeme i userot od relacijata
            $tmp = Task::where('column_id', $col->id)->with('assignedTo')->get();
            if(!$tmp->isEmpty()){
                foreach ($tmp as $t){
                    $tasks[] = $t;
                }
            }
        }

//        dd($tasks);

        $priorities = array_column(TaskPriority::cases(), 'value');

        $project_members = DB::table('project_member')->where('project_id', $board->project_id)->get();
        $this->authorize('view', [Board::class, $board->project, $project_members]);
        $users = [];
        foreach ($project_members as $member) {
            array_push($users, (object)[
                'user_id' => $member->user_id,
                'name' => User::find($member->user_id)->name,
                'role' => $member->role,
            ]);
        }



        return Inertia::render("Board/Show", ['project' => $board->project()->get()->first(),
            'board' => $board,
            'columns' => $columns,
            'tasks' => $tasks,
            'priorities' => $priorities,
            'members' => $users,
            'loggedInUser' => Auth::id()]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board)
    {
        //owner
        $this->authorize('update', [Board::class, $board->project]);
        $this->validate($request, $this->rules);
        $data = $request::createFromGlobals()->all();
        $board->update($data);
        return Redirect::route("project.show", ['project' => $board->project]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        //owner
        $this->authorize('delete', [Board::class, $board->project]);
        $board->delete();
        return Redirect::route("project.show", ['project' => $board->project]);
    }
}
