<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile()
    {
        return view('profile.edit');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Solo validar la foto
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'photo.required' => 'Por favor selecciona una foto',
            'photo.image' => 'El archivo debe ser una imagen',
            'photo.mimes' => 'La foto debe ser formato: jpeg, png, jpg o gif',
            'photo.max' => 'La foto no debe pesar más de 2MB'
        ]);

        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existe
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Guardar nueva foto
            $photoPath = $request->file('photo')->store('profile-photos', 'public');
            $user->photo = $photoPath;
            $user->save();

            // Redirigir a inicio con mensaje de éxito
            return redirect()->route('inicio')->with('success', 'Foto actualizada correctamente');
        }

        return redirect()->back()->with('error', 'No se pudo actualizar la foto');
    }

    public function removePhoto()
    {
        $user = Auth::user();

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->photo = null;
        $user->save();

        return response()->json(['success' => true]);
    }
}