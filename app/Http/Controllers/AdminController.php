<?php

namespace App\Http\Controllers;
use App\Http\Controllers\UserApprovedNotification;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('is_approved', false)->whereNotNull('email_verified_at')->get();
        return view('admin.users.index', compact('users'));
    }

    public function approve(User $user)
    {
        $user->update(['is_approved' => true]);
    
        $user->notify(new UserApprovedNotification());
    
        return redirect()->route('admin.users.index')->with('success', 'User approved successfully.');
    }
    
}
