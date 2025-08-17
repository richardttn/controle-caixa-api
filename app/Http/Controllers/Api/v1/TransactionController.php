<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Transaction::with(['user', 'teller', 'transactionDenominations.denomination']);

        if (!in_array($user->role, ['admin', 'manager', 'treasurer'])) {
            $query->where('user_id', $user->id);
        }

        $transactions = $query->latest()->get();
        return response()->json($transactions);
    }

    public function show($id)
    {
        $transaction = Transaction::with(['user', 'teller', 'transactionDenominations.denomination'])
            ->findOrFail($id);
        return response()->json($transaction);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'nullable|string',
            'client_code' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'type' => 'required|in:poupança,depósito,levantamento,desembolso,ajuste',
            'teller_id' => 'nullable|exists:tellers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction = Transaction::create([
            'id' => Str::random(15),
            'client_name' => $request->client_name,
            'client_code' => $request->client_code,
            'amount' => $request->amount,
            'type' => $request->type,
            'user_id' => $request->user()->id,
            'teller_id' => $request->teller_id,
        ]);

        return response()->json($transaction->load(['user', 'teller']), 201);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'client_name' => 'sometimes|nullable|string',
            'client_code' => 'sometimes|nullable|string',
            'amount' => 'sometimes|nullable|numeric',
            'type' => 'sometimes|in:poupança,depósito,levantamento,desembolso,ajuste',
            'teller_id' => 'sometimes|nullable|exists:tellers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction->update($request->only([
            'client_name',
            'client_code',
            'amount',
            'type',
            'teller_id'
        ]));

        return response()->json($transaction->load(['user', 'teller']));
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully']);
    }
}
