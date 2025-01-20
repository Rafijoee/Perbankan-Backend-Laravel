<?php

namespace App\Http\Controllers\Api;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $validation = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6|confirmed'
            ]);

            $validation['password'] = bcrypt($request->password);
            $user = User::create($validation);



            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }


    public function verifyAccount(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'two_factor_token' => 'required|numeric',
        ]);

        try {
            // Validasi token JWT
            $payload = JWTAuth::parseToken()->getPayload();

            // Ambil data dari payload
            $email = $payload->get('email');
            $expectedTwoFactorToken = $payload->get('two_factor_token');

            // Cocokkan kode 2FA
            if ($expectedTwoFactorToken != $request->two_factor_token) {
                return response()->json(['message' => 'Invalid 2FA token'], 400);
            }

            // Tandai akun sebagai terverifikasi
            $user = User::where('email', $email)->first();
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user->email_verified_at = now();
            $user->save();

            return response()->json(['message' => 'Account verified successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
