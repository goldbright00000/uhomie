<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{Payment};
class PaymentController extends Controller
{
    //
    public function getPayments(){
      $payments = Payment::with('user')->get();
      return response([ "records" => $payments ]);
    }
    public function getPayment( Request $request ){
      $payment = Payment::find($request->paymentId)->load("user");
      return response([ "payment" => $payment ]);
    }
    public function update( Request $request ){
      $payment = Payment::find($request->paymentId);
      $payment->order = $request->order;
      $payment->user_id = $request->user_id;
      $payment->status = $request->status;
      $payment->amount = $request->amount;
      $payment->iva = $request->iva;
      $payment->total = $request->total;
      $payment->method = $request->method;
      $payment->currency = $request->concurrency;
      $payment->exchange_rate = $request->exchange_rate;
      $payment->details = $request->details;
      $payment->save();
      return response([ "operation" => true ]);
    }
    public function delete( Request $request ){
      $payment = Payment::find($request->paymentId);
      $payment->delete();
      return response([ "operation" => true ]);
    }
}
