<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile()
{
    return view('profile.edit'); // Asegúrate que esta vista exista
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
        if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }

        // Guardar nueva foto en public/images/profile-photos
        $extension = $request->file('photo')->getClientOriginalExtension();
        $filename = 'profile_' . $user->id . '_' . time() . '.' . $extension;
        
        // Crear carpeta si no existe
        $directory = public_path('images/profile-photos');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Mover el archivo
        $request->file('photo')->move($directory, $filename);
        
        // Guardar ruta relativa en la base de datos
        $user->photo = 'images/profile-photos/' . $filename;
        $user->save();

        return redirect()->route('inicio')->with('success', 'Foto actualizada correctamente');
    }

    return redirect()->back()->with('error', 'No se pudo actualizar la foto');
}

public function removePhoto()
{
    $user = Auth::user();

    // Eliminar foto del servidor
    if ($user->photo && file_exists(public_path($user->photo))) {
        unlink(public_path($user->photo));
    }

    $user->photo = null;
    $user->save();

    return response()->json(['success' => true]);
}
}