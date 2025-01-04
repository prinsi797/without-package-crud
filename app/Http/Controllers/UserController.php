<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller {
    public function index() {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create() {
        return view('users.create');
    }

    public function store(Request $request) {
        // dd($request->all());
        // die;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:user,admin',
            'images.*' => 'image',
        ]);

        // dd($request->all());
        // die;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filePath = $image->store('images', 'public'); // Save in public/storage/images
                $user->images()->create(['file_path' => $filePath]); // Save in the database
            }
        }


        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function show(User $user) {
        return view('users.show', compact('user'));
    }

    public function edit(User $user) {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // Handle image updates
        if ($request->hasFile('images')) {
            // Optional: Delete old images from storage
            foreach ($user->images as $oldImage) {
                if (Storage::exists('public/' . $oldImage->file_path)) {
                    Storage::delete('public/' . $oldImage->file_path);
                }
                $oldImage->delete(); // Remove record from the database
            }

            // Save new images
            foreach ($request->file('images') as $image) {
                $filePath = $image->store('images', 'public'); // Save in public/storage/images
                $user->images()->create(['file_path' => $filePath]); // Save path in the database
            }
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user) {
        $user->delete(); // Soft delete the user
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
