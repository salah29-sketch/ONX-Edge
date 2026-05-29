<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\User as AdminUser;
use App\Models\Client\Client;
use App\Models\Worker\Worker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $email    = $request->input('email');
        $password = $request->input('password');

        $user = null;
        $type = null;

        $admin = AdminUser::where('email', $email)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            $user = $admin;
            $type = 'admin';
        }

        if (!$user) {
            $client = Client::where('email', $email)->first();
            if ($client && $client->password && !$client->login_disabled && Hash::check($password, $client->password)) {
                $user = $client;
                $type = 'client';
            }
        }

        if (!$user) {
            $worker = Worker::where('email', $email)->where('is_active', true)->first();
            if ($worker && Hash::check($password, $worker->password)) {
                $user = $worker;
                $type = 'worker';
            }
        }

        if (!$user) {
            return response()->json(['error' => 'بيانات الدخول غير صحيحة'], 401);
        }

        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'token' => $token,
            'type'  => $type,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }
}
