<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        // Exclude current logged-in admin from the list
        $query->where('id', '!=', auth()->id());

        // Order: role asc (admin first), then name asc
        $users = $query->orderBy('role')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.users', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,operator,masyarakat',
        ]);

        // Cannot change own role
        if ($user->id === auth()->id()) {
            abort(403, 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', "Role {$user->name} berhasil diubah menjadi {$request->role}.");
    }

    public function toggleStatus(User $user)
    {
        // Cannot deactivate self
        if ($user->id === auth()->id()) {
            abort(403, 'Anda tidak dapat mengubah status aktif akun Anda sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->fresh()->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }
}
