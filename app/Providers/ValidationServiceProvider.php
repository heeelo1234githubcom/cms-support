<?php

namespace App\Providers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot()
    {
        /**
         * validate old password
         */
        \Validator::extend('validateOldPassword', function($attribute, $value, $parameters, $validator) {

            /* @var $currentUser User */
            $currentUser = auth()->user();

            if ( !$currentUser->password) {
                return true;
            }

            return $value && \Hash::check($value, $currentUser->password);

        }, 'Mật khẩu cũ không chính xác.');

        /**
         * validate youtube video ID
         */
        \Validator::extend('validateVideoId', function($attribute, $value, $parameters, $validator) {

            $youtubeUrl = 'https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $value;
            $client = new Client();

            try {
                return (200 == $client->request('GET', $youtubeUrl, [
                    'verify' => false,
                    'header' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ]
                ])->getStatusCode());
            } catch (\Exception $e) {
                return (200 == $e->getCode());
            }

        }, 'Video ID không chính xác, phải là video ID của Youtube.');
    }

    /**
     * Register any application services.
     * @return void
     */
    public function register() {}
}
