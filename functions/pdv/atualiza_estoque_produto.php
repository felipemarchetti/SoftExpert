<?php

include '../../includes/conexao.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$produto_id = $_POST['produto_id'];
$qtde = $_POST['qtde'];

$operacao = $_GET['operacao'];

if($operacao == 'aumentaEstoque'){
    $logica = '+';
}

if($operacao == 'diminuiEstoque'){
    $logica = '-';
}

$sql = "UPDATE TB_Produtos SET qtde_estoque = qtde_estoque $logica $qtde WHERE produto_id = $produto_id";
//echo $sql;die;

$stmt = sqlsrv_query($conn, $sql);

if ($stmt) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao executar a query: ' . print_r(sqlsrv_errors(), true)]);
}

?>