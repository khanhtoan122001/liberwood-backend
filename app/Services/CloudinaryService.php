<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CloudinaryService
{
    public static function uploadImage($image)
    {
        $upload = Cloudinary::upload($image->getRealPath())->getSecurePath();
        return $upload;
    }
}

?>