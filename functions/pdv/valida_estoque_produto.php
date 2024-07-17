<?php

include '../../includes/conexao.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$produto_id = $_POST['produto_id'];
$qtde = $_POST['qtde'];
$editaProduto = $_GET['editaProduto'];

$temp_id = $_POST['temp_id'];

if ($editaProduto == 'true'){
    $sqlVerificaProduto = "SELECT COUNT(TB_Temp_Venda.produto_id) AS qtdePrdId FROM TB_Temp_Venda WHERE TB_Temp_Venda.produto_id = $produto_id";
    $stmtVerificaProduto = sqlsrv_query($conn, $sqlVerificaProduto);
    $rowVerificaProduto = sqlsrv_fetch_array($stmtVerificaProduto, SQLSRV_FETCH_ASSOC);
    if ($rowVerificaProduto['qtdePrdId'] > '0') {
        $operacao = '';
        $somaQtde = '';
    }else{
        $somaQtde = '+ ' . $qtde;
        $operacao = 'AND temp_id = ' . $temp_id;
    }
} else{
    $somaQtde = '+ ' . $qtde;
    $operacao = '';
}

//var_dump($_POST);

$sqlConsultaQtde = "SELECT ISNULL(SUM(TB_Temp_Venda.produto_qtde), 0) $somaQtde AS qtde_temp, TB_Produtos.qtde_estoque FROM TB_Produtos LEFT JOIN TB_Temp_Venda ON TB_Produtos.produto_id = TB_Temp_Venda.produto_id WHERE TB_Produtos.produto_id = $produto_id $operacao GROUP BY TB_Produtos.qtde_estoque";
//echo $sqlConsultaQtde;die;
$stmtConsultaQtde = sqlsrv_query($conn, $sqlConsultaQtde);
$row = sqlsrv_fetch_array($stmtConsultaQtde, SQLSRV_FETCH_ASSOC);

if ($row) {
    $result = [
        'qtde_temp' => $row['qtde_temp'],
        'qtde_estoque' => $row['qtde_estoque']
    ];
}

header('Content-Type: application/json');
echo json_encode($result);
?>