<?php
include '../../includes/conexao.php';

$cliente_id = $_POST['cliente_id'];
$produtos = $_POST['produtos'];
$valorTotalProdutos = $_POST['valorTotalProdutos'];
$valorTotalImpostos = $_POST['valorTotalImpostos'];
$valorTotalPedido = $_POST['valorTotalPedido'];

$sqlInsertVenda = "INSERT INTO TB_Vendas_Realizadas (venda_valor_produtos, venda_valor_imposto, venda_valor_total, cliente_id) VALUES ('$valorTotalProdutos', '$valorTotalImpostos', '$valorTotalPedido', '$cliente_id')";
//echo $sqlInsertVenda;die;
$stmtInsertVenda = sqlsrv_query($conn, $sqlInsertVenda);
if ($stmtInsertVenda === false) {
    throw new Exception('Erro ao inserir venda.');
}

$sqlConsultaUltimoId = "SELECT TOP 1 venda_id FROM TB_Vendas_Realizadas ORDER BY venda_id DESC";
$stmtConsultaUltimoId = sqlsrv_query($conn, $sqlConsultaUltimoId);
$row = sqlsrv_fetch_array($stmtConsultaUltimoId, SQLSRV_FETCH_ASSOC);
$venda_id = $row['venda_id'];

foreach ($produtos as $produto) {
    $produto_id = $produto['produto_id'];
    $produto_sku = $produto['produto_sku'];
    $produto_descricao = $produto['produto_descricao'];
    $produto_qtde = $produto['produto_qtde'];
    $produto_valor_unidade = $produto['produto_valor_unidade'];
    $produto_valor_total = $produto['produto_valor_total'];
    $desconto = $produto['desconto'];
    $mod_desconto = $produto['mod_desconto'];
    $produto_valor_imposto = $produto['produto_valor_imposto'];
    $sqlInsertItens = "INSERT INTO TB_Itens_Vendas_Realizadas (venda_id, produto_id, produto_sku, produto_descricao, produto_qtde,
                       produto_valor_unidade, produto_valor_total, desconto, mod_desconto, produto_valor_imposto)
                       VALUES ('$venda_id', '$produto_id', '$produto_sku', '$produto_descricao', '$produto_qtde', '$produto_valor_unidade',
                       '$produto_valor_total', '$desconto', '$mod_desconto', '$produto_valor_imposto')";
    //echo $sqlInsertItens;die;
    $stmtInsertItens = sqlsrv_query($conn, $sqlInsertItens);
    if ($stmtInsertItens === false) {
        throw new Exception('Erro ao inserir itens da venda.');
    }
}

$sqlRemoveItensCarrinho = "DELETE FROM TB_Temp_Venda";
$stmtRemoveItensCarrinho = sqlsrv_query($conn, $sqlRemoveItensCarrinho);

header('Content-Type: application/json');
echo json_encode(array('success' => true, 'venda_id' => $venda_id));
?>