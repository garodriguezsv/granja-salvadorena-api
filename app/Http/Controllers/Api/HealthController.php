<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Attributes as OA;

class HealthController extends Controller
{
    #[OA\Get(
        path: '/api/health',
        summary: 'Verificar estado de la API',
        tags: ['Health'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'API funcionando correctamente'
            )
        ]
    )]
    public function index()
    {
        return response()->json([
            'message' => 'API Granja Salvadoreña funcionando correctamente'
        ]);
    }
}