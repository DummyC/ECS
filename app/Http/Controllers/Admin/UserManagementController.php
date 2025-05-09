<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('settings', compact('users', 'search'));
    }

    public function toggleAdmin(Request $request, User $user)
    {
        $user->is_admin = !$user->is_admin;
        $user->save();

        return response()->json(['success' => true, 'is_admin' => $user->is_admin]);
    }
}
