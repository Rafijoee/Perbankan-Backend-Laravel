<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

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

            $token2fa = random_int(100000, 999999);
            $expired = now()->addMinutes(5);

            $user->two_fa_expired_at = $expired;
            $user->two_fa = $token2fa;
            $user->save();
            try{
                Mail::send(
                    'mails.VerifikasiAccount',
                    ['token' => $token2fa, 'name' => $user->name],
                    function ($message) use ($user) {
                        $message->to($user->email, $user->name)->subject('Verifikasi Account');
                    }
                );
            }catch (\Exception $e)
            {
                $user->delete();
                return response()->json([
                    'message' => 'Error: ' . $e->getMessage()
                ], 400);
            }
            DB::commit();
            return response()->json([
                "message" => "Succes",
                "data" => [
                    "user" => $user,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    public function login(Request $request)
    {
        try {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at == null) {
            return response()->json([
                'message' => 'Email not verified'
            ], 403);
        }

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = JWTAuth::attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    catch (\Exception $e) {
        Log::info("message: " . $e->getMessage());
        return response()->json([
            'message' => 'Error: ' . $e->getMessage()
        ], 400);
    }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function verifyAccount(Request $request)
    {
        try {
            $validation = $request->validate([
                'email' => 'required',
                'token' => 'required|numeric'
            ]);

            $user = User::where('email', $validation['email'])->first();

            if(!$user)
            {
                return response()->json([
                    'message' => 'User not found'
                ], 403);
            }

            if($validation['token'] != $user->two_fa)
            {
                return response()->json([
                    'message' => 'Token not match'
                ], 403);
            }

            if($user->two_fa_expired_at < now())
            {
                return response()->json([
                    'message' => 'Token expired'
                ], 403);
            }

            $user->two_fa = null;
            $user->two_fa_expired_at = null;
            $user->email_verified_at = now();
            $user->save();
            return response()->json([
                "message" => "Succes",
                "data" => $user
            ], 200);

        }catch(\Exception $e)
        {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
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
