<?php

include '../../includes/conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//var_dump($_POST);

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$tipo_id = $data['id'];

$sql = "DELETE FROM TB_Tipo_Produto WHERE tipo_id = $tipo_id";
//echo $sql;die;

$stmt = sqlsrv_query($conn, $sql);

if ($stmt) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao executar a query: ' . print_r(sqlsrv_errors(), true)]);
}