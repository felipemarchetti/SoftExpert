<?php

include '../../includes/conexao.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$produto_id = $_POST['produto_id'];
$sku = $_POST['sku'];
$descricao = $_POST['descricao'];
$qtde = $_POST['qtde'];
$valor_unitario_bruto = $_POST['valor_unitario_bruto'];
$valor_total_bruto = $_POST['valor_total_bruto'];
$desconto = str_replace(',', '.', $_POST['desconto']);
$modDesconto = $_POST['modDesconto'];
$imposto = str_replace(',', '.', $_POST['imposto']);

if($modDesconto == 'descontoDinheiro'){
    $modDesconto = 'R$';
}

if($modDesconto == 'descontoPorcentagem'){
    $modDesconto = '%';
}

$sql = "INSERT INTO TB_Temp_Venda (produto_id, produto_sku, produto_descricao, produto_qtde, produto_valor_unidade, produto_valor_total, desconto, mod_desconto, produto_valor_imposto) VALUES ('$produto_id', '$sku', '$descricao', '$qtde', '$valor_unitario_bruto', '$valor_total_bruto', '$desconto', '$modDesconto', '$imposto')";
//echo $sql;die;

$stmt = sqlsrv_query($conn, $sql);

if ($stmt) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao executar a query: ' . print_r(sqlsrv_errors(), true)]);
}

?>