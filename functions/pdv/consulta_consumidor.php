<?php
include '../../includes/conexao.php';

$query = $_GET['query'];

$sql = "SELECT TOP 1 * FROM TB_Clientes WHERE cliente_cpf = '$query'";
$stmt = sqlsrv_query($conn, $sql);

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($row) {
    $user = [
        'cliente_id' => $row['cliente_id'],
        'cliente_cpf' => $row['cliente_cpf'],
        'cliente_nome' => $row['cliente_nome'],
        'cliente_email' => $row['cliente_email'],
        'cliente_telefone' => $row['cliente_telefone']
    ];

    echo json_encode($user);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Consumidor não encontrado']);
}
?>