<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use OpenApi\Attributes as OA;

class CategoryController extends Controller
{
    /**
     * Listar categorías.
     */
    /**Documentacion */
    #[OA\Get(
    path: '/api/categories',
    summary: 'Listar categorías',
    tags: ['Categorías'],
    security: [['bearerAuth' => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Lista de categorías obtenida correctamente'
        ),
        new OA\Response(
            response: 401,
            description: 'No autenticado'
        )
    ]
)]
    /**Documentacion */
    public function index()
    {
        $categories = Category::with('products')->get();

        return response()->json([
            'categories' => $categories
        ]);
    }

    /**
     * Crear una categoría.
     */

    #[OA\Post(
    path: '/api/categories',
    summary: 'Crear una nueva categoría',
    tags: ['Categorías'],
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name'],
            properties: [
                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: 'Bebidas'
                ),
                new OA\Property(
                    property: 'description',
                    type: 'string',
                    example: 'Bebidas tradicionales de Centroamérica y México.'
                )
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: 'Categoría creada correctamente'
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

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return response()->json([
            'message' => 'Categoría creada correctamente.',
            'category' => $category
        ], 201);
    }

    /**
     * Mostrar una categoría.
     */
    /**Documentacion */
    #[OA\Get(
    path: '/api/categories/{category}',
    summary: 'Mostrar una categoría específica',
    tags: ['Categorías'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'category',
            in: 'path',
            required: true,
            description: 'ID de la categoría',
            schema: new OA\Schema(
                type: 'integer',
                example: 1
            )
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Categoría obtenida correctamente'
        ),
        new OA\Response(
            response: 401,
            description: 'No autenticado'
        ),
        new OA\Response(
            response: 404,
            description: 'Categoría no encontrada'
        )
    ]
)]
    /**Documentcion */
    public function show(Category $category)
    {
        $category->load('products');
        
        return response()->json([
            'category' => $category
        ]);
    }

    /**
     * Actualizar una categoría.
     */

    /**Documentacion */
    #[OA\Put(
    path: '/api/categories/{category}',
    summary: 'Actualizar una categoría',
    tags: ['Categorías'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'category',
            in: 'path',
            required: true,
            description: 'ID de la categoría',
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
                    example: 'Bebidas'
                ),
                new OA\Property(
                    property: 'description',
                    type: 'string',
                    example: 'Bebidas tradicionales y artesanales de Centroamérica y México.'
                )
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Categoría actualizada correctamente'
        ),
        new OA\Response(
            response: 401,
            description: 'No autenticado'
        ),
        new OA\Response(
            response: 404,
            description: 'Categoría no encontrada'
        ),
        new OA\Response(
            response: 422,
            description: 'Error de validación'
        )
    ]
)]
    /**Documentacion */
    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ) {
        $category->update($request->validated());

        return response()->json([
            'message' => 'Categoría actualizada correctamente.',
            'category' => $category
        ]);
    }

    /**
     * Eliminar una categoría.
     */
    /**Documentacion de borrado */
    #[OA\Delete(
    path: '/api/categories/{category}',
    summary: 'Eliminar una categoría',
    tags: ['Categorías'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'category',
            in: 'path',
            required: true,
            description: 'ID de la categoría',
            schema: new OA\Schema(
                type: 'integer',
                example: 1
            )
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Categoría eliminada correctamente'
        ),
        new OA\Response(
            response: 401,
            description: 'No autenticado'
        ),
        new OA\Response(
            response: 404,
            description: 'Categoría no encontrada'
        )
    ]
)]
    /**Documentacion de borrado */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Categoría eliminada correctamente.'
        ]);
    }
}