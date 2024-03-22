<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ColumnController extends Controller
{

    public function index()
    {
        //
    }


    public function create(Board $board)
    {
//        return Inertia::render('Column/Create', ['board' => $board]);
    }


    public function store(Request $request, Board $board)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);

        $data['board_id'] = $board->id;

        Column::create($data);

        return Redirect::route("board.show", ['board' => $board->id]);
    }


    public function show(string $id)
    {
        //
    }


    public function edit(Column $column)
    {
//        return Inertia::render('Column/Edit', ['column' => $column]);
    }


    public function update(Request $request, Column $column)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);

        $column->update($data);

        return Redirect::route("board.show", ['board' => $column->board]);
    }


    public function destroy(Column $column)
    {
        $column->delete();

        return Redirect::route("board.show", ['board' => $column->board]);
    }
}
