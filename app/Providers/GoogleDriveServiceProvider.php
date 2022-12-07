<?php

namespace App\Providers;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Storage::extend('google', function($app, $config) {
            $pr = Http::post('https://oauth2.googleapis.com/token',[
                'client_id' => '1050410147847-m4g5t6son5eea9ppr9ll6acm9rp68je9.apps.googleusercontent.com',
                'client_secret' =>  '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',
                'grant_type' => 'refresh_token',
                'refresh_token' => '1//04-3aseMU6FFZCgYIARAAGAQSNwF-L9IrwzY_yYzDwSkFaIsaySWDQg1d8PmJe4L6SNmgN3q57OI2N1uFBt5EdFvYaOlifwfQT-0',
                'scope' => 'https://www.googleapis.com/auth/drive'
            ]);

            $client = new \Google_Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($pr->json()['access_token']);
            $service = new \Google_Service_Drive($client);
            $adapter = new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter($service, $config['folderId']);

            return new \League\Flysystem\Filesystem($adapter);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}