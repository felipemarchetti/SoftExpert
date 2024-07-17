<?php
include '../../includes/conexao.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$temp_id = $_GET['temp_id'];

$sql = "SELECT TB_Temp_Venda.temp_id, TB_Temp_Venda.produto_id, TB_Temp_Venda.produto_sku, TB_Temp_Venda.produto_descricao, TB_Temp_Venda.produto_qtde, TB_Produtos.qtde_estoque, TB_Temp_Venda.produto_valor_unidade, TB_Temp_Venda.produto_valor_total, TB_Temp_Venda.desconto, TB_Temp_Venda.mod_desconto, TB_Temp_Venda.produto_valor_imposto, valor_porcentagem_imposto FROM TB_Temp_Venda INNER JOIN TB_Produtos ON TB_Produtos.produto_id = TB_Temp_Venda.produto_id INNER JOIN TB_Tipo_Produto ON TB_Tipo_Produto.tipo_id = TB_Produtos.produto_tipo WHERE TB_Temp_Venda.temp_id = $temp_id";
//echo $sql;die;
$stmt = sqlsrv_query($conn, $sql);

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($row) {
    $produto_valor_unidade = number_format($row['produto_valor_unidade'], 2, '.', ''); 
    $produto_valor_total = number_format($row['produto_valor_total'], 2, '.', ''); 
    $produto_valor_imposto = number_format($row['produto_valor_imposto'], 2, '.', ''); 
    $desconto = number_format($row['desconto'], 2, '.', ''); 
    $valor_porcentagem_imposto = number_format($row['valor_porcentagem_imposto'], 2, '.', ''); 

    $produto = [
        'temp_id' => $row['temp_id'],
        'produto_id' => $row['produto_id'],
        'produto_sku' => $row['produto_sku'],
        'produto_descricao' => $row['produto_descricao'],
        'produto_qtde' => $row['produto_qtde'],
        'qtde_estoque' => $row['qtde_estoque'],
        'produto_valor_unidade' => $produto_valor_unidade,
        'produto_valor_total' => $produto_valor_total,
        'desconto' => $desconto,
        'mod_desconto' => $row['mod_desconto'],
        'produto_valor_imposto' => $produto_valor_imposto,
        'valor_porcentagem_imposto' => $valor_porcentagem_imposto
    ];
}

header('Content-Type: application/json');
echo json_encode($produto);
?>