<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function update(Request $request){
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return response()->json([
            'message' => 'Perfil actualizado',
            'user' => $user,
            'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) : null
        ]);
    }
}
