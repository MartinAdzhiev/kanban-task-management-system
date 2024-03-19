<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        $priorities = array_column(TaskPriority::cases(), 'value');

        $query = Task::query()->where('assigned_to', Auth::id());

        if (isset($request->priority) && ($request->priority != null))
        {
            $query->where('priority', $request->priority);
        }

        if (isset($request->deadline) && ($request->deadline != null))
        {
            $query->where('deadline', $request->deadline);
        }

        $tasks = $query->get();


        return Inertia::render('Task/Index', ['tasks' => $tasks,
                                                        'priorities' => $priorities]);
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
        $assign_to_me = $request->input('assign_to_me');

        $data = $request->validate([
            'column_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'deadline' => ['required', 'after_or_equal:' . now()->format('Y-m-d')],
            'priority' => ['required', Rule::in(array_column(TaskPriority::cases(), 'value'))],
            'assigned_to' => Rule::requiredIf($assign_to_me == false),
        ]);

        if ($assign_to_me) {
            $data['assigned_to'] = Auth::id();
        }


        Task::create($data);

        return redirect('/task');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(Task $task)
    {
        $priorities = array_column(TaskPriority::cases(), 'value');

        $column = Column::find($task->column_id);
        $board = Board::find($column->board_id);
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


            return Inertia::render('Task/Edit', ['task' => $task,
            'priorities' => $priorities,
            'members' => $users,
            'assigned_to_me' => fn() => $task->assigned_to == Auth::id()]);
    }


    public function update(Request $request, Task $task)
    {
        $assign_to_me = $request->input('assign_to_me');

        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'deadline' => ['required', 'after_or_equal:' . now()->format('Y-m-d')],
            'priority' => ['required', Rule::in(array_column(TaskPriority::cases(), 'value'))],
            'assigned_to' => Rule::requiredIf($assign_to_me == false),
        ]);

        if ($assign_to_me) {
            $data['assigned_to'] = Auth::id();
        }

        $task->update($data);

        return redirect('/');
    }


    public function destroy(Task $task)
    {
        $task->delete();

        return redirect('/');
    }
}
