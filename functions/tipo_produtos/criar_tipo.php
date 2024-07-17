<?php

include '../../includes/conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//var_dump($_POST);

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$produto_descricao = $data['descricaoTipo'];
$produto_valor = str_replace(',', '.', $data['valorImpostoTipo']);

$sql = "INSERT INTO TB_Tipo_Produto (tipo_descricao, valor_porcentagem_imposto) VALUES ('$produto_descricao', '$produto_valor')";
//echo $sql;die;

$stmt = sqlsrv_query($conn, $sql);

if ($stmt) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao executar a query: ' . print_r(sqlsrv_errors(), true)]);
}