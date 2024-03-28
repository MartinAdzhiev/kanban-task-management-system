<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ColumnController extends Controller
{

//    public function index()
//    {
//        //
//    }


//    public function create(Board $board)
//    {
//        return Inertia::render('Column/Create', ['board' => $board]);
//    }


    public function store(Request $request, Project $project, Board $board)
    {
        $permission = $this->authorize('create', [Column::class, $project]);

        $data = $request->validate([
            'name' => 'required',
        ]);

        $data['board_id'] = $board->id;

        Column::create($data);

        return Redirect::route("board.show", ['board' => $board->id]);
    }

//
//    public function show(string $id)
//    {
//        //
//    }


//    public function edit(Column $column)
//    {
//       return Inertia::render('Column/Edit', ['column' => $column]);
//    }


    public function update(Request $request, Project $project, Board $board, Column $column)
    {
        $this->authorize('update', [Column::class, $project]);

        $data = $request->validate([
            'name' => 'required',
        ]);

        $column->update($data);

        return Redirect::route("board.show", ['board' => $board->id]);
    }


    public function destroy(Project $project, Board $board, Column $column)
    {
        $this->authorize('delete', [Column::class, $project]);

        $column->delete();

        return Redirect::route("board.show", ['board' => $board->id]);
    }
}
