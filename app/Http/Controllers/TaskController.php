<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Models\Board;
use App\Models\Column;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        $priorities = array_column(TaskPriority::cases(), 'value');

        $query = Task::query()->where('assigned_to', Auth::id())->with('column');

        if (isset($request->priority) && ($request->priority != null)) {
            $query->where('priority', $request->priority);
        }

        if (isset($request->deadline) && ($request->deadline != null)) {
            $query->where('deadline', $request->deadline);
        }

        $tasks = $query->get();


        return Inertia::render('Task/Index', ['tasks' => $tasks,
            'priorities' => $priorities]);
    }


//    public function create(Board $board, Column $column)
//    {
//        $priorities = array_column(TaskPriority::cases(), 'value');
//        $project_id = $board->project_id;
//
//        $project_members = DB::table('project_member')->where('project_id', $project_id)->get();
//        $users = [];
//        foreach ($project_members as $member) {
//            array_push($users, (object)[
//                'user_id' => $member->user_id,
//                'name' => User::find($member->user_id)->name,
//                'role' => $member->role,
//            ]);
//        }
//
//
//
//        return Inertia::render('Task/Create',['column' => $column,
//                                                        'priorities' => $priorities,
//                                                        'members' => $users]);
//    }


    public function store(Request $request, Project $project, Board $board, Column $column)
    {
        $this->authorize('create', [Task::class, $project]);

        $is_assigned_to_owner = $request->input('is_assigned_to_owner');

        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'deadline' => ['required', 'after_or_equal:' . now()->format('Y-m-d')],
            'priority' => ['required', Rule::in(array_column(TaskPriority::cases(), 'value'))],
            'assigned_to' => Rule::requiredIf($is_assigned_to_owner == false),
        ]);

        $data['column_id'] = $column->id;

        if ($is_assigned_to_owner) {
            $data['assigned_to'] = Auth::id();
            $data['is_assigned_to_owner'] = true;
        }


        Task::create($data);

        return Redirect::route("board.show", ['board' => $board->id]);
    }


//    public function show(string $id)
//    {
//        //
//    }


//    public function edit(Task $task)
//    {
//        $priorities = array_column(TaskPriority::cases(), 'value');
//
//        $column = Column::find($task->column_id);
//        $board = Board::find($column->board_id);
//        $project_id = $board->project_id;
//
//        $project_members = DB::table('project_member')->where('project_id', $project_id)->get();
//        $users = [];
//        foreach ($project_members as $member) {
//            array_push($users, (object)[
//                'user_id' => $member->user_id,
//                'name' => User::find($member->user_id)->name,
//                'role' => $member->role,
//            ]);
//        }
//
//
//            return Inertia::render('Task/Edit', ['task' => $task,
//            'priorities' => $priorities,
//            'members' => $users,
//            'assigned_to_me' => fn() => $task->assigned_to == Auth::id()]);
//    }


    public function update(Request $request, Project $project, Board $board, Column $column, Task $task)
    {

        $this->authorize('update', [Task::class, $project]);

        $is_assigned_to_owner = $request->input('is_assigned_to_owner');


        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'deadline' => 'required',
            'priority' => ['required', Rule::in(array_column(TaskPriority::cases(), 'value'))],
            'assigned_to' => Rule::requiredIf($is_assigned_to_owner == false),
        ]);

        $deadline = $request->input('deadline');
        $data['deadline'] = Carbon::parse($deadline)->startOfDay();


        if ($data['deadline'] >= date('Y-m-d') || $data['deadline'] >= $task->deadline) {
            if ($is_assigned_to_owner) {
                $data['assigned_to'] = Auth::id();
                $data['is_assigned_to_owner'] = true;
            } else {
                if (is_array($data['assigned_to'])) {
                    $data['assigned_to'] = $data['assigned_to']['id'];
                }
                $data['is_assigned_to_owner'] = false;
            }

            $task->update($data);
        }


        return Redirect::route("board.show", ['board' => $board->id]);
    }


    public function destroy(Project $project, Board $board, Column $column, Task $task)
    {
        $this->authorize('delete', [Task::class, $project]);

        $task->delete();

        return Redirect::route("board.show", ['board' => $board->id]);
    }

    public function changeTaskInColumn(Column $column, Task $task)
    {
        $data['column_id'] = $column->id;
        $data['deadline'] = Carbon::parse($task->deadline)->startOfDay();
        $task->update($data);
        $board = $column->board_id;
        return Redirect::route("board.show", ['board' => $board]);
    }
}
