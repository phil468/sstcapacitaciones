<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gabinete;
use Illuminate\Http\Request;

class GabineteController extends Controller
{
    public function index()
    {
        return Gabinete::all();
    }

    public function store(Request $request)
    {
        $gabinete = Gabinete::create($request->all());
        return response()->json($gabinete, 201);
    }

    public function show($id)
    {
        return Gabinete::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $gabinete = Gabinete::findOrFail($id);
        $gabinete->update($request->all());
        return response()->json($gabinete, 200);
    }

    public function destroy($id)
    {
        Gabinete::destroy($id);
        return response()->json(null, 204);
    }
}