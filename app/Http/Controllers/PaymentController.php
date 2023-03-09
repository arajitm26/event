<?php

namespace App\Http\Controllers;
require('../app/Razorpay/razorpay-php/Razorpay.php');
session_start();

// // Create the Razorpay Order

use Razorpay\Api\Api;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //

   public $keyId = '<Enter your key id>';
   public	$keySecret = '<Enter your key secret>';
   public	$displayCurrency = '<Enter your display currency here>';

    public function make_payment()
    {
    	$api = new Api($this->keyId, $this->keySecret);

				//
				// We create an razorpay order using orders api
				// Docs: https://docs.razorpay.com/docs/orders
				//
				$orderData = [
				    'receipt'         => 3456,
				    'amount'          => 10 * 100, // 10 rupees in paise
				    'currency'        => 'INR',
				    'payment_capture' => 1 // auto capture
				];

				$razorpayOrder = $api->order->create($orderData);

				$razorpayOrderId = $razorpayOrder['id'];

				$_SESSION['razorpay_order_id'] = $razorpayOrderId;

				$displayAmount = $amount = $orderData['amount'];

				if ($displayCurrency !== 'INR')
				{
				    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
				    $exchange = json_decode(file_get_contents($url), true);

				    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
				}

				$checkout = 'automatic';

				if (isset($_GET['checkout']) and in_array($_GET['checkout'], ['automatic', 'manual'], true))
				{
				    $checkout = $_GET['checkout'];
				}

				$data = [
				    "key"               => $this->keyId,
				    "amount"            => $amount,
				    "name"              => "DJ Tiesto",
				    "description"       => "Tron Legacy",
				    "image"             => "https://s29.postimg.org/r6dj1g85z/daft_punk.jpg",
				    "prefill"           => [
				    "name"              => "Daft Punk",
				    "email"             => "customer@merchant.com",
				    "contact"           => "9999999999",
				    ],
				    "notes"             => [
				    "address"           => "Hello World",
				    "merchant_order_id" => "12312321",
				    ],
				    "theme"             => [
				    "color"             => "#F37254"
				    ],
				    "order_id"          => $razorpayOrderId,
				];

				if ($displayCurrency !== 'INR')
				{
				    $data['display_currency']  = $displayCurrency;
				    $data['display_amount']    = $displayAmount;
				}

				$json = json_encode($data);

				require("../app/Razorpay/checkout/{$checkout}.php");

				    }
}
