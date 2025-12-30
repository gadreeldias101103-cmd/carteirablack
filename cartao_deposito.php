<?php
$headers = getallheaders();
$token = str_replace("Bearer ","",$headers['Authorization']);

$user = validarToken($token); // sua função

$data = json_decode(file_get_contents("php://input"), true);

$payload = [
  "amount" => $data['valor'],
  "payment_method" => "credit_card",
  "card_token" => $data['card_token'],
  "callback_url" => "https://SEU_BACKEND.ct.ws/api/blackcat_callback.php"
];

$ch = curl_init("https://api.blackcatpagamentos.com/v1/transactions");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER=>true,
  CURLOPT_POST=>true,
  CURLOPT_HTTPHEADER=>[
    "Authorization: Bearer SUA_SECRET_KEY",
    "Content-Type: application/json"
  ],
  CURLOPT_POSTFIELDS=>json_encode($payload)
]);

$response = curl_exec($ch);
curl_close($ch);

echo json_encode(["message"=>"Pagamento processando"]);
