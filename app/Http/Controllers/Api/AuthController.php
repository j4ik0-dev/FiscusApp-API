<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register (Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
        ]);
        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'token' => $user->createToken('API TOKEN')->plainTextToken,
            'user' => $user
        ], 201);
    }
    public function login (Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }
        $user = User::where('email', $request->email)->first();
        return response()->json([
            'message' => 'Login exitoso',
            'token' => $user->createToken('API TOKEN')->plainTextToken,
            'user' => $user
        ], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}