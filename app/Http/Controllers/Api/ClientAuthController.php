<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $client = Client::where('email', $request->email)->first();

        if (! $client || ! Hash::check($request->password, $client->password)) {
            return response()->json([
                'success' => false,
                'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
            ], 401);
        }

        if ($client->login_disabled) {
            return response()->json([
                'success' => false,
                'message' => 'حسابك غير مفعّل، تواصل مع الإدارة',
            ], 403);
        }

        // Passport token
        $token = $client->createToken('flutter-client')->accessToken;

        return response()->json([
            'success' => true,
            'role'    => 'client',
            'token'   => $token,
            'user'    => [
                'id'    => $client->id,
                'name'  => $client->name,
                'email' => $client->email,
                'phone' => $client->phone ?? null,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الخروج',
        ]);
    }

    public function me(Request $request)
    {
        $client = $request->user();

        return response()->json([
            'success' => true,
            'role'    => 'client',
            'user'    => [
                'id'    => $client->id,
                'name'  => $client->name,
                'email' => $client->email,
                'phone' => $client->phone ?? null,
            ],
        ]);
    }
}