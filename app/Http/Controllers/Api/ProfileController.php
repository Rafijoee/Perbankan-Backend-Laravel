<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // $user = auth()->user();
            // log::info("ini usernyaaaaa", ["user" => $user]);
            return response()->json([
                'message' => 'Welcome to the profile page',
                // 'data' => $user
            ], 200);
        } catch (\Exception $e) {
            Log::info("message", ["error" => $e->getMessage()]);
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    public function search (Request $request) {
        $users = User::query()
        ->when($request->search, fn($q) => $q->search($request->email))
        ->when($request->role, fn($q) => $q->role($request->name))
        ->paginate(10);

    return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                // 'active_letter' => 'required|mimes:pdf|max:2048',
            ]);

            $roles = auth()->user()->roles;

            // Log::info('All Request Data:', $request->all()); 
            // Log::info('All Files:', $request->allFiles()); 
            if ($request->hasFile('profile_image')) {
                $profileImage = $request->file('profile_image');
                $profileImageName = 'profile_' . auth()->id() . '_' . time() . '.' . $profileImage->getClientOriginalExtension();
                $profileImagePath = $profileImage->storeAs('profile_images' . $roles, $profileImageName, 'public');
            }                        

            return response()->json([
                'message' => 'File uploaded successfully',
                'data' => $request
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
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
