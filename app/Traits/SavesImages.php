<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait SavesImages {

    public function storeImage ($value, $attribute_name, $destination_path) {
        $disk = 'local'; // or use your own disk, defined in config/filesystems.php
        // if the image was erased
        if ($value==null) {
            // delete the image from disk
            Storage::disk($disk)->delete($this->{$attribute_name});
            // set null in the database column
            return null;
        }
        // if a base64 was sent, store it in the db
        // 0. Make the image
        $image = Image::make($value)->encode('jpg', 90);
        // 1. Generate a filename.
        $filename = md5($value.time()).'.jpg';
        // 2. Store the image on disk.
        Storage::disk($disk)->put($destination_path.'/'.$filename, $image->stream());
        // 3. Save the public path to the database
        // but first, remove "public/" from the path, since we're pointing to it from the root folder
        // that way, what gets saved in the database is the user-accesible URL
        $public_destination_path = Str::replaceFirst('public/', '', $destination_path);
        return 'storage/'.$public_destination_path.'/'.$filename;
    }

}
