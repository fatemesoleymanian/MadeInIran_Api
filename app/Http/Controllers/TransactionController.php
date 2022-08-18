<?php

namespace App\Http\Controllers;

use App\Classes\Zarinpal;
use App\Models\Card;
use App\Models\CardProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class TransactionController extends Controller
{

    protected $zarinpal;

    public function __construct(Zarinpal $zarinpal)
    {
        $this->zarinpal = $zarinpal;
    }

    //badaz pardakht status order va card bayad 0 she

    public function showAll()
    {

    }

    public function show()
    {

    }

    public function showAllByUser($user)
    {

    }

    public function showByOrder($order)
    {

    }


    public function payment(Request $request): JsonResponse
    {
        $user = $request->user();
        $card = $request->user()->card->last()->id;
        // $amount = $card->products()->withSum('state','discounted_price')->first()->state_sum_discounted_price;
        $products = CardProduct::with(['product', 'state'])->where('card_id', $card)->get();

        //products
        if (!$products) return response()->json([
            'msg' => 'سبد خرید خالی است!'
        ]);

        $amount = 0;
        foreach ($products as $product) {
            $amount += $product->count * $product->state->discounted_price;
        }
        //be rial
        $amount = $amount * 10;

        try {
            $invoice = (new Invoice)->amount($amount);
            return Payment::purchase($invoice, function ($driver, $transactionId) use ($user, $amount) {
                $user->transactions()->create([
                    'amount' => $amount,
                    'tx_id' => $transactionId,
                ]);
            })->pay()->render();
        } catch (InvalidPaymentException | InvoiceNotFoundException $exception) {
            throw new Exception($exception->getMessage());
        }


    }

    public function verify(Request $request)
    {
        //real payment
        $token = $request->Authority;
        $transaction = $request->user()->transactions->last();
        $amount = $transaction->amount;
        try {
            $card_id = Card::where([
                'user_id' => $request->user()->id,
                'status' => 1
            ])->update('status', 0);

            //create new card
            Card::create([
                'status' => 1,
                'user_id' => $request->user()->id
            ]);
            $order = Order::updateOrCreate([
                'card_id' => $card_id->id,
                'status' => 1
            ], [
                'total' => $amount,
                'current_state' => 'ثبت و پرداخت سفارش'
            ]);
            $ref_id = $this->zarinpal->verify($token, $amount)->getReferenceId();
            $transaction->update([
                'reference_id' => $ref_id,
                'status' => 'ACCEPTED',
                'order_id' => $order->id,
            ]);

        } catch (InvalidPaymentException | InvoiceNotFoundException $exception) {
            $transaction->update([
                'status' => 'REJECTED'
            ]);
            return response()->json([
                'message' => 'تراکنش ناموفق!'
            ]);
        }
    }
}

