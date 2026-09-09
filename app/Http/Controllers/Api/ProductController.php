<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use OpenApi\Attributes as OA;

class ProductController extends Controller
{
    /**
     * Mostrar todos los productos.
     */
    /**Documentarcion */
        #[OA\Get(
        path: '/api/products',
        summary: 'Listar productos',
        tags: ['Productos'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista de productos obtenida correctamente'
            ),
            new OA\Response(
                response: 401,
                description: 'No autenticado'
            )
        ]
    )]
    /**Documentarcion */
    public function index()
    {
        $products = Product::with('category')->get();

        return response()->json([
            'products' => $products
        ]);
    }

    /**
     * Crear un nuevo producto.
     */
    /**Documentarcion */
        #[OA\Post(
    path: '/api/products',
    summary: 'Crear un nuevo producto',
    tags: ['Productos'],
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'price', 'stock'],
            properties: [
                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: 'Horchata Salvadoreña'
                ),
                new OA\Property(
                    property: 'description',
                    type: 'string',
                    example: 'Bebida tradicional elaborada con semillas y especias.'
                ),
                new OA\Property(
                    property: 'price',
                    type: 'number',
                    format: 'float',
                    example: 3.50
                ),
                new OA\Property(
                    property: 'stock',
                    type: 'integer',
                    example: 50
                ),
                new OA\Property(
                    property: 'country',
                    type: 'string',
                    example: 'El Salvador'
                ),
                new OA\Property(
                    property: 'active',
                    type: 'boolean',
                    example: true
                ),
                new OA\Property(
                    property: 'category_id',
                    type: 'integer',
                    example: 1
                )
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Producto creado correctamente'
        ),
        new OA\Response(
            response: 401,
            description: 'No autenticado'
        ),
        new OA\Response(
            response: 422,
            description: 'Error de validación'
        )
    ]
)]
    /**Documentarcion */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

         $product->load('category');

        return response()->json([
            'message' => 'Producto creado correctamente.',
            'product' => $product
        ], 201);
    }

    /**
     * Mostrar un producto específico.
     */
    /**Documentarcion */
    #[OA\Get(
    path: '/api/products/{product}',
    summary: 'Mostrar un producto específico',
    tags: ['Productos'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'product',
            in: 'path',
            required: true,
            description: 'ID del producto',
            schema: new OA\Schema(
                type: 'integer',
                example: 1
            )
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Producto obtenido correctamente'
        ),
        new OA\Response(
            response: 401,
            description: 'No autenticado'
        ),
        new OA\Response(
            response: 404,
            description: 'Producto no encontrado'
        )
    ]
)]
    /**Documentarcion */
    public function show(Product $product)
    {
        $product->load('category');
        
        return response()->json([
            'product' => $product
        ]);
    }

    /**
     * Actualizar un producto.
     */
    /**Documentacion */
    #[OA\Put(
    path: '/api/products/{product}',
    summary: 'Actualizar un producto',
    tags: ['Productos'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'product',
            in: 'path',
            required: true,
            description: 'ID del producto',
            schema: new OA\Schema(
                type: 'integer',
                example: 1
            )
        )
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: 'Horchata Salvadoreña'
                ),
                new OA\Property(
                    property: 'description',
                    type: 'string',
                    example: 'Bebida tradicional elaborada con semillas y especias.'
                ),
                new OA\Property(
                    property: 'price',
                    type: 'number',
                    format: 'float',
                    example: 4.00
                ),
                new OA\Property(
                    property: 'stock',
                    type: 'integer',
                    example: 75
                ),
                new OA\Property(
                    property: 'country',
                    type: 'string',
                    example: 'El Salvador'
                ),
                new OA\Property(
                    property: 'active',
                    type: 'boolean',
                    example: true
                ),
                new OA\Property(
                    property: 'category_id',
                    type: 'integer',
                    example: 1
                )
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Producto actualizado correctamente'
        ),
        new OA\Response(
            response: 401,
            description: 'No autenticado'
        ),
        new OA\Response(
            response: 404,
            description: 'Producto no encontrado'
        ),
        new OA\Response(
            response: 422,
            description: 'Error de validación'
        )
    ]
)]
    /**Documentacion */
    public function update(
        UpdateProductRequest $request,
        Product $product
    ) {
        $product->update($request->validated());

        $product->load('category');

        return response()->json([
            'message' => 'Producto actualizado correctamente.',
            'product' => $product
        ]);
    }

    /**
     * Eliminar un producto.
     */

    /**Documentacion */
    #[OA\Delete(
    path: '/api/products/{product}',
    summary: 'Eliminar un producto',
    tags: ['Productos'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'product',
            in: 'path',
            required: true,
            description: 'ID del producto',
            schema: new OA\Schema(
                type: 'integer',
                example: 1
            )
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Producto eliminado correctamente'
        ),
        new OA\Response(
            response: 401,
            description: 'No autenticado'
        ),
        new OA\Response(
            response: 404,
            description: 'Producto no encontrado'
        )
    ]
)]
    /**Documentacion */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente.'
        ]);
    }
}