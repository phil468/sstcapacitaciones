<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function index()
    {
        return Cargo::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean'
        ]);

        $cargo = Cargo::create($request->all());

        return response()->json($cargo, 201);
    }

    public function show($id)
    {
        $cargo = Cargo::findOrFail($id);
        return response()->json($cargo, 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean'
        ]);

        $cargo = Cargo::findOrFail($id);
        $cargo->update($request->all());

        return response()->json($cargo, 200);
    }

    public function destroy($id)
    {
        Cargo::destroy($id);
        return response()->json(null, 204);
    }
}