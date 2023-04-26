<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthenticateApi extends Middleware
{
    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        $token = $request->bearerToken();
        if (in_array($token , config('apitokens'), true)) return;
        //$this->unauthenticated($request, $guards);
        throw new HttpResponseException(response()->json([
            'message' => 'Unauthenticated user'
        ], 401));
    }
}