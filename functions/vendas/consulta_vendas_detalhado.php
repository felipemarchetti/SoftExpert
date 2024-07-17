<?php

include '../../includes/conexao.php';

$venda_id = $_POST['venda_id'];

$sql = "SELECT venda_id, produto_sku, produto_descricao, produto_qtde, produto_valor_unidade, desconto, mod_desconto, produto_valor_imposto, produto_valor_total FROM TB_Itens_Vendas_Realizadas WHERE venda_id = $venda_id";
$stmt = sqlsrv_query($conn, $sql);

$vendas = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $produto_valor_unidade = number_format($row['produto_valor_unidade'], 2, '.', ''); 
    $produto_valor_imposto = number_format($row['produto_valor_imposto'], 2, '.', ''); 
    $produto_valor_total = number_format($row['produto_valor_total'], 2, '.', ''); 
    $venda = [
        'venda_id' => $row['venda_id'],
        'produto_sku' => $row['produto_sku'],
        'produto_descricao' => $row['produto_descricao'],
        'produto_qtde' => $row['produto_qtde'],
        'produto_valor_unidade' => $produto_valor_unidade,
        'desconto' => $row['desconto'],
        'mod_desconto' => $row['mod_desconto'],
        'produto_valor_imposto' => $produto_valor_imposto,
        'produto_valor_total' => $produto_valor_total
    ];
    $vendas[] = $venda;
}

$response = array('data' => $vendas);

header('Content-Type: application/json');
echo json_encode($response);

?>