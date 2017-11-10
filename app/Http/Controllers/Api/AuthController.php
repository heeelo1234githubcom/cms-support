<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * Class AuthController
 * @package App\Http\Contronllers\Api
 */
class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $throttles = in_array(
            ThrottlesLogins::class, class_uses_recursive(get_class($this))
        );

        /**
         * check login attempts
         */
        if ($throttles && $this->hasTooManyLoginAttempts($request)) {

            return response()->json([
                'error' => trans('auth.throttle')
            ], 400);
        }

        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        try {
            
            // attempt to verify the credentials and create a token for the user
            if ( !$token = JWTAuth::attempt($credentials)) {

                /**
                 * increment login attempts
                 */
                if ($throttles) {
                    $this->incrementLoginAttempts($request);
                }
                
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

        } catch (JWTException $e) {

            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user = JWTAuth::authenticate($token)->withInfo();

        /**
         * clear login attempts
         */
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }
        
        // all good so return the token
        return response()->json(compact('token', 'user'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken()
    {
        $token = JWTAuth::getToken();
        $refreshToken = JWTAuth::refresh($token);

        return response()->json([
            'token' => $refreshToken
        ]);
    }

    /**
     * @return mixed
     */
    public function me()
    {
        return JWTAuth::parseToken()->authenticate()->withInfo();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        $token = JWTAuth::getToken();
        JWTAuth::setToken($token)->invalidate();

        return response()->json([
            'success' => true
        ]);
    }
}
