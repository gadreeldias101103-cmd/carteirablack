<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
if(!isset($input['token']) || !isset($input['amount'])){
    echo json_encode(["success"=>false,"error"=>"Dados inválidos"]);
    exit;
}

$token = $input['token'];
$amount = $input['amount'];

// Endpoint produção da BlackCatPay
$apiUrl = "https://api.blackcatpagamentos.com/v1/transactions";
$secretKey = "sk_gNesqZTjGlcZO1859qhBIhEhRjqwhTbK9TLUmlblqYzqA-Dy";

$data = [
    "amount" => intval($amount * 100),
    "currency" => "BRL",
    "card_token" => $token,
    "description" => "Depósito CARTEIRA BLACK",
    "three_ds" => true
];

$ch = curl_init($apiUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $secretKey",
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($ch);
$curlError = curl_error($ch);
curl_close($ch);

if($curlError){
    echo json_encode(["success"=>false,"error"=>$curlError]);
    exit;
}

$res = json_decode($response,true);

if(isset($res['status']) && ($res['status'] === 'approved' || $res['status'] === 'pending_3ds')){
    $result = ["success"=>true,"data"=>$res];
    if(isset($res['threeDS']['redirectUrl'])){
        $result['data']['three_ds_url'] = $res['threeDS']['redirectUrl'];
    }
    echo json_encode($result);
} else {
    echo json_encode(["success"=>false,"error"=>$res]);
}