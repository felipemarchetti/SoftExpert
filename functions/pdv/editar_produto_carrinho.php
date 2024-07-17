<?php

include '../../includes/conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//var_dump($_POST);

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$temp_id = $data['temp_id'];
$produto_qtde = str_replace(',', '.', $data['produto_qtde']);
$desconto = str_replace(',', '.', $data['desconto']);
$valor_total_brutoPdv = str_replace(',', '.', $data['valor_total_brutoPdv']);
$mod_desconto = $data['mod_desconto'];
$produto_valor_imposto = $data['produto_valor_imposto'];

if($mod_desconto == 'descontoDinheiro'){
    $mod_desconto = 'R$';
}

if($mod_desconto == 'descontoPorcentagem'){
    $mod_desconto = '%';
}

$sql = "UPDATE TB_Temp_Venda SET produto_qtde = '$produto_qtde', desconto = '$desconto', produto_valor_total = '$valor_total_brutoPdv',  mod_desconto = '$mod_desconto', produto_valor_imposto = '$produto_valor_imposto' WHERE temp_id = $temp_id";
//echo $sql;die;

$stmt = sqlsrv_query($conn, $sql);

if ($stmt) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao executar a query: ' . print_r(sqlsrv_errors(), true)]);
}