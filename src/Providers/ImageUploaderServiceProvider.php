<?php

namespace ImageUploader\Providers;

use Illuminate\Support\ServiceProvider;
use ImageUploader\ImageUploader;

class ImageUploaderServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton('image-uploader', function () {
            return new ImageUploader();
        });
    }

    public function boot() {}
}
