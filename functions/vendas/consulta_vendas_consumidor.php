<?php

include '../../includes/conexao.php';

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

function formatCnpjCpf($value) {
    $cnpj_cpf = preg_replace("/\D/", '', $value);
  
    if (strlen($cnpj_cpf) === 11) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    } 
  
    return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
}

function telefone($n)
{
    $tam = strlen(preg_replace("/[^0-9]/", "", $n));
    
    if ($tam == 13) { // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS e 9 dígitos
        return "+".substr($n, 0, $tam-11)." (".substr($n, $tam-11, 2).") ".substr($n, $tam-9, 5)."-".substr($n, -4);
    }
    if ($tam == 12) { // COM CÓDIGO DE ÁREA NACIONAL E DO PAIS
        return "+".substr($n, 0, $tam-10)." (".substr($n, $tam-10, 2).") ".substr($n, $tam-8, 4)."-".substr($n, -4);
    }
    if ($tam == 11) { // COM CÓDIGO DE ÁREA NACIONAL e 9 dígitos
        return " (".substr($n, 0, 2).") ".substr($n, 2, 5)."-".substr($n, 7, 11);
    }
    if ($tam == 10) { // COM CÓDIGO DE ÁREA NACIONAL
        return " (".substr($n, 0, 2).") ".substr($n, 2, 4)."-".substr($n, 6, 10);
    }
    if ($tam <= 9) { // SEM CÓDIGO DE ÁREA
        return substr($n, 0, $tam-4)."-".substr($n, -4);
    }
}

$cliente_id = $_POST['cliente_id'];

$sql = "SELECT cliente_nome, cliente_cpf, cliente_email, cliente_telefone, venda_id, venda_valor_produtos, venda_valor_imposto, venda_valor_total FROM TB_Vendas_Realizadas INNER JOIN TB_Clientes ON TB_Clientes.cliente_id = TB_Vendas_Realizadas.cliente_id  WHERE TB_Vendas_Realizadas.cliente_id = $cliente_id";
$stmt = sqlsrv_query($conn, $sql);

$vendas = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $venda_valor_produtos = number_format($row['venda_valor_produtos'], 2, '.', ''); 
    $venda_valor_imposto = number_format($row['venda_valor_imposto'], 2, '.', ''); 
    $venda_valor_total = number_format($row['venda_valor_total'], 2, '.', ''); 
    $cliente_cpf = formatCnpjCpf($row['cliente_cpf']); 
    $cliente_telefone = telefone($row['cliente_telefone']); 
    $venda = [
        'cliente_nome' => $row['cliente_nome'],
        'cliente_cpf' => $cliente_cpf,
        'cliente_email' => $row['cliente_email'],
        'cliente_telefone' => $cliente_telefone,
        'venda_id' => $row['venda_id'],
        'venda_valor_produtos' => $venda_valor_produtos,
        'venda_valor_imposto' => $venda_valor_imposto,
        'venda_valor_total' => $venda_valor_total
    ];
    $vendas[] = $venda;
}

header('Content-Type: application/json');
echo json_encode($vendas);

?>