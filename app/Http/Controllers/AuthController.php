<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    // ==================== LOGIN ====================
    
    /**
     * Mostrar formulario de login
     */
    public function showLogin()
    {
        return view('auth.login');
    }
    
    /**
     * Procesar inicio de sesión (SOLO POR NOMBRE DE USUARIO)
     */
    public function login(Request $request)
    {
        $username = $request->input('email'); // Mantenemos el nombre del campo pero ahora es usuario
        $password = $request->input('password');
        
        // Validar campos vacíos
        $errors = [];
        
        if (empty($username)) {
            $errors['email'] = 'El nombre de usuario es obligatorio.';
        }
        
        if (empty($password)) {
            $errors['password'] = 'La contraseña es obligatoria.';
        }
        
        if (!empty($errors)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors($errors);
        }
        
        // Validar longitud mínima de contraseña
        if (strlen($password) < 6) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'La contraseña debe tener al menos 6 caracteres.']);
        }
        
        // Buscar SOLO por nombre de usuario (no por correo)
        $usuario = Usuario::where('name', $username)->first();
        
        // VALIDACIÓN ESPECÍFICA: Verificar qué está mal exactamente
        
        // Caso 1: El usuario NO existe
        if (!$usuario) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'El nombre de usuario es incorrecto o no esta registrado.',
                    'password' => 'La contraseña es incorrecta.'
                ]);
        }
        
        // Caso 2: El usuario SÍ existe pero la contraseña es incorrecta
        if (!Hash::check($password, $usuario->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'password' => 'La contraseña es incorrecta.'
                ]);
        }
        
        // Si llegamos aquí, todo está correcto
        // Loguear usuario
        Auth::login($usuario);
        session(['usuario_id' => $usuario->id]);
        
        // Redirigir según rol
        if ($usuario->hasRole('administrador')) {
            return redirect()->route('inicio');
        } elseif ($usuario->hasRole('medico')) {
            return redirect()->route('medicos.index');
        } elseif ($usuario->hasRole('usuario')) {
            return redirect()->route('inicio');
        } elseif ($usuario->hasRole('empleado')) {
            return redirect()->route('empleados.index');
        } else {
            return redirect()->route('inicio');
        }
    }
    
    // ==================== RECUPERAR CONTRASEÑA ====================
    
    /**
     * Mostrar formulario de recuperación de contraseña
     */
    public function showForgotPassword(Request $request)
    {
        $email = $request->query('email', '');
        return view('auth.passwords.forgot', compact('email'));
    }
    
    /**
     * Enviar enlace de recuperación por correo electrónico
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email'
        ], [
            'username.required' => 'El nombre de usuario es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.'
        ]);
        
        // ⭐ Buscar usuario por NOMBRE DE USUARIO
        $usuario = Usuario::where('name', $request->username)->first();
        
        if (!$usuario) {
            return back()->withErrors([
                'username' => 'No se encontró ningún usuario con ese nombre.'
            ])->withInput();
        }
        
        // ⭐ Validar que el correo ingresado sea el correo del sistema (del .env)
        $emailSistema = config('mail.from.address'); // cindisosa560@gmail.com
        
        if ($request->email !== $emailSistema) {
            return back()->withErrors([
                'email' => 'El correo de recuperación no es válido.'
            ])->withInput();
        }
        
        // Generar token único
        $token = Str::random(64);
        
        // Guardar token con el EMAIL del .env
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );
        
        // ⭐ Enviar correo con el NOMBRE DE USUARIO en la URL
        try {
            Mail::send('auth.passwords.email-template', [
                'token' => $token, 
                'email' => $request->email,
                'username' => $usuario->name
            ], function($message) use ($request) {
                $message->to($request->email);
                $message->subject('Recuperación de Contraseña - Clinitek');
            });
            
            return redirect()->route('login.form')->with('success', 
                'Se ha enviado un enlace de recuperación al correo del sistema.');
                
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'No se pudo enviar el correo. Verifica tu conexión o contacta al administrador.'
            ])->withInput();
        }
    }
    
    /**
     * Mostrar formulario de restablecimiento de contraseña
     */
    public function showResetPassword($token, Request $request)
    {
        $email = $request->query('email');
        $username = $request->query('username'); // ⭐ Obtener username de la URL
        
        // Verificar que el token existe
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();
        
        if (!$passwordReset) {
            return redirect()->route('login.form')->withErrors([
                'email' => 'El enlace de recuperación no es válido o ha expirado.'
            ]);
        }
        
        // Verificar que no haya expirado (24 horas)
        if (Carbon::parse($passwordReset->created_at)->addHours(24)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('login.form')->withErrors([
                'email' => 'El enlace de recuperación ha expirado. Solicita uno nuevo.'
            ]);
        }
        
        // ⭐ Verificar que el usuario existe
        $usuario = Usuario::where('name', $username)->first();
        
        if (!$usuario) {
            return redirect()->route('login.form')->withErrors([
                'email' => 'Usuario no encontrado.'
            ]);
        }
        
        return view('auth.passwords.reset', compact('token', 'email', 'username'));
    }
    
    /**
     * Procesar el restablecimiento de contraseña
     */
    public function resetPassword(Request $request)
    {
        // Validación personalizada de contraseña
        $password = $request->input('password');
        $passwordErrors = [];
        
        if (strlen($password) < 8) {
            $passwordErrors[] = 'mínimo 8 caracteres';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $passwordErrors[] = 'al menos una letra mayúscula';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $passwordErrors[] = 'al menos un número';
        }
        if (!preg_match('/[!.@#$%^&*]/', $password)) {
            $passwordErrors[] = 'al menos un carácter especial (!.@#$%^&*)';
        }
        
        if (!empty($passwordErrors)) {
            return back()->withErrors([
                'password' => 'La contraseña debe contener: ' . implode(', ', $passwordErrors) . '.'
            ])->withInput();
        }
        
        // Validar confirmación de contraseña
        if ($password !== $request->input('password_confirmation')) {
            return back()->withErrors([
                'password_confirmation' => 'Las contraseñas no coinciden.'
            ])->withInput();
        }
        
        $request->validate([
            'email' => 'required|email',
            'username' => 'required',
            'token' => 'required'
        ], [
            'username.required' => 'El nombre de usuario es obligatorio.'
        ]);
        
        // Verificar token
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();
        
        if (!$passwordReset) {
            return back()->withErrors(['email' => 'El enlace de recuperación no es válido.']);
        }
        
        if (!Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['email' => 'El enlace de recuperación no es válido.']);
        }
        
        if (Carbon::parse($passwordReset->created_at)->addHours(24)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'El enlace de recuperación ha expirado.']);
        }
        
        // ⭐ Buscar usuario por NOMBRE DE USUARIO
        $usuario = Usuario::where('name', $request->username)->first();
        
        if (!$usuario) {
            return back()->withErrors(['username' => 'Usuario no encontrado.']);
        }
        
        // Actualizar contraseña SOLO de ese usuario
        $usuario->password = Hash::make($request->password);
        $usuario->save();
        
        // Eliminar token usado
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        return redirect()->route('login.form')->with('success', 
            '¡Contraseña restablecida exitosamente! Tu nombre de usuario es: ' . $usuario->name);
    }
    
    // ==================== LOGOUT ====================
    
    /**
     * Cerrar sesión
     */
    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login.form');
    }
}