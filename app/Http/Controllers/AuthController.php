<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)   
    {   
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|min:8'
            ]);

            $user = User::create( [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Enkripsi password
            ]);

            return response()->json([
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken
            ],);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
    return response()->json([
        'message' => 'Validasi gagal',
        'errors' => $e->errors()
    ], 422);

    
    }catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ],500);
        }

}

   public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

/** @var \App\Models\User $user */
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken; // Error syntax akan hilang  

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
}
}