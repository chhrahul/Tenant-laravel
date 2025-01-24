<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class UserManagementController extends Controller
{
    public function index(){
        return view('user-management');
    }
    public function getUserData(){
        $data = User::all();
        return DataTables::of($data)->make(mDataSupport: true);
    }
    public function getUserDataById($id)
    {
        $data = User::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function updateUserData(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|in:admin,user',
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $validatedData['name'];
        $user->role = $validatedData['role'];
        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }
        $user->save();

        return response()->json(['success' => true, 'message' => 'User updated successfully!']);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}
