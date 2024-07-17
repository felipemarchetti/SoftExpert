<?php

include '../../includes/conexao.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$sql = "SELECT venda_id, cliente_nome, venda_valor_produtos, venda_valor_imposto, venda_valor_total FROM TB_Vendas_Realizadas INNER JOIN TB_Clientes ON TB_Clientes.cliente_id = TB_Vendas_Realizadas.cliente_id";
$stmt = sqlsrv_query($conn, $sql);

$vendas = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $venda_valor_produtos = number_format($row['venda_valor_produtos'], 2, '.', ''); 
    $venda_valor_imposto = number_format($row['venda_valor_imposto'], 2, '.', ''); 
    $venda_valor_total = number_format($row['venda_valor_total'], 2, '.', ''); 
    $venda = [
        'venda_id' => $row['venda_id'],
        'cliente_nome' => $row['cliente_nome'],
        'venda_valor_produtos' => $venda_valor_produtos,
        'venda_valor_imposto' => $venda_valor_imposto,
        'venda_valor_total' => $venda_valor_total
    ];
    $vendas[] = $venda;
}

header('Content-Type: application/json');
echo json_encode($vendas);

?>