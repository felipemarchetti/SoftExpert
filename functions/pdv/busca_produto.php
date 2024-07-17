<?php

include '../../includes/conexao.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$query = $_GET['query'];

$sql = "SELECT TOP 1 * FROM TB_Produtos INNER JOIN TB_Tipo_Produto ON TB_Tipo_Produto.tipo_id = TB_Produtos.produto_tipo WHERE produto_id LIKE '%$query%' OR produto_sku LIKE '%$query%' OR produto_descricao LIKE '%$query%'";
$stmt = sqlsrv_query($conn, $sql);

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($row) {
    $produto_valor = number_format($row['produto_valor'], 2, '.', ''); 
    $valor_porcentagem_imposto = number_format($row['valor_porcentagem_imposto'], 2, '.', ''); 
    $valor_imposto_produto = ($produto_valor * $valor_porcentagem_imposto) / 100; 
    $product = [
        'produto_id' => $row['produto_id'],
        'produto_descricao' => $row['produto_descricao'],
        'produto_sku' => $row['produto_sku'],
        'qtde_estoque' => $row['qtde_estoque'],
        'produto_valor' => $produto_valor,
        'valor_porcentagem_imposto' => $valor_porcentagem_imposto,
        'valor_imposto_produto' => $valor_imposto_produto
    ];

    echo json_encode($product);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Produto não encontrado']);
}

?>