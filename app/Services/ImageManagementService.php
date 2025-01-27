<?php

namespace App\Services;

use App\Enums\EnumFileSystemDisk;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ImageManagementService
{
    /**
     * Get the public uploads path.
     * 
     * Default folder for laravel public folder is public_path($foler).
     * 
     * @param string $folder
     * @return string
     */
    protected function publicUploadsPath($folder = '')
    {
        return '../public_html/' . $folder;
    }

    /**
     * Upload an image.
     *
     * @param UploadedFile $file
     * @param array $options
     * @return string|null
     */
    public function uploadImage(UploadedFile $file, array $options = [])
    {
        $currentImagePath = $options['currentImagePath'] ?? null;
        $disk = $options['disk'] ?? EnumFileSystemDisk::PUBLIC->value;
        $folder = $options['folder'] ?? null;

        if ($disk === EnumFileSystemDisk::PUBLIC->value) {
            if ($currentImagePath && Storage::disk('public')->exists($currentImagePath)) {
                Storage::disk('public')->delete($currentImagePath);
            }

            $imagePath = $file->store($folder, 'public');
            return $imagePath;

        } elseif ($disk === EnumFileSystemDisk::PUBLIC_UPLOADS->value) {
            $directory = $this->publicUploadsPath($folder);

            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $fileName = time() . '.' . $file->extension();

            if ($currentImagePath && File::exists($this->publicUploadsPath($currentImagePath))) {
                File::delete($this->publicUploadsPath($currentImagePath));
            }

            $file->move($directory, $fileName);
            return $folder . '/' . $fileName;
        }

        return null;
    }

    /**
     * Destroy an image.
     *
     * @param string $currentImagePath
     * @param string $disk
     * @return bool
     */
    public function destroyImage($currentImagePath, $disk = EnumFileSystemDisk::PUBLIC->value)
    {
        if ($disk === EnumFileSystemDisk::PUBLIC->value) {
            if ($currentImagePath && Storage::disk('public')->exists($currentImagePath)) {
                Storage::disk('public')->delete($currentImagePath);
                return true;
            }
        } elseif ($disk === EnumFileSystemDisk::PUBLIC_UPLOADS->value) {
            if ($currentImagePath && File::exists($this->publicUploadsPath($currentImagePath))) {
                File::delete($this->publicUploadsPath($currentImagePath));
                return true;
            }
        }

        return false;
    }
}