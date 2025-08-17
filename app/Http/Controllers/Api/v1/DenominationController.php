<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Denomination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DenominationController extends Controller
{
    public function index()
    {
        $denominations = Denomination::all();
        return response()->json($denominations);
    }

    public function show($id)
    {
        $denomination = Denomination::findOrFail($id);
        return response()->json($denomination);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $denomination = Denomination::create([
            'id' => Str::random(15),
            'value' => $request->value,
        ]);

        return response()->json($denomination, 201);
    }

    public function update(Request $request, $id)
    {
        $denomination = Denomination::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'value' => 'sometimes|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $denomination->update($request->only(['value']));

        return response()->json($denomination);
    }

    public function destroy($id)
    {
        $denomination = Denomination::findOrFail($id);
        $denomination->delete();

        return response()->json(['message' => 'Denomination deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deestroy(Denomination $denomination)
    {
        //
    }
}
