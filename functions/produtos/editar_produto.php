<?php

include '../../includes/conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//var_dump($_POST);

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$produto_id = $data['id'];
$produto_sku = $data['sku'];
$produto_descricao = $data['descricao'];
$produto_tipo = $data['tipo'];
$produto_valor = str_replace(',', '.', $data['valorVenda']);
$qtde_estoque = $data['qtde'];

$sql = "UPDATE TB_Produtos SET produto_sku = '$produto_sku', produto_descricao = '$produto_descricao', produto_tipo = '$produto_tipo', produto_valor = '$produto_valor', qtde_estoque = '$qtde_estoque' WHERE produto_id = $produto_id";
//echo $sql;die;

$stmt = sqlsrv_query($conn, $sql);

if ($stmt) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao executar a query: ' . print_r(sqlsrv_errors(), true)]);
}