<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::prefix('v1')->group(function () {

    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'app' => config('app.name'),
            'version' => '0.1.0'
        ]);
    })->name('health');

      Route::post('/login', function (Illuminate\Http\Request $request) {
      $datos = $request->validate([
          'email' => 'required|email',
          'password' => 'required|string',
      ]);

      $usuario = User::where('email', $datos['email'])->first();

      if (!$usuario || !\Illuminate\Support\Facades\Hash::check($datos['password'], $usuario->password)) {
          return response()->json([
              'message' => 'Correo o contraseña incorrectos'
          ], 401);
      }

      $token = $usuario->createToken('auth_token')->plainTextToken;

      return response()->json([
          'message' => 'Inicio de sesión exitoso',
          'user' => $usuario,
          'token' => $token,
      ]);
  });

});