<?php


namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!in_array($user->role, ['admin', 'manager', 'treasurer'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $users = User::with('teller')->get();
        return response()->json($users);
    }

    public function show(Request $request, $id)
    {
        $authUser = $request->user();
        
        if (!in_array($authUser->role, ['admin', 'manager', 'treasurer']) && $authUser->id !== $id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::with('teller')->findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:8',
            'fullname' => 'required|string|max:255',
            'role' => 'nullable|in:manager,admin,teller,treasurer',
            'teller_id' => 'nullable|exists:tellers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'id' => Str::random(15),
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'token_key' => Str::random(50),
            'fullname' => $request->fullname,
            'role' => $request->role,
            'teller_id' => $request->teller_id,
        ]);

        return response()->json($user->load('teller'), 201);
    }

    public function update(Request $request, $id)
    {
        $authUser = $request->user();
        
        if ($authUser->id !== $id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|max:255|unique:users,username,' . $id,
            'email' => 'sometimes|nullable|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'fullname' => 'sometimes|string|max:255',
            'role' => 'sometimes|nullable|in:manager,admin,teller,treasurer',
            'teller_id' => 'sometimes|nullable|exists:tellers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['username', 'email', 'fullname', 'role', 'teller_id']);
        
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json($user->load('teller'));
    }

    public function destroy(Request $request, $id)
    {
        $authUser = $request->user();
        
        if ($authUser->id !== $id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
