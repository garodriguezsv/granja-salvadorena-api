<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                env('STRIPE_WEBHOOK_SECRET')
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json([
                'message' => 'Payload inválido.'
            ], 400);
        } catch (SignatureVerificationException $e) {
            return response()->json([
                'message' => 'Firma inválida.'
            ], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;

            $payment = Payment::where(
                'stripe_payment_id',
                $paymentIntent->id
            )->first();

            if ($payment) {
                $payment->update([
                    'status' => $paymentIntent->status,
                ]);

                $payment->order()->update([
                    'status' => 'paid',
                ]);
            }
        }

        if ($event->type === 'payment_intent.payment_failed') {
            $paymentIntent = $event->data->object;

            $payment = Payment::where(
                'stripe_payment_id',
                $paymentIntent->id
            )->first();

            if ($payment) {
                $payment->update([
                    'status' => $paymentIntent->status,
                ]);
            }
        }

        return response()->json([
            'received' => true,
        ]);
    }
}