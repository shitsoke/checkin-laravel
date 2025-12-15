<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        $query = User::with('profile');

        if (request()->filled('q')) {
            $q = trim(request('q'));
            $query->where(function ($sub) use ($q) {
                $sub->where('email', 'LIKE', "%{$q}%")
                    ->orWhere('first_name', 'LIKE', "%{$q}%")
                    ->orWhere('last_name', 'LIKE', "%{$q}%")
                    ->orWhere(DB::raw("CONCAT_WS(' ', first_name, middle_name, last_name)"), 'LIKE', "%{$q}%")
                    ->orWhereHas('profile', function ($p) use ($q) {
                        $p->where('display_name', 'LIKE', "%{$q}%");
                    });
            });
        }

        if (request()->filled('role')) {
            $query->where('role', request('role'));
        }

        if (request()->filled('status')) {
            $status = request('status');
            if ($status === 'banned') {
                $query->where('is_banned', true);
            } elseif ($status === 'active') {
                $query->where('is_banned', false);
            }
        }

        $users = $query->orderByDesc('id')->paginate(12)->withQueryString();
        $roles = User::select('role')->distinct()->pluck('role')->filter();

        return view('admin.manage_users', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        $user->load('profile');

        return view('admin.user_show', compact('user'));
    }

    public function ban(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        if ($user->role === 'admin') {
            return back()->with('error', 'You cannot ban an admin account.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot ban your own account.');
        }

        $user->is_banned = true;
        $user->save();

        return back()->with('success', 'User has been banned successfully.');
    }

    public function unban(User $user)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);

        $user->is_banned = false;
        $user->save();

        return back()->with('success', 'User has been unbanned successfully.');
    }
}

