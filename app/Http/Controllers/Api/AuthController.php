<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Capacitacione;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{      

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            $token = JWTAuth::fromUser($user);
            // dd( $user->permissions());
            $user->permissions = $user->roles()->with('permissions')->get()->pluck('permissions')->flatten()->pluck('name')->unique();

            // $token = $user->createToken('authToken')->accessToken;
            return response()->json(['token' => $token, 'user' => $user], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $user->permissions = $user->getAllPermissions()->pluck('name');
        return response()->json($user);
    }

    public function refreshToken($user)
    {
        // dd($user);
        try {
            $user = User::find($user->id);
            if ($user == null) {
                return response()->json(['error' => 'Usuario no encontrado'], 401);
            } 

            if ($user->estado == 0) {
                return response()->json(['error' => 'Usuario inactivo'], 401);
            }

            $token = JWTAuth::fromUser($user);
            $response['token'] = $token;
            $response['user'] = $user;
        
            return response()->json($response, 200);
        } catch (Exception $ex) {

            return response()->json(['error' => 'No se pudo refrescar el token /n'.$ex], 401);
        }
        // return response()->json(['message' => 'Unauthorized'], 401);

        // return $this->respondWithToken(JWTAuth::refresh());

        // try {
        //     if (!$token = JWTAuth::parseToken()->refresh()) {
        //         return response()->json(['error' => 'No se pudo refrescar el token'], 401);
        //     }
        // } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        //     return response()->json(['error' => 'Token inválido'], 401);
        // } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        //     return response()->json(['error' => 'Token expirado'], 401);
        // } catch (Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
        //     return response()->json(['error' => 'Token en lista negra'], 401);
        // }

        // return response()->json(compact('token'));

    }

}
