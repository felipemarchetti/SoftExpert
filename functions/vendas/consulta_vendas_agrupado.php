<?php

include '../../includes/conexao.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$sql = "SELECT TB_Clientes.cliente_id, cliente_nome, COUNT(venda_id) AS qtde_vendas_cliente FROM TB_Vendas_Realizadas INNER JOIN TB_Clientes ON TB_Vendas_Realizadas.cliente_id = TB_Clientes.cliente_id GROUP BY TB_Clientes.cliente_id, cliente_nome";
$stmt = sqlsrv_query($conn, $sql);

$vendas = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $venda = [
        'cliente_id' => $row['cliente_id'],
        'cliente_nome' => $row['cliente_nome'],
        'qtde_vendas_cliente' => $row['qtde_vendas_cliente']
    ];
    $vendas[] = $venda;
}

header('Content-Type: application/json');
echo json_encode($vendas);

?>