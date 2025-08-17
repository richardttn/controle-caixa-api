<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Teller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TellerController extends Controller
{
    public function index()
    {
        $tellers = Teller::with('users', 'transactions')->get();
        return response()->json($tellers);
    }

    public function show($id)
    {
        $teller = Teller::with('users', 'transactions')->findOrFail($id);
        return response()->json($teller);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required|integer|min:1',
            'total_amount' => 'nullable|numeric|min:0',
            'today_transaction_nr' => 'nullable|integer|min:0',
            'last_reset_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $teller = Teller::create([
            'id' => Str::random(15),
            'number' => $request->number,
            'total_amount' => $request->total_amount ?? 0,
            'today_transaction_nr' => $request->today_transaction_nr ?? 0,
            'last_reset_at' => $request->last_reset_at,
        ]);

        return response()->json($teller, 201);
    }

    public function update(Request $request, $id)
    {
        $teller = Teller::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'number' => 'sometimes|integer|min:1',
            'total_amount' => 'sometimes|numeric|min:0',
            'today_transaction_nr' => 'sometimes|integer|min:0',
            'last_reset_at' => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $teller->update($request->only([
            'number',
            'total_amount',
            'today_transaction_nr',
            'last_reset_at'
        ]));

        return response()->json($teller);
    }

    public function destroy($id)
    {
        $teller = Teller::findOrFail($id);
        $teller->delete();

        return response()->json(['message' => 'Teller deleted successfully']);
    }
}
