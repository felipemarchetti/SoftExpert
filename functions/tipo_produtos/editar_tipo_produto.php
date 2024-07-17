<?php

include '../../includes/conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//var_dump($_POST);

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$tipo_id = $data['id'];
$tipo_descricao = $data['descricao'];
$tipo_imposto = str_replace(',', '.', $data['valorImposto']);

$sql = "UPDATE TB_Tipo_Produto SET tipo_descricao = '$tipo_descricao', valor_porcentagem_imposto = '$tipo_imposto' WHERE tipo_id = $tipo_id";
//echo $sql;die;

$stmt = sqlsrv_query($conn, $sql);

if ($stmt) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao executar a query: ' . print_r(sqlsrv_errors(), true)]);
}