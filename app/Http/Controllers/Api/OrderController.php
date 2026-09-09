<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class OrderController extends Controller
{

    /**Documentacion */
    #[OA\Get(
        path: "/api/orders",
        summary: "Obtener historial de órdenes",
        description: "Obtiene las órdenes del usuario autenticado.",
        tags: ["Órdenes"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Órdenes obtenidas correctamente."
            ),
            new OA\Response(
                response: 401,
                description: "No autenticado."
            )
        ]
    )]
    /**Fin documentacion */
    public function index()
    {
        $user = auth()->user();

        $orders = $user->orders()
            ->with('items.product')
            ->latest()
            ->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    /**Documentacion */
    #[OA\Post(
    path: "/api/orders",
    summary: "Crear una orden",
    description: "Crea una nueva orden para el usuario autenticado.",
    tags: ["Órdenes"],
    security: [["bearerAuth" => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["items"],
            properties: [
                new OA\Property(
                    property: "items",
                    type: "array",
                    items: new OA\Items(
                        type: "object",
                        required: ["product_id", "quantity"],
                        properties: [
                            new OA\Property(
                                property: "product_id",
                                type: "integer",
                                example: 2
                            ),
                            new OA\Property(
                                property: "quantity",
                                type: "integer",
                                example: 2
                            )
                        ]
                    )
                )
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: "Orden creada correctamente."
        ),
        new OA\Response(
            response: 401,
            description: "No autenticado."
        ),
        new OA\Response(
            response: 422,
            description: "Datos de la orden inválidos."
        )
    ]
)]
    /**fin documentacion */
    public function store(StoreOrderRequest $request)
    {
        $user = auth()->user();

        $total = 0;

        DB::transaction(function () use ($request, $user, &$total, &$order) {

            $order = Order::create([
                'user_id' => $user->id,
                'total' => 0,
                'status' => 'pending',
            ]);

            foreach ($request->items as $item) {

                $product = Product::findOrFail($item['product_id']);

                $subtotal = $product->price * $item['quantity'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $total += $subtotal;
            }

            $order->update([
                'total' => $total,
            ]);
        });

        $order->load([
            'user',
            'items.product',
        ]);

        return response()->json([
            'message' => 'Orden creada correctamente.',
            'order' => $order,
        ], 201);
    }

    /**Documentacion */
    #[OA\Get(
    path: "/api/orders/{order}",
    summary: "Consultar una orden",
    description: "Obtiene una orden perteneciente al usuario autenticado.",
    tags: ["Órdenes"],
    security: [["bearerAuth" => []]],
    parameters: [
        new OA\Parameter(
            name: "order",
            description: "ID de la orden",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer"),
            example: 2
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Orden obtenida correctamente."
        ),
        new OA\Response(
            response: 403,
            description: "La orden no pertenece al usuario."
        ),
        new OA\Response(
            response: 404,
            description: "Orden no encontrada."
        )
    ]
)]
    /**Fin documntacion */
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'No tienes permiso para ver esta orden.'
            ], 403);
        }

        $order->load([
            'items.product',
        ]);

        return response()->json([
            'order' => $order,
        ]);
    }

    /**Documentacion */
    #[OA\Patch(
    path: "/api/orders/{order}/cancel",
    summary: "Cancelar una orden",
    description: "Cancela una orden perteneciente al usuario autenticado.",
    tags: ["Órdenes"],
    security: [["bearerAuth" => []]],
    parameters: [
        new OA\Parameter(
            name: "order",
            description: "ID de la orden",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer"),
            example: 2
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Orden cancelada correctamente."
        ),
        new OA\Response(
            response: 403,
            description: "La orden no pertenece al usuario."
        ),
        new OA\Response(
            response: 422,
            description: "La orden ya está cancelada."
        ),
        new OA\Response(
            response: 404,
            description: "Orden no encontrada."
        )
    ]
)]
    /**Fin documentacion */
    public function cancel(Order $order)
    {
        // Verificar que la orden pertenezca al usuario autenticado
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'No tienes permiso para cancelar esta orden.'
            ], 403);
        }

        // Verificar que la orden no esté cancelada
        if ($order->status === 'cancelled') {
            return response()->json([
                'message' => 'Esta orden ya fue cancelada.'
            ], 422);
        }

        // Cancelar la orden
        $order->update([
            'status' => 'cancelled'
        ]);

        return response()->json([
            'message' => 'Orden cancelada correctamente.',
            'order' => $order
        ]);
    }

}