<?php

namespace ImageUploader\Facades;

use Illuminate\Support\Facades\Facade;

class ImageUploader extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'image-uploader';
    }
}
