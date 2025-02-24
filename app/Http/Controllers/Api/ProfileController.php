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
    public function index(Request $request)
    {        
        $query = User::where('email_verified_at', '!=', null);
    
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }
    
        // Filtering berdasarkan role (opsional)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
    
        // Ambil data dengan pagination
        $users = $query->paginate(10);
    
        return response()->json($users);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}


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

            $roles = auth()->user->roles;

            // Log::info('All Request Data:', $request->all()); 
            // Log::info('All Files:', $request->allFiles()); 
            if ($request->hasFile('profile_image')) {
                $profileImage = $request->file('profile_image');
                $profileImageName = 'profile_' . '_' . time() . '.' . $profileImage->getClientOriginalExtension();
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
        $profile = User::find($id);
        return response()->json($profile);

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
        $profile = User::find($id);
        $profile->update($request->all());
        return response()->json($profile);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
