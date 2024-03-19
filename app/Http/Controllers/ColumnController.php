<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ColumnController extends Controller
{

    public function index()
    {
        //
    }


    public function create(Board $board)
    {
        return Inertia::render('Column/Create', ['board' => $board]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'board_id' => 'required',
            'name' => 'required',
        ]);

        Column::create($data);

        return redirect('/');
    }


    public function show(string $id)
    {
        //
    }


    public function edit(Column $column)
    {
        return Inertia::render('Column/Edit', ['column' => $column]);
    }


    public function update(Request $request, Column $column)
    {
        $data = $request->validate([
            'name' => 'required',
        ]);

        $column->update($data);

        return redirect('/');
    }


    public function destroy(Column $column)
    {
        $column->delete();

        return redirect('/');
    }
}
