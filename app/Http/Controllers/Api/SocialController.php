<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use \Socialite;
use App\Models\User;

class SocialController extends Controller
{
    /**
     * Redirect the user to the Google|Facebook authentication page.
     * @param $type
     * @return mixed
     */
    public function redirectToProvider($type)
    {
        if ( !in_array($type, ['google', 'facebook'])) {
            abort(404);
        }

        return Socialite::driver($type)->redirect();
    }

    /**
     * @param $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function handleProviderCallback($type)
    {
        if ( !in_array($type, ['google', 'facebook'])) {
            abort(404);
        }

        /* @var $repository UserRepository */
        $repository = app('user');
        
        try {

            $socialiteUser = Socialite::driver($type)->user();

            /* @var User $user */
            if ( !$user = $repository->getByColumns(['email' => $socialiteUser->email])) {

                $user = User::create([
                    'email' => $socialiteUser->email,
                    'name' => $socialiteUser->name,
                    'password' => '',
                    'status' => 'enable'
                ]);

                /**
                 * add user info
                 */
                $user->addInfo([
                    'gender' => $socialiteUser->user['gender'],
                    'avatar' => $socialiteUser->avatar
                ]);
            }

            if ($user) {

                try {

                    /**
                     * get user token
                     */
                    $token = \JWTAuth::fromUser($user);

                } catch (\Exception $e) {
                    abort(500, trans('error.could_not_create_token'));
                }

                /**
                 * check user permission
                 */
                if ($user->isNormalUser()) {
                    abort(403, trans('error.access_denied'));
                }
            }

            return view('backend.socialite_callback', compact('token'));

        } catch (\Exception $e) {
            abort(404, trans('error.user_not_found'));
        }
    }
}
