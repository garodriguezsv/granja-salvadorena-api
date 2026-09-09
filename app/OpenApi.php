<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'API Granja Salvadoreña',
    description: 'API REST para el sistema de E-commerce de Granja Salvadoreña'
)]

#[OA\Server(
    url: 'http://127.0.0.1:8000',
    description: 'Servidor local'
)]

#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]

class OpenApi
{
}