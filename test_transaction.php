<?php
header('Content-Type: application/json');

$secretKey = "sk_gNesqZTjGlcZO1859qhBIhEhRjqwhTbK9TLUmlblqYzqA-Dy";
$apiUrl = "https://api.blackcatpagamentos.com/v1/transactions";

// Token de teste (cartão fictício)
$cardToken = "tokentest123456";

$data = [
    "amount" => 1000, // R$10,00
    "currency" => "BRL",
    "card_token" => $cardToken,
    "description" => "Teste de transação CARTEIRA BLACK",
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
} else {
    $res = json_decode($response,true);
    echo json_encode($res, JSON_PRETTY_PRINT);
}
