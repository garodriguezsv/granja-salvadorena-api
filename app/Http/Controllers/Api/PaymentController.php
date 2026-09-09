<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use OpenApi\Attributes as OA;

class PaymentController extends Controller
{
    /**Documentacion */
    #[OA\Post(
    path: "/api/orders/{order}/payment",
    summary: "Iniciar pago de una orden",
    description: "Crea un PaymentIntent de Stripe y registra el pago asociado a la orden.",
    tags: ["Pagos"],
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
            response: 201,
            description: "Pago iniciado correctamente."
        ),
        new OA\Response(
            response: 403,
            description: "La orden no pertenece al usuario."
        ),
        new OA\Response(
            response: 422,
            description: "La orden no puede ser pagada."
        ),
        new OA\Response(
            response: 500,
            description: "Error al procesar el pago con Stripe."
        )
    ]
)]
    /**Fin Documentacion */
    public function store(Request $request, Order $order)
    {
        // Verificar que la orden pertenece al usuario autenticado
        if ($order->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'No tienes permiso para pagar esta orden.'
            ], 403);
        }

        // Verificar que la orden no esté cancelada
        if ($order->status === 'cancelled') {
            return response()->json([
                'message' => 'No se puede pagar una orden cancelada.'
            ], 422);
        }

        // Configurar Stripe
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {

            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($order->total * 100),
                'currency' => env('STRIPE_CURRENCY', 'usd'),
                'payment_method_types' => ['card'],
            ]);

            $payment = Payment::create([
                'order_id' => $order->id,
                'stripe_payment_id' => $paymentIntent->id,
                'amount' => $order->total,
                'currency' => env('STRIPE_CURRENCY', 'usd'),
                'status' => $paymentIntent->status,
            ]);

            return response()->json([
                'message' => 'Pago iniciado correctamente.',
                'payment' => $payment,
                'client_secret' => $paymentIntent->client_secret,
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'No fue posible procesar el pago.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}