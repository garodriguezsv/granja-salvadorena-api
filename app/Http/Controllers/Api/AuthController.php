<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
    path: '/api/auth/register',
    summary: 'Registrar un nuevo usuario',
    tags: ['Autenticación'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'email', 'password', 'password_confirmation'],
            properties: [
                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: 'Gerson Rodriguez'
                ),
                new OA\Property(
                    property: 'email',
                    type: 'string',
                    format: 'email',
                    example: 'gerson@example.com'
                ),
                new OA\Property(
                    property: 'password',
                    type: 'string',
                    format: 'password',
                    example: 'password123'
                ),
                new OA\Property(
                    property: 'password_confirmation',
                    type: 'string',
                    format: 'password',
                    example: 'password123'
                ),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Usuario registrado correctamente'
        ),
        new OA\Response(
            response: 422,
            description: 'Error de validación'
        )
    ]
)]
    /**
     * Registrar un nuevo usuario.
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = Auth::guard('api')->login($user);

        return $this->respondWithToken($token, $user, 201);
    }

    /**
     * Iniciar sesión.
     */
    /**Documentacion */
    #[OA\Post(
    path: '/api/auth/login',
    summary: 'Iniciar sesión',
    tags: ['Autenticación'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(
                    property: 'email',
                    type: 'string',
                    format: 'email',
                    example: 'gerson@example.com'
                ),
                new OA\Property(
                    property: 'password',
                    type: 'string',
                    format: 'password',
                    example: 'password123'
                ),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Inicio de sesión correcto'
        ),
        new OA\Response(
            response: 401,
            description: 'Credenciales incorrectas'
        ),
        new OA\Response(
            response: 422,
            description: 'Error de validación'
        )
    ]
)]
    /**Documentacion */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }

        return $this->respondWithToken(
            $token,
            Auth::guard('api')->user()
        );
    }

    /**
     * Obtener usuario autenticado.
     */
    
    /**Dcoumentacion */
    #[OA\Get(
    path: '/api/auth/me',
    summary: 'Obtener usuario autenticado',
    tags: ['Autenticación'],
    security: [['bearerAuth' => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Usuario autenticado obtenido correctamente'
        ),
        new OA\Response(
            response: 401,
            description: 'No autenticado'
        )
    ]
)]
    /**Dcoumentacion */

    public function me()
    {
        return response()->json([
            'user' => Auth::guard('api')->user()
        ]);
    }

    /**
     * Cerrar sesión.
     */

    /**
     * Documentcion
     */
    #[OA\Post(
    path: '/api/auth/logout',
    summary: 'Cerrar sesión',
    tags: ['Autenticación'],
    security: [['bearerAuth' => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Sesión cerrada correctamente'
        ),
        new OA\Response(
            response: 401,
            description: 'No autenticado'
        )
    ]
)]
    /**
     * Documentacion
     */
    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.'
        ]);
    }

    /**
     * Construir respuesta con JWT.
     */
    protected function respondWithToken($token, $user, int $status = 200)
    {
        return response()->json([
            'message' => 'Autenticación realizada correctamente.',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ], $status);
    }
}