<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait ImageStorageTrait
{
    public function storeImage($path, $file)
    {
        return Storage::disk('public')->putFile(path: $path, file: $file);
    }

    public function deleteImage($path)
    {
        if (File::exists(public_path('storage/' . $path))) {
            File::delete(public_path('storage/' . $path));
        }
    }
}