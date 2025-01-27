<?php

namespace Modules\Profile\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ImageManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ImageManagementService $imageManagementService
    ) {}

    /**
     * Display the specified user's profile.
     */
    public function show(string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        return view('profile::show', [
            'title' => 'Profile ' . ucfirst($username),
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request, string $username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $request->validate([
            'image_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id . ',id',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id . ',id',
            'password' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $currentImagePath = $user->image_profile;

        if ($request->hasFile('image_profile')) {
            $file = $request->file('image_profile');

            $imagePath = $this->imageManagementService->uploadImage($file, [
                'currentImagePath' => $currentImagePath,
                'disk' => env('FILESYSTEM_DISK'),
                'folder' => 'uploads/user_profiles'
            ]);
            
            $user->image_profile = $imagePath;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;

        $user->save();

        return redirect()
            ->route('show.profile', $username)
            ->with('success', 'Profile updated successfully.');
    }
}