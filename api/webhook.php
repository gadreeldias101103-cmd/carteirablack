<?php
// webhook.php
header('Content-Type: application/json');

// Recebe o POST da BlackCatPay
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Para debug: salvar os webhooks recebidos (opcional)
file_put_contents('webhook_log.json', json_encode($data, JSON_PRETTY_PRINT));

// Verifica se é um evento de transação
if(isset($data['type']) && $data['type'] === 'transaction' && isset($data['data'])){
    $transaction = $data['data'];
    $status = $transaction['status'] ?? '';
    $transactionId = $transaction['id'] ?? null;
    $amount = $transaction['amount'] ?? null;
    $customer = $transaction['customer']['name'] ?? '';
    
    // Aqui você atualiza seu banco de dados
    // Exemplo genérico:
    $dbFile = 'saldo.json';
    $saldoAtual = file_exists($dbFile) ? json_decode(file_get_contents($dbFile), true) : [];
    
    if($status === 'paid'){
        // Adiciona saldo ao cliente
        $saldoAtual[$customer] = ($saldoAtual[$customer] ?? 0) + ($amount/100);
    } elseif($status === 'refused'){
        // Pode registrar falha de pagamento
        $saldoAtual[$customer] = $saldoAtual[$customer] ?? 0;
    }
    
    file_put_contents($dbFile, json_encode($saldoAtual, JSON_PRETTY_PRINT));
}

// Retorna 200 para a BlackCatPay confirmar recebimento
echo json_encode(["success"=>true]);
