<?php
$data = json_decode(file_get_contents("php://input"), true);

if($data['status']=="paid"){
  $user_id = $data['metadata']['user_id'];
  $valor = $data['amount'];

  // atualizar saldo no banco
}
