<?php
include '../../includes/conexao.php';

$cpfCnpj = $_POST['cpfCnpj'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];

$sql = "SELECT TOP 1 * FROM TB_Clientes WHERE cliente_cpf = '$cpfCnpj'";
$stmt = sqlsrv_query($conn, $sql);

if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo json_encode(['error' => 'Consumidor já cadastrado.']);
} else {
    $sql = "INSERT INTO TB_Clientes (cliente_cpf, cliente_nome, cliente_email, cliente_telefone) VALUES ('$cpfCnpj', '$nome', '$email', '$telefone')";
    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt) {
        echo json_encode(['success' => 'Consumidor salvo com sucesso.']);
    } else {
        echo json_encode(['error' => 'Erro ao salvar consumidor.']);
    }
}
?>