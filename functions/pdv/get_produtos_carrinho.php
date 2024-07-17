<?php

include '../../includes/conexao.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$produto_id = $_GET['produto_id'];

if ($produto_id != 'null') {
    $sql = "SELECT * FROM TB_Temp_Venda WHERE produto_id = $produto_id";
    //echo $sql;die;
} else {
    $sql = "SELECT * FROM TB_Temp_Venda";
    //echo $sql;die;
}

$stmt = sqlsrv_query($conn, $sql);

$produtos = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $produto_valor_unidade = number_format($row['produto_valor_unidade'], 2, '.', ''); 
    $produto_valor_total = number_format($row['produto_valor_total'], 2, '.', ''); 
    $desconto = number_format($row['desconto'], 2, '.', ''); 
    $produto_valor_imposto = number_format($row['produto_valor_imposto'], 2, '.', ''); 
    $produto = [
        'temp_id' => $row['temp_id'],
        'produto_id' => $row['produto_id'],
        'produto_sku' => $row['produto_sku'],
        'produto_descricao' => $row['produto_descricao'],
        'produto_qtde' => $row['produto_qtde'],
        'produto_valor_unidade' => $produto_valor_unidade,
        'produto_valor_total' => $produto_valor_total,
        'desconto' => $desconto,
        'mod_desconto' => $row['mod_desconto'],
        'produto_valor_imposto' => $produto_valor_imposto
    ];
    $produtos[] = $produto;
}

header('Content-Type: application/json');
echo json_encode($produtos);

?>
