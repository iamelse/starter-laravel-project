<?php

use App\Enums\EnumFileSystemDisk;
use Illuminate\Support\Facades\Storage;

if (!function_exists('getUserImageProfilePath')) {
    function getUserImageProfilePath($user)
    {
        $disk = env('FILESYSTEM_DISK');
        $placeholderUrl = 'https://placehold.co/150';
        $appUrl = rtrim(env('APP_URL'), '/'); // Ensure there's no trailing slash in APP_URL
        $publicHtmlPath = base_path('../public_html'); // Define the relative path to public_html

        // Check for the PUBLIC disk
        if ($disk === EnumFileSystemDisk::PUBLIC->value) {
            if ($user->image_profile && Storage::disk('public')->exists($user->image_profile)) {
                return asset('storage/' . $user->image_profile);  // Use the existing logic for PUBLIC
            }
        } 
        // Check for the PUBLIC_UPLOADS disk
        elseif ($disk === EnumFileSystemDisk::PUBLIC_UPLOADS->value) {
            // Directly using the uploads path with APP_URL
            $filePath = $user->image_profile;
            $fullPath = $publicHtmlPath . '/' . $filePath; // Combine with the actual public_html path
            if ($user->image_profile && file_exists($fullPath)) {
                return $appUrl . '/' . $filePath;  // Use APP_URL to generate the full URL for PUBLIC_UPLOADS
            }
        }

        return $placeholderUrl;
    }
}