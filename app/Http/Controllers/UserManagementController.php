<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\UserApprovedNotification;

class UserManagementController extends Controller
{
    // Show pending user approvals
    public function showPendingApprovals()
    {
        $users = User::where('is_approved', false)->get();
        return view('admin.users.approve', compact('users'));
    }

    // Approve a user
    public function approve(User $user)
    {
        $user->is_approved = true;
        $user->save();
    
        // Send notification
        $user->notify(new UserApprovedNotification());
    
        return redirect()->route('admin.users.approve')->with('success', 'User approved successfully.');
    }

    // Show inactive users
    public function showInactiveUsers()
    {
        $users = User::where('status', 'inactive')->get();
        return view('admin.users.activate', compact('users'));
    }

    // Activate a user
    public function activate(User $user)
    {
        $user->status = 'active';
        $user->save();

        return redirect()->route('admin.users.activate')->with('success', 'User activated successfully.');
    }

    // Show active users
    public function showActiveUsers()
    {
        $users = User::where('status', 'active')->get();
        return view('admin.users.deactivate', compact('users'));
    }

    // Deactivate a user

    public function deactivate(User $user)
{
    // Logic to deactivate the user
    $user->status = 'inactive'; // Example; modify based on your actual status field
    $user->save();

    return redirect()->route('admin.users.deactivate')->with('success', 'User has been deactivated.');
}

    // Show edit form for a user
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function listUsers()
{
    $users = User::where('email', '!=', 'sayahsamson@gmail.com')->get();// Fetch all users
    return view('admin.users.edit-list', compact('users')); // Load the list view
}


    // Update user details
    public function update(Request $request, User $user)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'usertype' => 'required|in:admin,editor,attorney,user', // Add role validation
        ]);
    
        // Update user fields
        $user->name = $request->name;
        $user->email = $request->email;
    
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
    
        $user->usertype = $request->usertype; // Update role
    
        $user->save();
    
        // Redirect back with a success message
        return redirect()->route('admin.users.edit', $user->id)->with('success', 'User updated successfully!');
    }
    
    

    // Show all users for deletion
    public function showAllUsers()
    {
        $users = User::where('email', '!=', 'sayahsamson@gmail.com')->get();
        return view('admin.users.delete', compact('users'));
    }

    // Delete a user
    public function delete(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.delete')->with('success', 'User deleted successfully.');
    }
}
