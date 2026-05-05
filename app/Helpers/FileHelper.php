<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileHelper
{
    public static function uploadFile(UploadedFile $file, $directory, $filename = null)
    {
        if (!$filename) {
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        }
        
        $path = $file->storeAs($directory, $filename, 'public');
        
        return $path;
    }
    
    public static function deleteFile($path)
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }
    
    public static function getFileUrl($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }
        
        return null;
    }
    
    public static function getFileSize($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->size($path);
        }
        
        return 0;
    }
    
    public static function getFileExtension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }
}