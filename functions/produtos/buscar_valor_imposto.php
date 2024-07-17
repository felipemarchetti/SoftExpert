<?php

include '../../includes/conexao.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$tipo_id = $_GET['tipoProduto'];

$sql = "SELECT valor_porcentagem_imposto FROM TB_Tipo_Produto WHERE tipo_id = $tipo_id";
$stmt = sqlsrv_query($conn, $sql);

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$valorImposto = $row['valor_porcentagem_imposto'];

echo $valorImposto;

?>