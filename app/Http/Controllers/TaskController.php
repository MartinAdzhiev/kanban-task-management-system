<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TaskController extends Controller
{

    public function index()
    {
        //
    }


    public function create(Board $board, Column $column)
    {
        $priorities = array_column(TaskPriority::cases(), 'value');
        $project_id = $board->project_id;

        $project_members = DB::table('project_member')->where('project_id', $project_id)->get();
        $users = [];
        foreach ($project_members as $member) {
            array_push($users, (object)[
                'user_id' => $member->user_id,
                'name' => User::find($member->user_id)->name,
                'role' => $member->role,
            ]);
        }



        return Inertia::render('Task/Create',['column' => $column,
                                                        'priorities' => $priorities,
                                                        'members' => $users]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'column_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'deadline' => 'required',
            'priority' => ['required', Rule::in(array_column(TaskPriority::cases(), 'value'))],
            'assigned_to' => 'required',
        ]);

        Task::create($data);

        return redirect('/');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(Task $task)
    {
        $task->delete();

        return redirect('/');
    }
}
