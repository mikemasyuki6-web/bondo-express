<?php
// Weka keys zako kutoka developer.safaricom.co.ke
$consumerKey = "YOUR_KEY";
$consumerSecret = "YOUR_SECRET";
$shortCode = "174379";
$passkey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
$callbackUrl = "https://YOUR-VERCEL-URL.vercel.app/callback.php";

$phone = $_POST['phone']; $amount = $_POST['amount']; $seats = $_POST['seats'];

// Get Token
$creds = base64_encode($consumerKey.":".$consumerSecret);
$ch = curl_init("https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials");
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic ".$creds]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$token = json_decode(curl_exec($ch))->access_token;

// STK Push
$timestamp = date('YmdHis');
$password = base64_encode($shortCode.$passkey.$timestamp);
$data = ["BusinessShortCode"=>$shortCode,"Password"=>$password,"Timestamp"=>$timestamp,"TransactionType"=>"CustomerPayBillOnline","Amount"=>$amount,"PartyA"=>$phone,"PartyB"=>$shortCode,"PhoneNumber"=>$phone,"CallBackURL"=>$callbackUrl,"AccountReference"=>"BondoExpress","TransactionDesc"=>"Seats $seats"];

$ch2 = curl_init("https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest");
curl_setopt($ch2, CURLOPT_HTTPHEADER, ["Content-Type: application/json","Authorization: Bearer ".$token]);
curl_setopt($ch2, CURLOPT_POST, 1);
curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
echo curl_exec($ch2);
?>