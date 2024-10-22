<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function showImage($filename, $access_code = null)
    {
        if (auth()->check()) {
            # Path to the image in storage
            $path = storage_path('app/' . $filename);

            # Check if the file exists
            if (Storage::exists($path)) {
                abort(404, 'Image Not Found'); # Return 404 if image is not found
            }

            # Return the file as a response
            return response()->file($path);

        } elseif (isset($access_code) && Code::where('access_code', $access_code)->where('is_accessible', 'yes')->exists()) {
            # Path to the image in storage
            $path = storage_path('app/' . $filename);

            # Check if the file exists
            if (Storage::exists($path)) {
                abort(404, 'Image Not Found'); # Return 404 if image is not found
            }

            # Return the file as a response
            return response()->file($path);
        }

        abort(403, 'Unauthorized Access');
    }
}
