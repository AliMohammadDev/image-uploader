<?php

namespace ImageUploader\Providers;

use Illuminate\Support\ServiceProvider;
use ImageUploader\ImageUploader;

class ImageUploaderServiceProvider extends ServiceProvider
{

    // public function register()
    // {
    //     $this->app->singleton('image-uploader', function () {
    //         return new ImageUploader();
    //     });
    // }
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/imageupload.php', 'imageupload');

        $this->app->singleton('image-uploader', function ($app) {
            $config = $app['config']->get('imageupload');

            return new ImageUploader(
                $config['max_size'],
                $config['allowed_mime_types'],
                $config['disk']
            );
        });
    }


    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/imageupload.php' => config_path('imageupload.php'),
        ], 'config');
    }
}
