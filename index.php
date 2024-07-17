<?php

include 'includes/conexao.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$sql_produtos = "SELECT produto_id, produto_sku, produto_descricao, produto_tipo, tipo_descricao, produto_valor, valor_porcentagem_imposto, qtde_estoque FROM TB_Produtos INNER JOIN TB_Tipo_Produto ON TB_Tipo_Produto.tipo_id = TB_Produtos.produto_tipo";
$exec_prd = sqlsrv_query($conn, $sql_produtos);

$sql_tipo_prd = "SELECT * FROM TB_Tipo_Produto ORDER BY tipo_descricao ASC";
$exec_tipo = sqlsrv_query($conn, $sql_tipo_prd);

$tipos_produto = [];
while ($row = sqlsrv_fetch_array($exec_tipo, SQLSRV_FETCH_ASSOC)) {
    $tipos_produto[] = $row;
}

$sql_vendas_agrupado = "SELECT TB_Clientes.cliente_id, cliente_nome, COUNT(venda_id) AS qtde_vendas_cliente FROM TB_Vendas_Realizadas INNER JOIN TB_Clientes ON TB_Vendas_Realizadas.cliente_id = TB_Clientes.cliente_id GROUP BY TB_Clientes.cliente_id, cliente_nome";
$exec_vendas_agrupado = sqlsrv_query($conn, $sql_vendas_agrupado);

?>

<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Teste PHP - SoftExpert</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="css/tooplate-style.css">
    <link rel="stylesheet" href="css/estilo.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">

</head>

<body>
    <div id="tm-bg">
    </div>
    <div id="tm-wrap">
        <div class="tm-main-content" id="mainContent">
            <div class="container tm-site-header-container">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6 col-md-col-xl-6 mb-md-0 mb-sm-4 mb-4 tm-site-header-col">
                        <div class="tm-site-header">
                            <a class="mb-4" href="https://www.softexpert.com/pt-br/" target="_blank"><img src="img/softexpert-logo.png" width="450" height="150"></a>
                            <img src="img/underline.png" class="img-fluid mb-4">
                            <p>Desafio Técnico PHP - Referente à vaga<br><strong>Pessoa Desenvolvedora Full Stack Pleno</strong></p>
                        </div>                        
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="content">
                            <div class="grid">

                                <!-- Produtos -->
                                <div class="grid__item">
                                    <div class="product" id="gridProdutos">
                                        <div class="tm-nav-link">
                                            <i class="fa fa-plus-circle fa-3x tm-nav-icon"></i>
                                            <span class="tm-nav-text">Produtos</span>
                                            <div class="product__bg"></div>        
                                        </div>
                                        <div id="fixStyle" style="display: none;">
                                            <div class="product__description">
                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h4 class="bold-text">Cadastro de Produtos</h4>
                                                            <button class="btn btn-outline-success custom-btn button" style="font-weight: 500;" onclick="abrirModalCriacaoProduto()">Novo Produto</button>
                                                        </div>
                                                    </div>
                                                </div>
    
                                                <hr/>
    
                                                <div class="row mb-3 d-flex align-items-center tarefa">
                                                    <div class="col-sm bold-text">SKU</div>
                                                    <div class="col-sm bold-text">Descrição</div>
                                                    <div class="col-sm bold-text">Tipo</div>
                                                    <div class="col-sm bold-text">Valor Venda</div>
                                                    <div class="col-sm bold-text text-right">Qtde Estoque</div>
                                                    <div class="col-sm bold-text text-center">Ações</div>
                                                </div>
    
                                                <hr/>
    
                                                <div class="scrollBar">
                                                    <?php while ($row = sqlsrv_fetch_array($exec_prd, SQLSRV_FETCH_ASSOC)) { ?>
                                                        <div class="row mb-3 d-flex align-items-center tarefa">
                                                            <div class="col-sm" id="prd_id_<?= $row['produto_id'] ?>"><?= $row['produto_sku'] ?></div>
                                                            <div class="col-sm" id="prd_id_<?= $row['produto_id'] ?>_descricao"><?= $row['produto_descricao'] ?></div>
                                                            <div class="col-sm" id="prd_id_<?= $row['produto_id'] ?>_tipo"><?= $row['tipo_descricao'] ?></div>
                                                            <div class="col-sm" id="prd_id_<?= $row['produto_id'] ?>_valor">R$ <?= number_format($row['produto_valor'], 2, ',', '') ?></div>
                                                            <div class="col-sm text-right" id="prd_id_<?= $row['produto_id'] ?>_qtde"><?= $row['qtde_estoque'] ?></div>
                                                            <div class="col-sm d-flex justify-content-center align-items-center">
                                                                <i class="fas fa-edit fa-lg text-light mr-2" onclick="
                                                                    editarProduto(
                                                                        <?= $row['produto_id'] ?>,
                                                                        '<?= $row['produto_sku'] ?>',
                                                                        '<?= $row['produto_descricao'] ?>',
                                                                        '<?= $row['produto_tipo'] ?>',
                                                                        '<?= $row['produto_valor'] ?>',
                                                                        '<?= $row['qtde_estoque'] ?>'
                                                                    )" title="Editar"></i>
                                                                <i class="fas fa-trash-alt fa-lg text-danger ml-2" onclick="removerProduto(<?= $row['produto_id'] ?>,'<?= $row['produto_descricao'] ?>')" title="Excluir"></i>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tipos de Produtos -->
                                <div class="grid__item">
                                    <div class="product">
                                        <div class="tm-nav-link">
                                            <i class="fa fa-th fa-3x tm-nav-icon"></i>
                                            <span class="tm-nav-text">Tipos de Produtos</span>
                                            <div class="product__bg"></div>
                                        </div>                                  
                                        <div class="product__description">
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h4 class="bold-text">Cadastro de Tipos de Produtos</h4>
                                                        <button class="btn btn-outline-success custom-btn button" style="font-weight: 500;" onclick="abrirModalCriacaoTipo()">Novo Tipo de Produto</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr/>

                                            <div class="row mb-3 d-flex align-items-center tarefa">
                                                <div class="col-sm bold-text">Descrição</div>
                                                <div class="col-sm bold-text text-right">Valor de Imposto (%)</div>
                                                <div class="col-sm bold-text text-center">Ações</div>
                                            </div>

                                            <hr/>

                                            <div class="scrollBar">
                                                <?php foreach ($tipos_produto as $row) { ?>
                                                    <div class="row mb-3 d-flex align-items-center tarefa">
                                                        <div class="col-sm" id="tipo_id_<?= $row['tipo_id'] ?>_descricao"><?= $row['tipo_descricao'] ?></div>
                                                        <div class="col-sm text-right" id="tipo_id_<?= $row['tipo_id'] ?>_valor"><?= number_format($row['valor_porcentagem_imposto'], 2, ',', '') ?>%</div>
                                                        <div class="col-sm d-flex justify-content-center align-items-center">
                                                            <i class="fas fa-edit fa-lg text-light mr-2" onclick="
                                                                editarTipo(
                                                                    '<?= $row['tipo_id'] ?>',
                                                                    '<?= $row['tipo_descricao'] ?>',
                                                                    '<?= $row['valor_porcentagem_imposto'] ?>'
                                                                )" title="Editar"></i>
                                                            <i class="fas fa-trash-alt fa-lg text-danger ml-2" onclick="removerTipo(<?= $row['tipo_id'] ?>,'<?= $row['tipo_descricao'] ?>')" title="Excluir"></i>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PDV -->
                                <div class="grid__item">
                                    <div class="product" onclick="openPdvModal()">
                                        <div class="tm-nav-link">
                                            <i class="fa fa-shopping-cart fa-3x tm-nav-icon"></i>
                                            <span class="tm-nav-text">PDV</span>
                                            <div class="product__bg"></div>
                                        </div>
                                        <div class="product__description">
                                        </div>
                                    </div>
                                </div>

                                <!-- Vendas Realizadas -->
                                <div class="grid__item">
                                    <div class="product" onclick="abreModalVendas()">
                                        <div class="tm-nav-link">
                                            <i class="fas fa-users fa-3x tm-nav-icon"></i>
                                            <span class="tm-nav-text">Vendas Realizadas</span>
                                            <div class="product__bg"></div>
                                        </div>
                                        <div class="product__description">                                            
                                        </div>
                                    </div>
                                </div>

                            </div> 
                        </div>                       
                    </div>
                </div>
                
                <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content details__bg--down">
                            <div class="modal-header">
                                <h5 class="modal-title bold-text" id="createModalLabel">Novo Produto</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span class="buttonClose" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="lowFont" for="createSKU">SKU</label>
                                    <input type="text" class="form-control" id="createSKU" required>
                                </div>
                                <div class="form-group">
                                    <label class="lowFont" for="createDescricao">Descrição</label>
                                    <input type="text" class="form-control" id="createDescricao" required>
                                </div>
                                <div class="form-group">
                                    <label class="lowFont" for="createTipo">Tipo de Produto</label>
                                    <select class="form-control lowFont" id="createTipo" required>
                                        <option value="0" selected hidden>Selecione</option>
                                            <?php foreach ($tipos_produto as $row) { ?>
                                                <option value="<?= $row['tipo_id'] ?>"><?= $row['tipo_descricao'] ?></option>
                                            <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group prohibited">
                                    <label class="lowFont" for="createValorImposto">Valor Imposto (%)</label>
                                    <span class="form-control" id="createValorImposto"></span>
                                </div>
                                <div class="form-group">
                                    <label class="lowFont" for="createValorVenda">Valor Venda</label>
                                    <input type="text" class="form-control" id="createValorVenda" placeholder="0,00" required>
                                </div>
                                <div class="form-group">
                                    <label class="lowFont" for="createQtde">Qtde Estoque</label>
                                    <input type="number" min="0" step="1" class="form-control" id="createQtde" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="button btn btn-secondary" data-dismiss="modal">Voltar</button>
                                <button type="button" class="button btn btn-success text-white" onclick="criarProduto()">Criar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content details__bg--down">
                            <div class="modal-header">
                                <h5 class="modal-title bold-text" id="editModalLabel">Editar Produto</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                    <span class="buttonClose" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="editProdutoId">
                                <div class="form-group">
                                    <label class="lowFont" for="editSKU">SKU</label>
                                    <input type="text" class="form-control" id="editSKU">
                                </div>
                                <div class="form-group">
                                    <label class="lowFont" for="editDescricao">Descrição</label>
                                    <input type="text" class="form-control" id="editDescricao">
                                </div>
                                <div class="form-group">
                                    <label class="lowFont" for="editTipo">Tipo de Produto</label>
                                    <select class="form-control lowFont" id="editTipo">
                                        <?php foreach ($tipos_produto as $row) { ?>
                                            <option value="<?= $row['tipo_id'] ?>"><?= $row['tipo_descricao'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group prohibited">
                                    <label class="lowFont" for="editValorImposto">Valor Imposto (%)</label>
                                    <span class="form-control" id="editValorImposto"></span>
                                </div>
                                <div class="form-group">
                                    <label class="lowFont" for="editValorVenda">Valor Venda</label>
                                    <input type="text" class="form-control" id="editValorVenda" placeholder="0,00" required>
                                </div>
                                <div class="form-group">
                                    <label class="lowFont" for="editQtde">Qtde Estoque</label>
                                    <input type="number" min="0" step="1" class="form-control" id="editQtde" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="button btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="button" class="button btn btn-success" onclick="salvarEdicaoModalProduto()">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="createModalTipo" tabindex="-1" role="dialog" aria-labelledby="createModalTipoLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content details__bg--down">
                            <div class="modal-header">
                                <h5 class="modal-title bold-text" id="createModalTipoLabel">Novo Tipo de Produto</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span class="buttonClose" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="lowFont" for="createDescricaoTipo">Descrição</label>
                                    <input type="text" class="form-control" id="createDescricaoTipo" required>
                                </div>
                                <div class="form-group">
                                    <label class="lowFont" for="createValorImpostoTipo">Valor de Imposto (%)</label>
                                    <input type="text" class="form-control" id="createValorImpostoTipo" placeholder="0,00" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="button btn btn-secondary" data-dismiss="modal">Voltar</button>
                                <button type="button" class="button btn btn-success text-white" onclick="criarTipo()">Criar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editModalTipo" tabindex="-1" role="dialog" aria-labelledby="editModalTipoLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content details__bg--down">
                            <div class="modal-header">
                                <h5 class="modal-title bold-text" id="editModalTipoLabel">Editar Produto</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                    <span class="buttonClose" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="editTipoProdutoId">
                                <div class="form-group">
                                    <label class="lowFont" for="editTipoDescricao">Descrição</label>
                                    <input type="text" class="form-control" id="editTipoDescricao">
                                </div>
                                <div class="form-group">
                                    <label class="lowFont" for="editTipoValorImposto">Valor de Imposto (%)</label>
                                    <input type="text" class="form-control" id="editTipoValorImposto" placeholder="0,00" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="button btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="button" class="button btn btn-success" onclick="salvarEdicaoModalTipo()">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="pdvModal" tabindex="-1" role="dialog" aria-labelledby="pdvModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content details__bg--down">
                            <div class="row">
                            
                                <div class="col-md-5" id="esquerda">
                                    <div class="form-group row" id="consultaProduto">
                                        <div class="col-md-11">
                                            <input type="text" class="form-control bgColorDark widthCorrection" id="search-input" placeholder="Busque por SKU | Descrição | ID Interno">
                                        </div>
                                        <div class="col-md-1">
                                            <button id="search-button" class="form-control bgColorDark custom-button-height" onclick="buscaProduto()"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="product-details">
                                        <input type="hidden" name="produtoIdPdv" id="produtoIdPdv">
                                        <div class="form-group">
                                            <label class="marginLabel" for="descricaoPdv">Descrição:</label>
                                            <input type="text" id="descricaoPdv" class="form-control prohibited bgColorDark" readonly disabled>
                                        </div>
                                        <div class="form-group">
                                            <label class="marginLabel" for="skuPdv">SKU:</label>
                                            <input type="text" id="skuPdv" class="form-control prohibited bgColorDark" readonly disabled>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label class="marginLabel" for="estoquePdv">Estoque Atual:</label>
                                                <input type="text" id="estoquePdv" class="form-control prohibited bgColorDark" readonly disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <label class="marginLabel" for="impostoPdv">Imposto</label><span class="textRed lowFont">*(%)</span>
                                                </div>
                                                <input type="number" id="impostoPdv" class="form-control prohibited bgColorDark" readonly disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <label class="marginLabel" for="impostoValorPdv">Imposto</label><span class="textRed lowFont">*(R$)</span>
                                                </div>
                                                <input type="number" id="impostoValorPdv" class="form-control prohibited bgColorDark" readonly disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="marginLabel" for="qtdePdv">Qtde:</label>
                                                <input type="number" min="1" id="qtdePdv" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-5">
                                                <label class="marginLabel" for="valor_unitario_brutoPdv">Valor Unitário:</label>
                                                <input type="number" id="valor_unitario_brutoPdv" class="form-control prohibited bgColorDark" readonly disabled>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <label class="marginLabel" for="descontoPdv">Desconto:</label><span class="textRed lowFont">*aplicado ao Total Bruto</span>
                                                </div>
                                                <input type="text" id="descontoPdv" class="form-control" placeholder="0,00">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="marginLabel" for="opcaoDescontoPdv" class="lowFont">Tipo</label>
                                                <select name="opcaoDescontoPdv" id="opcaoDescontoPdv" class="form-control lowFont custom-select-height">
                                                    <option value="descontoDinheiro">R$</option>
                                                    <option value="descontoPorcentagem">%</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label class="marginLabel" for="valor_unitario_liquidoPdv">Valor Un * Qtde:</label>
                                                <input type="number" id="valor_unitario_liquidoPdv" class="form-control prohibited bgColorDark" readonly disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="marginLabel" for="valor_total_brutoPdv">Valor Total Bruto:</label>
                                                <input type="number" id="valor_total_brutoPdv" class="form-control prohibited bgColorDark" readonly disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <label class="marginLabel" for="valor_total_liquidoPdv">Total:</label><span class="textRed lowFont">*T. Bruto + Imp. (R$)</span>
                                                </div>
                                                <input type="number" id="valor_total_liquidoPdv" class="form-control prohibited bgColorDark" readonly disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="action-buttons d-flex justify-content-between" id="botoesTempPdv">
                                        <button class="btn btn-secondary" onclick="limpaPdv()">LIMPAR</button>
                                        <button class="btn btn-primary" onclick="addProdutoCarrinho()">ADICIONAR PRODUTO</button>
                                    </div>
                                </div>

                                <div class="col-md-7 d-flex flex-column" id="direita">
                                    <div class="vendedor-consumidor d-flex justify-content-between align-items-center">
                                        <h3 class="font-weight-bold">PDV ONLINE</h3>
                                        <a href="https://www.softexpert.com/pt-br/" target="_blank" class="marginRightCorrectionPositive"><img src="img/softexpert-logo2.png"></a>
                                    </div>
                                    <strong class="font-weight-bold"><p>Consumidor: </strong><span onclick="selecionaConsumidor()" class="textBlue" id="consumidorSelecionado">Selecione</span></p>
                                    <div class="product-list flex-grow-1 maxWidthTable">
                                        <div class="scrollBar2">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="">+</th>
                                                        <th style="min-width: 430px;">Descrição</th>
                                                        <th class="text-center">Qtd</th>
                                                        <th class="text-center">R$ un</th>
                                                        <th class="text-center">Desconto</th>
                                                        <th class="text-center">R$ Imp.</th>
                                                        <th class="text-center">R$ Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="total-compra mt-auto">
                                        <hr class="hrWhite">
                                        <div class="d-flex justify-content-between align-items-center gapFix">
                                            <h5>TOTAL PRODUTOS: <span id="valorTotalProdutos" class="text-right">R$ 0,00</span></h5>
                                            <h5>TOTAL IMPOSTOS: <span id="valorTotalImpostos" class="text-right">R$ 0,00</span></h5>
                                            <h5>TOTAL PEDIDO: <span id="valorTotalPedido" class="text-right">R$ 0,00</span></h5>
                                        </div>
                                        <button class="btn btn-success buttonWidth" onclick="finalizarCompraPdv()">FINALIZAR COMPRA</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="selecionaConsumidorModal" tabindex="-1" role="dialog" aria-labelledby="selecionaConsumidorModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content details__bg--down">
                            <div class="modal-header">
                                <h5 class="modal-title bold-text" id="selecionaConsumidorModalLabel">Identificar Consumidor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span class="buttonClose" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="idConsumidor">
                                <div class="form-group">
                                    <label class="lowFont" for="cpfCnpjConsumidor">C.P.F. / C.N.P.J.</label>
                                    <input type="text" class="form-control" id="cpfCnpjConsumidor" required onblur="consultaConsumidor()">
                                </div>
                                <div class="form-group">
                                    <label class="lowFont" for="nomeConsumidor">Nome Completo</label>
                                    <input type="text" class="form-control" id="nomeConsumidor" required>
                                </div>
                                <div class="form-group">
                                    <label class="lowFont" for="emailConsumidor">Email</label>
                                    <input type="mail" class="form-control" id="emailConsumidor" required>
                                </div>
                                <div class="form-group">
                                    <label class="lowFont" for="telefoneConsumidor">Telefone</label>
                                    <input type="text" class="form-control" id="telefoneConsumidor" placeholder="(xx)xxxxx-xxxx" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="button btn btn-secondary" data-dismiss="modal">Voltar</button>
                                <button type="button" class="button btn btn-success text-white" onclick="salvaConsumidor()">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalExibeVendas" tabindex="-1" role="dialog" aria-labelledby="modalExibeVendasLabel" aria-hidden="true">
                    <div class="modal-dialog ajusteWidthModal" role="document">
                        <div class="modal-content details__bg--down">
                            <div class="modal-header">
                                <h5 class="modal-title bold-text" id="modalExibeVendasModalLabel">Vendas Realizadas</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                                    <span class="buttonClose" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="botoesNavList">
                                    <div class="d-flex justify-content-between mb-3">
                                        <button class="btn btn-primary" id="btnAgrupadoPorCliente" onclick="exibirAgrupadoPorCliente()">Agrupadas por Cliente</button>
                                        <button class="btn btn-secondary ml-2" id="btnSemAgrupamento" onclick="exibirSemAgrupamento()">Sem Agrupamento</button>
                                    </div>
                                    
                                    <div id="agrupadoPorCliente" class="scrollBar">
                                        <table id="agrupadoPorClienteTable">
                                            <thead class="tableHeadFixo">
                                                <tr>
                                                    <th>Consumidor</th>
                                                    <th>Qtde</th>
                                                    <th class="text-center">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div id="semAgrupamento" style="display: none;" class="scrollBar">
                                        <table id="semAgrupamentoTable">
                                            <thead class="tableHeadFixo">
                                                <tr>
                                                    <th>Venda</th>
                                                    <th>Consumidor</th>
                                                    <th>Produtos</th>
                                                    <th>Impostos</th>
                                                    <th>Total</th>
                                                    <th class="text-center">+</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>                                                                                                             
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalDetalhesAgrupado" tabindex="-1" role="dialog" aria-labelledby="modalDetalhesAgrupadoLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content details__bg--down">
                            <div class="modal-header">
                                <h5 class="modal-title bold-text" id="modalDetalhesAgrupadoLabel">DETALHES - VENDAS REALIZADAS</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                    <span class="buttonClose" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group heightFix">
                                    <i class="fa fa-user"></i>
                                    <strong><label for="nomeConsumidorVendaAgrupado">Nome: </label></strong>
                                    <span id="nomeConsumidorVendaAgrupado"></span>
                                    
                                </div>
                                <div class="form-group heightFix">
                                    <i class="fa fa-id-card"></i>
                                    <strong><label for="cpfConsumidorVendaAgrupado">CPF: </label></strong>
                                    <span id="cpfConsumidorVendaAgrupado"></span>
                                </div>
                                <div class="form-group heightFix">
                                    <i class="fa fa-envelope"></i>
                                    <strong><label for="emailConsumidorVendaAgrupado">Email: </label></strong>
                                    <span id="emailConsumidorVendaAgrupado"></span>
                                </div>
                                <div class="form-group heightFix heightFixDown">
                                    <i class="fa fa-phone fa-flip-horizontal"></i>
                                    <strong><label for="telConsumidorVendaAgrupado">Telefone: </label></strong>
                                    <span id="telConsumidorVendaAgrupado"></span>
                                </div>
                                
                                
                                
                                <div id="agrupadoPorClienteDetalhes" class="scrollBar">
                                    <table id="vendasClienteDetalhe">
                                        <thead class="tableHeadFixo">
                                            <tr>
                                                <th>Venda</th>
                                                <th>Vl. Produtos</th>
                                                <th>Vl. Imposto</th>
                                                <th>Total</th>
                                                <th class="text-center">+</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalVendaAgrupadoDetalhes" tabindex="-1" role="dialog" aria-labelledby="modalVendaAgrupadoDetalhesLabel" aria-hidden="true">
                    <div class="modal-dialog modal-mg" role="document">
                        <div class="modal-content details__bg--down">
                            <div class="modal-header">
                                <h5 class="modal-title bold-text" id="modalVendaAgrupadoDetalhesLabel">DETALHES - VENDA Nº <span id="numeroVendaDetalhe">x</span></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                    <span class="buttonClose" aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="vendaClienteAgrupadoDetalhe" class="scrollBar">
                                    <table id="vendaClienteAgrupadoTable">
                                        <thead class="tableHeadFixo">
                                            <tr>
                                                <th>SKU</th>
                                                <th>Descrição</th>
                                                <th>R$ Un</th>
                                                <th>Qtde</th>
                                                <th>Desconto</th>
                                                <th>Imposto</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th class="text-center">Totais</th>
                                                <th id="totalQtde"></th>
                                                <th id="totalImposto"></th>
                                                <th></th>
                                                <th id="totalProdutos"></th>
                                                <th id="totalFinal"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <footer>
                <p class="small tm-copyright-text">Felipe Cunha Marchetti | <a rel="nofollow" href="https://www.linkedin.com/in/felipe-cunha-marchetti/" target="_blank" class="tm-text-highlight">LinkedIn</a> | <a rel="nofollow" href="https://github.com/felipecmarchetti" target="_blank" class="tm-text-highlight">GitHub</a></p>
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/anime.min.js"></script>
    <script src="js/main.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>



    <script>
        //Produtos
        document.getElementById('createValorImposto').setAttribute('style', 'color: red; font-size: 0.85rem;'); document.getElementById('createValorImposto').textContent = '*Selecione um Tipo de Produto';
        
        document.getElementById('createTipo').addEventListener('change', function() {
            refreshValorImposto('createTipo', 'createValorImposto');
        });

        document.getElementById('editTipo').addEventListener('change', function() {
            refreshValorImposto('editTipo', 'editValorImposto');
        });

        document.querySelectorAll('#createValorVenda, #editValorVenda, #createValorImpostoTipo, #editTipoValorImposto, #descontoPdv').forEach(function(input) {
            input.addEventListener('input', function(e) {
                var value = e.target.value.replace(/\D/g, '');
                var formattedValue = '';
                
                if (value.length > 0) {
                    if (value.length === 1) {
                        formattedValue = '0,0' + value;
                    } else if (value.length === 2) {
                        formattedValue = '0,' + value;
                    } else {
                        var integerPart = value.slice(0, -2).replace(/^0+/, '');
                        var decimalPart = value.slice(-2);
                        formattedValue = (integerPart.length > 0 ? formatNumberWithDots(integerPart) : '0') + ',' + decimalPart;
                    }
                } else {
                    formattedValue = '0,00';
                }
                
                e.target.value = formattedValue;
            });
        });
        
        function formatNumberWithDots(value) {
            return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function refreshValorImposto(tipoElementId, valorImpostoElementId) {
            var tipoProduto = document.getElementById(tipoElementId).value;

            $.ajax({
                url: 'functions/produtos/buscar_valor_imposto.php',
                method: 'GET',
                data: { tipoProduto: tipoProduto },
                success: function(response) {
                    var valorImposto = parseFloat(response);
                    var valorFormatado = valorImposto.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '%';
                    document.getElementById(valorImpostoElementId).setAttribute('style', 'color: white; font-size: 1rem;');
                    document.getElementById(valorImpostoElementId).textContent = valorFormatado;
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao buscar valor de imposto:', error);
                }
            });
        }

        function abrirModalCriacaoProduto() {
            document.getElementById('createSKU').value = '';
            document.getElementById('createDescricao').value = '';
            document.getElementById('createValorVenda').value = '';
            document.getElementById('createValorImposto').value = '';
            document.getElementById('createQtde').value = '';

            $('#createModal').modal('show');
        }

        function criarProduto() {
            var novoSku = document.getElementById('createSKU').value;
            var novoDescricao = document.getElementById('createDescricao').value;
            var novoTipo = document.getElementById('createTipo').value;
            var novoValorVenda = document.getElementById('createValorVenda').value;
            var novoQtde = document.getElementById('createQtde').value;

            if (novoSku == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, insira um SKU válido.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            if (novoDescricao == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, insira uma descrição válida.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            if (novoTipo == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, selecione um tipo de produto válido.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            if (novoValorVenda == '' || novoValorVenda == '0,00') {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, insira um valor de venda válido.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            if (novoQtde == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, insira uma quantidade válida.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            var data = {
                sku: novoSku,
                descricao: novoDescricao,
                tipo: novoTipo,
                vlVenda: novoValorVenda,
                qtde: novoQtde
            };

            Swal.fire({
                title: "Atenção!",
                text: "Você realmente deseja criar este produto?",
                icon: "warning",
                showCancelButton: true,
                cancelButtonColor: "rgb(221, 51, 51)",
                confirmButtonColor: "#28a745",
                cancelButtonText: "Cancelar",
                confirmButtonText: "Sim"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'functions/produtos/criar_produto.php',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(data),
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Produto criado com sucesso!',
                                    text: '',
                                    confirmButtonColor: "#28a745"
                                }).then(() => {
                                    $('#createModal').modal('hide');
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: 'Erro ao criar produto: ' + data.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Erro na requisição AJAX: ' + error
                            });
                        }
                    });
                }
            });
        }

        function editarProduto(id, skuAtual, descricaoAtual, tipoAtual, valorVendaAtual, qtdeAtual) {

            var valorFormatado = parseFloat(valorVendaAtual).toFixed(2).replace('.', ',');

            document.getElementById('editProdutoId').value = id;
            document.getElementById('editSKU').value = skuAtual;
            document.getElementById('editDescricao').value = descricaoAtual;
            document.getElementById('editTipo').value = tipoAtual;
            document.getElementById('editValorVenda').value = valorFormatado;
            document.getElementById('editQtde').value = qtdeAtual;

            refreshValorImposto('editTipo', 'editValorImposto');

            $('#editModal').modal('show');
        }

        function salvarEdicaoModalProduto() {
            var id = document.getElementById('editProdutoId').value;
            var editarSku = document.getElementById('editSKU').value;
            var editarDescricao = document.getElementById('editDescricao').value;
            var editarTipo = document.getElementById('editTipo').value;
            var editarValorVenda = document.getElementById('editValorVenda').value;
            var editarQtde = document.getElementById('editQtde').value;

            if (editarSku == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, insira um SKU válido.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            if (editarDescricao == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, insira uma descrição válida.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            if (editarTipo == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, selecione um tipo de produto válido.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            if (editarValorVenda == '' || editarValorVenda == '0,00') {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, insira um valor de venda válido.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            if (editarQtde == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, insira uma quantidade válida.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            Swal.fire({
                title: "Você realmente deseja editar este produto?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim"
            }).then((result) => {
                if (result.isConfirmed) {
                    var data = {
                        id: id,
                        sku: editarSku,
                        descricao: editarDescricao,
                        tipo: editarTipo,
                        valorVenda: editarValorVenda,
                        qtde: editarQtde
                    };

                    $.ajax({
                        url: 'functions/produtos/editar_produto.php',
                        method: 'POST',
                        data: JSON.stringify(data),
                        contentType: 'application/json',
                        success: function(response) {
                            response = JSON.parse(response);
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Produto editado com sucesso!',
                                    confirmButtonColor: "#28a745"
                                }).then(() => {
                                    $('#editModal').modal('hide');
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: 'Erro ao editar produto: ' + response.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Erro na requisição AJAX: ' + error
                            });
                        }
                    });
                }
            });
        }

        function removerProduto(produtoId, produtoDescricao) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            Swal.fire({
                title: "Você irá excluir:\n\"" + produtoDescricao + "\"\nTem certeza disso?",
                text: "Esta ação não poderá ser revertida!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sim",
                confirmButtonColor: "rgb(221, 51, 51)",
                cancelButtonText: "Cancelar",
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'functions/produtos/deletar_produto.php',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            id: produtoId
                        }),
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Produto excluído com sucesso!',
                                    confirmButtonColor: "#28a745"
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                swalWithBootstrapButtons.fire({
                                    title: "Erro!",
                                    text: "Erro ao excluir Produto: " + data.message,
                                    icon: "error"
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            swalWithBootstrapButtons.fire({
                                title: "Erro!",
                                text: "Erro na requisição AJAX: " + error,
                                icon: "error"
                            });
                        }
                    });
                }
            });
        }

        //Tipos de Produtos
        function abrirModalCriacaoTipo() {
            document.getElementById('createDescricaoTipo').value = '';
            document.getElementById('createValorImpostoTipo').value = '';

            $('#createModalTipo').modal('show');
        }

        function criarTipo() {
            var novoDescricaoTipo = document.getElementById('createDescricaoTipo').value;
            var novoValorImpostoTipo = document.getElementById('createValorImpostoTipo').value;

            if (novoDescricaoTipo == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, insira uma descrição válida.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            if (novoValorImpostoTipo == '' || novoValorImpostoTipo == '0,00') {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, insira um valor de imposto válido.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            var data = {
                descricaoTipo: novoDescricaoTipo,
                valorImpostoTipo: novoValorImpostoTipo
            };

            Swal.fire({
                title: "Atenção!",
                text: "Você realmente deseja criar este tipo de produto?",
                icon: "warning",
                showCancelButton: true,
                cancelButtonColor: "rgb(221, 51, 51)",
                confirmButtonColor: "#28a745",
                cancelButtonText: "Cancelar",
                confirmButtonText: "Sim"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'functions/tipo_produtos/criar_tipo.php',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(data),
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Tipo de Produto criado com sucesso!',
                                    text: '',
                                    confirmButtonColor: "#28a745"
                                }).then(() => {
                                    $('#createModal').modal('hide');
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: 'Erro ao criar Tipo de Produto: ' + data.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Erro na requisição AJAX: ' + error
                            });
                        }
                    });
                }
            });
        }

        function editarTipo(idTipo, descricaoAtual, valorImpostoAtual) {

            var valorFormatado = parseFloat(valorImpostoAtual).toFixed(2).replace('.', ',');

            document.getElementById('editTipoProdutoId').value = idTipo;
            document.getElementById('editTipoDescricao').value = descricaoAtual;
            document.getElementById('editTipoValorImposto').value = valorFormatado;

            $('#editModalTipo').modal('show');
        }

        function salvarEdicaoModalTipo() {
            var tipoId = document.getElementById('editTipoProdutoId').value;
            var editarTipoDescricao = document.getElementById('editTipoDescricao').value;
            var editarTipoValorImposto = document.getElementById('editTipoValorImposto').value;

            if (editarTipoDescricao == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, insira uma descrição válida.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            if (editarTipoValorImposto == '' || editarTipoValorImposto == '0,00') {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Por favor, insira um valor de venda válido.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            Swal.fire({
                title: "Você realmente deseja editar este Tipo de Produto?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim"
            }).then((result) => {
                if (result.isConfirmed) {
                    var data = {
                        id: tipoId,
                        descricao: editarTipoDescricao,
                        valorImposto: editarTipoValorImposto
                    };

                    $.ajax({
                        url: 'functions/tipo_produtos/editar_tipo_produto.php',
                        method: 'POST',
                        data: JSON.stringify(data),
                        contentType: 'application/json',
                        success: function(response) {
                            response = JSON.parse(response);
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Tipo de Produto editado com sucesso!',
                                    confirmButtonColor: "#28a745"
                                }).then(() => {
                                    $('#editModal').modal('hide');
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: 'Erro ao editar Tipo de Produto: ' + response.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: 'Erro na requisição AJAX: ' + error
                            });
                        }
                    });
                }
            });
        }

        function removerTipo(tipoId, tipoDescricao) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            Swal.fire({
                title: "Você irá excluir:\n\"" + tipoDescricao + "\"\nTem certeza disso?",
                text: "Esta ação não poderá ser revertida!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sim",
                confirmButtonColor: "rgb(221, 51, 51)",
                cancelButtonText: "Cancelar",
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'functions/tipo_produtos/deletar_tipo_produto.php',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            id: tipoId
                        }),
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Tipo de Produto excluído com sucesso!',
                                    confirmButtonColor: "#28a745"
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                swalWithBootstrapButtons.fire({
                                    title: "Erro!",
                                    text: "Erro ao excluir Tipo de Produto: " + data.message,
                                    icon: "error"
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            swalWithBootstrapButtons.fire({
                                title: "Erro!",
                                text: "Erro na requisição AJAX: " + error,
                                icon: "error"
                            });
                        }
                    });
                }
            });
        }

        //PDV
        function openPdvModal(){
            $('#pdvModal').modal('show');
            atualizaTabelaProdutos(null);
        }

        function buscaProduto() {
            const query = $('#search-input').val().trim();

            if (query) {
                $.ajax({
                    url: 'functions/pdv/busca_produto.php',
                    type: 'GET',
                    data: { query: query },
                    success: function(response) {
                        try {
                            const product = JSON.parse(response);
                            const produtoValor = parseFloat(product.produto_valor).toFixed(2);
                            const impostoValor = parseFloat(product.valor_porcentagem_imposto).toFixed(2);

                            $('#produtoIdPdv').val(product.produto_id);
                            $('#descricaoPdv').val(product.produto_descricao);
                            $('#skuPdv').val(product.produto_sku);
                            $('#estoquePdv').val(product.qtde_estoque);
                            $('#valor_unitario_brutoPdv').val(produtoValor);
                            $('#impostoPdv').val(impostoValor);

                            // Inicializa a quantidade se estiver vazia
                            if ($('#qtdePdv').val() == '') {
                                $('#qtdePdv').val(1);
                            }

                            // Inicializa campo Desconto
                            if ($('#descontoPdv').val() == '') {
                                $('#descontoPdv').val('0,00');
                            }

                            // Validador campo Quantidade > Esqtoque
                            const qtdePdv = parseInt($('#qtdePdv').val(), 10);
                            const estoquePdv = parseInt($('#estoquePdv').val(), 10);
                            if (qtdePdv > estoquePdv) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Atenção!',
                                    text: 'Você tentou inserir mais unidades do que a quantidade disponível em estoque. Quantidade alterada para quantidade disponível em estoque',
                                    confirmButtonColor: "rgb(221, 51, 51)"
                                });
                                $('#qtdePdv').val($('#estoquePdv').val());
                                buscaProduto();
                                return;
                            }

                            // Campo "Valor Un * Qtde:" = Valor Unitário * Qtde
                            let calcularValorUnQtde = parseFloat(parseFloat(produtoValor) * parseFloat(qtdePdv)).toFixed(2);
                            $('#valor_unitario_liquidoPdv').val(calcularValorUnQtde);

                            // Campo "Valor Total Bruto"
                            // R$ => Valor Un Qtde - Desconto
                            // % => Valor Un Qtde - (Valor Un Qtde * (Desconto / 100))
                            let mod_desconto = $('#opcaoDescontoPdv').val();
                            let valorUnitarioLiquido = $('#valor_unitario_liquidoPdv').val();
                            let desconto = $('#descontoPdv').val().replace(',','.');
                            if(mod_desconto == 'descontoDinheiro'){
                                calcularValorTotalBruto = parseFloat(parseFloat(valorUnitarioLiquido) - parseFloat(desconto)).toFixed(2);
                            }

                            if(mod_desconto == 'descontoPorcentagem'){
                                calcularValorTotalBruto = parseFloat(parseFloat(valorUnitarioLiquido) - (parseFloat(valorUnitarioLiquido) * (parseFloat(desconto) / 100))).toFixed(2);
                            }
                            $('#valor_total_brutoPdv').val(calcularValorTotalBruto);

                            //Campo "ImpostoR$" = (Valor Total Bruto * Imposto%) / 100
                            let calcularValorImposto = parseFloat((parseFloat(calcularValorTotalBruto) * parseFloat(impostoValor)) / 100).toFixed(2)
                            $('#impostoValorPdv').val(calcularValorImposto);

                            // Valida se desconto inserido é maior que valor máximo permitido
                            if (parseFloat(calcularValorTotalBruto) < 0) {
                                $('#descontoPdv').val(valorUnitarioLiquidoFormatado);
                                $('#valor_total_brutoPdv').val('0,00');
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Atenção!',
                                    text: 'O desconto inserido foi maior que o valor unitário total dos produtos. O desconto foi ajustado para o valor total das unidades.',
                                    confirmButtonColor: "rgb(221, 51, 51)"
                                });
                            }

                            // Campo "Total" = Valor Total Bruto + ImpostoR$
                            let calcularTotalFinal = parseFloat(parseFloat(calcularValorTotalBruto) + parseFloat(calcularValorImposto)).toFixed(2);
                            $('#valor_total_liquidoPdv').val(calcularTotalFinal);
                        } catch (error) {
                            console.error('Erro ao processar resposta JSON:', error);
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            text: 'Produto não encontrado.',
                            confirmButtonColor: "rgb(221, 51, 51)"
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Atenção!',
                    text: 'Digite algo no campo de busca para buscar.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
            }
        }

        function limpaPdv(){
            $('#search-input').val('');
            $('#descricaoPdv').val('');
            $('#skuPdv').val('');
            $('#estoquePdv').val('');
            $('#impostoPdv').val('');
            $('#impostoValorPdv').val('');
            $('#qtdePdv').val('');
            $('#valor_unitario_brutoPdv').val('');
            $('#descontoPdv').val('');
            $('#valor_unitario_liquidoPdv').val('');
            $('#valor_total_brutoPdv').val('');
            $('#valor_total_liquidoPdv').val('');

            $('#botoesTempPdv').html(`
                    <button class="btn btn-secondary" onclick="limpaPdv()">LIMPAR</button>
                    <button class="btn btn-primary" onclick="addProdutoCarrinho()">ADICIONAR PRODUTO</button>
            `);
        }

        $('#search-input, #qtdePdv, #descontoPdv, #opcaoDescontoPdv').on('keyup', function(event) {
            if (event.keyCode === 13 || event.keyCode === 9) {
                buscaProduto();
            }
        });

        $('#search-input, #qtdePdv, #descontoPdv, #opcaoDescontoPdv').on('blur', function() {
            buscaProduto();
        });

        function addProdutoCarrinho() {
            const produto_id = $('#produtoIdPdv').val();
            const sku = $('#skuPdv').val();
            const descricao = $('#descricaoPdv').val();
            const qtde = $('#qtdePdv').val();
            const valorUnitarioBruto = $('#valor_unitario_brutoPdv').val();
            const valorTotalBruto = $('#valor_total_brutoPdv').val();
            const desconto = $('#descontoPdv').val();
            const modDesconto = $('#opcaoDescontoPdv').val();
            const imposto = $('#impostoValorPdv').val();

            const searchInput = $('#search-input').val();

            if(qtde == 0){
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Não é possível adicionar zero unidades ao carrinho. Altere o valor do campo "Qtde".',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
                return;
            }

            $.ajax({
                url: 'functions/pdv/valida_estoque_produto.php',
                type: 'POST',
                data: {
                    produto_id: produto_id,
                    qtde: qtde
                },
                success: function(result) {
                    const qtdeCarrinho = parseInt(result.qtde_temp, 10) || 0;
                    const qtdeEstoque = parseInt(result.qtde_estoque, 10) || 0;

                    if(qtdeCarrinho <= qtdeEstoque){
                        if(searchInput == ''){
                            Swal.fire({
                                icon: 'error',
                                title: 'Atenção!',
                                text: 'Primeiro busque um produto para poder adicioná-lo ao carrinho.',
                                confirmButtonColor: "rgb(221, 51, 51)"
                            });
                            return;
                        }

                        $.ajax({
                            url: 'functions/pdv/add_produto_carrinho.php',
                            type: 'POST',
                            data: {
                                produto_id: produto_id,
                                sku: sku,
                                descricao: descricao,
                                qtde: $('#qtdePdv').val(),
                                valor_unitario_bruto: valorUnitarioBruto,
                                valor_total_bruto: valorTotalBruto,
                                desconto: desconto,
                                modDesconto: modDesconto,
                                imposto: imposto
                            },
                            success: function(response) {
                                limpaPdv();
                                atualizaTabelaProdutos(null);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Produto adicionado ao carrinho!',
                                    confirmButtonColor: "#28a745"
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error('Erro ao adicionar produto ao carrinho:', error);
                            }
                        });
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Estoque insuficiente.\nVerifique se o produto já não está em seu carrinho',
                            confirmButtonColor: "rgb(221, 51, 51)"
                        });
                        return;
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Erro ao atualizar o estoque:', error);
                }
            });
        }

        function atualizaTabelaProdutos(produto_id) {

            $.ajax({
                url: `functions/pdv/get_produtos_carrinho.php?produto_id=${produto_id}`,
                type: 'GET',
                dataType: 'json',
                success: function(produtos) {
                    let tbody = '';
                    let totalProdutos = 0;
                    let totalImpostos = 0;

                    produtos.forEach(produto => {
                        const prdValorUnidade = parseFloat(produto.produto_valor_unidade).toFixed(2).replace('.', ',');
                        const prdValorTotal = parseFloat(produto.produto_valor_total).toFixed(2).replace('.', ',');
                        const prdValorImposto = parseFloat(produto.produto_valor_imposto).toFixed(2).replace('.', ',');
                        const prdDesconto = parseFloat(produto.desconto).toFixed(2).replace('.', ',');


                        let valorDescPrdt = '';
                        let modDescPrdt = '';
                        let modalidadeDescPrd = '';

                        if (prdDesconto === '0,00') {
                            valorDescPrdt = '-';
                        } else {
                            valorDescPrdt = prdDesconto;
                        }

                        if (valorDescPrdt == '-') {
                            modDescPrdt = '';
                        } else {
                            modDescPrdt = produto.mod_desconto;
                        }

                        if (modDescPrdt == 'R$'){
                            modalidadeDescPrd = `<th>${modDescPrdt}${valorDescPrdt}</th>`;
                        }
                        else if (modDescPrdt == '%'){
                            modalidadeDescPrd = `<th>${valorDescPrdt}${modDescPrdt}</th>`;
                        } else {
                            modalidadeDescPrd = `<th>-</th>`;
                        }
                        
                        totalProdutos += parseFloat(prdValorTotal.replace(',', '.'));
                        totalImpostos += parseFloat(prdValorImposto.replace(',', '.'));

                        tbody += `
                            <tr>
                                <th class="text-center"><i class="fas fa-edit fa-lg text-light mr-2" title="Editar" onclick="editarProdutoPdv(${produto.temp_id})"></i></th>
                                <th>${produto.produto_descricao}</th>
                                <th>${produto.produto_qtde}</th>
                                <th>${prdValorUnidade}</th>
                                ${modalidadeDescPrd}
                                <th>${prdValorImposto}</th>
                                <th>${prdValorTotal}</th>
                            </tr>
                        `;
                    });

                    $('table tbody').html(tbody);
                    const totalProdutosFormatted = `R$ ${totalProdutos.toFixed(2).replace('.', ',')}`;
                    const totalImpostosFormatted = `R$ ${totalImpostos.toFixed(2).replace('.', ',')}`;
                    const totalPedido = totalProdutos + totalImpostos;
                    const totalPedidoFormatted = `R$ ${totalPedido.toFixed(2).replace('.', ',')}`;

                    $('#valorTotalProdutos').text(totalProdutosFormatted);
                    $('#valorTotalProdutos').val(totalProdutos.toFixed(2));

                    $('#valorTotalImpostos').text(totalImpostosFormatted);
                    $('#valorTotalImpostos').val(totalImpostos.toFixed(2));

                    $('#valorTotalPedido').text(totalPedidoFormatted);
                    $('#valorTotalPedido').val(totalPedido.toFixed(2));
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao buscar produtos do carrinho:', error);
                }
            });
        }

        function editarProdutoPdv(temp_id) {
            $.ajax({
                url: `functions/pdv/consulta_editar_produto.php?temp_id=${temp_id}`,
                type: 'GET',
                dataType: 'json',
                success: function(produto) {
                    $('#search-input').val(produto.produto_sku);
                    $('#descricaoPdv').val(produto.produto_descricao);
                    $('#skuPdv').val(produto.produto_sku);
                    $('#estoquePdv').val(produto.qtde_estoque);
                    $('#impostoPdv').val(produto.valor_porcentagem_imposto);
                    $('#valor_total_brutoPdv').val(produto.produto_valor_total)
                    $('#qtdePdv').val(produto.produto_qtde);
                    $('#valor_unitario_brutoPdv').val(produto.produto_valor_unidade);
                    let descontoFormatado = '';
                    if (typeof produto.desconto === 'number') {
                        descontoFormatado = produto.desconto.toFixed(2).replace('.', ',');
                    } else {
                        descontoFormatado = parseFloat(produto.desconto).toFixed(2).replace('.', ',');
                    }

                    let valorUnitarioLiquido = parseFloat(parseFloat(produto.produto_valor_unidade) * parseFloat(produto.produto_qtde)).toFixed(2);
                    $('#valor_unitario_liquidoPdv').val(valorUnitarioLiquido);

                    $('#descontoPdv').val(descontoFormatado);
                    if(produto.mod_desconto == 'R$'){
                        produto.mod_desconto = 'descontoDinheiro';
                        calcularValorTotalBruto = parseFloat(parseFloat(valorUnitarioLiquido) - parseFloat(produto.desconto)).toFixed(2);
                    }

                    if(produto.mod_desconto == '%'){
                        produto.mod_desconto = 'descontoPorcentagem';
                        calcularValorTotalBruto = parseFloat(valorUnitarioLiquido - (valorUnitarioLiquido * (produto.desconto / 100))).toFixed(2);
                    }

                    $('#valor_total_brutoPdv').val(calcularValorTotalBruto);
                    
                    let calcularValorImposto = parseFloat((calcularValorTotalBruto * produto.valor_porcentagem_imposto) / 100).toFixed(2)
                    $('#impostoValorPdv').val(calcularValorImposto);
                    
                    let calcularValorTotalFinal = parseFloat(parseFloat(calcularValorTotalBruto) + parseFloat(calcularValorImposto)).toFixed(2);
                    $('#valor_total_liquidoPdv').val(calcularValorTotalFinal);
                    
                    $('#opcaoDescontoPdv').val(produto.mod_desconto);

                    $('#botoesTempPdv').html(`
                        <button class="btn btn-outline-secondary mr-2" onclick="limpaPdv()"><strong>CANCELAR</strong></button>
                        <button class="btn btn-danger btn-sm mr-2" onclick="excluirProdutoPdv(${produto.temp_id})"><strong>EXCLUIR PRODUTO</strong></button>
                        <button class="btn btn-warning btn-sm" onclick="atualizarProdutoPdv(${produto.temp_id}, ${produto.produto_id}, $('#qtdePdv').val(), $('#descontoPdv').val(), $('#valor_total_brutoPdv').val(), $('#opcaoDescontoPdv').val(), $('#impostoValorPdv').val())"><strong>ATUALIZAR</strong></button>
                    `);
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao buscar produtos do carrinho:', error);
                }
            });
        }

        function excluirProdutoPdv(temp_id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            Swal.fire({
                title: "Deseja remover este produto do carrinho?",
                text: "Esta ação não poderá ser revertida!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sim",
                confirmButtonColor: "rgb(221, 51, 51)",
                cancelButtonText: "Cancelar",
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'functions/pdv/remove_produto_carrinho.php',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            id: temp_id
                        }),
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Produto removido com sucesso!',
                                    confirmButtonColor: "#28a745"
                                }).then(() => {
                                    limpaPdv();
                                    atualizaTabelaProdutos(null);
                                });
                            } else {
                                swalWithBootstrapButtons.fire({
                                    title: "Erro!",
                                    text: "Erro ao remover produto: " + data.message,
                                    icon: "error"
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            swalWithBootstrapButtons.fire({
                                title: "Erro!",
                                text: "Erro na requisição AJAX: " + error,
                                icon: "error"
                            });
                        }
                    });
                }
            });
        }

        function atualizarProdutoPdv(temp_id, produto_id, produto_qtde, desconto, valor_total_brutoPdv, mod_desconto, produto_valor_imposto) {
            $.ajax({
                url: 'functions/pdv/valida_estoque_produto.php?editaProduto=true',
                type: 'POST',
                data: {
                    produto_id: produto_id,
                    qtde: $('#qtdePdv').val(),
                    temp_id: temp_id
                },
                success: function(result, response) {
                    const qtdeCarrinho = parseInt(result.qtde_temp, 10) || 0;
                    const qtdeEstoque = parseInt(result.qtde_estoque, 10) || 0;

                    if(qtdeCarrinho <= qtdeEstoque){
                        Swal.fire({
                            title: "Você realmente deseja editar este produto?",
                            text: "",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#28a745",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Sim"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                var data = {
                                    temp_id: temp_id,
                                    produto_qtde: produto_qtde,
                                    desconto: desconto,
                                    valor_total_brutoPdv: valor_total_brutoPdv,
                                    valor_total_liquidoPdv: valor_total_liquidoPdv, 
                                    mod_desconto: mod_desconto,
                                    produto_valor_imposto: produto_valor_imposto
                                };

                                $.ajax({
                                    url: 'functions/pdv/editar_produto_carrinho.php',
                                    method: 'POST',
                                    data: JSON.stringify(data),
                                    contentType: 'application/json',
                                    success: function(response) {
                                        response = JSON.parse(response);
                                        if (response.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Produto editado com sucesso!',
                                                confirmButtonColor: "#28a745"
                                            }).then(() => {
                                                limpaPdv();
                                                atualizaTabelaProdutos(null);
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Erro!',
                                                text: 'Erro ao editar produto: ' + response.message
                                            });
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Erro!',
                                            text: 'Erro na requisição AJAX: ' + error
                                        });
                                    }
                                });
                            }
                        });
                    }
                    else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Estoque insuficiente.',
                            confirmButtonColor: "rgb(221, 51, 51)"
                        });
                        return;
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Erro ao atualizar o estoque do produto: ', error);
                }
            });
        }

        function selecionaConsumidor() {
            $('#selecionaConsumidorModal').modal('show');
        }

        function consultaConsumidor() {
            const cpfCnpj = $('#cpfCnpjConsumidor').val().trim();

            if (cpfCnpj) {
                $.ajax({
                    url: 'functions/pdv/consulta_consumidor.php',
                    type: 'GET',
                    data: { query: cpfCnpj },
                    success: function(response) {
                        response = JSON.parse(response);

                        if (response.error) {
                            
                        } else {
                            $('#idConsumidor').val(response.cliente_id);
                            $('#nomeConsumidor').val(response.cliente_nome);
                            $('#emailConsumidor').val(response.cliente_email);
                            $('#telefoneConsumidor').val(response.cliente_telefone);
                        }
                    },
                    error: function(xhr, status, error) {
                        //Caso inserido cpf que não está cadastrado, zera os campos
                        $('#idConsumidor').val('');
                        $('#nomeConsumidor').val('');
                        $('#emailConsumidor').val('');
                        $('#telefoneConsumidor').val('');
                    }
                });
            }else{
                //Zera campos caso limpe o cpf e dê tab/enter
                $('#idConsumidor').val('');
                $('#nomeConsumidor').val('');
                $('#emailConsumidor').val('');
                $('#telefoneConsumidor').val('');
            }
        }

        function salvaConsumidor() {
            const cpfCnpj = $('#cpfCnpjConsumidor').val().trim();
            const nome = $('#nomeConsumidor').val().trim();
            const email = $('#emailConsumidor').val().trim();
            const telefone = $('#telefoneConsumidor').val().trim();

            if (cpfCnpj && nome && email && telefone) {
                $.ajax({
                    url: 'functions/pdv/salva_consumidor.php',
                    type: 'POST',
                    data: {
                        cpfCnpj: cpfCnpj,
                        nome: nome,
                        email: email,
                        telefone: telefone
                    },
                    success: function(response) {
                        $('#selecionaConsumidorModal').modal('hide');
                        $('#consumidorSelecionado').html(nome);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erro ao salvar consumidor:', error);
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Por favor, digite todos os dados do consumidor.',
                    confirmButtonColor: "rgb(221, 51, 51)"
                });
            }
        }

        function finalizarCompraPdv() {
            if($('#consumidorSelecionado').html() == 'Selecione'){
                $('#idConsumidor').val('3');
                $('#cpfCnpjConsumidor').val('12345678925');
                $('#nomeConsumidor').val('CONSUMIDOR');
                $('#emailConsumidor').val('nao@tem.com');
                $('#telefoneConsumidor').val('11111111111');
                salvaConsumidor();
            }

            const cliente_id = $('#idConsumidor').val();

            $.ajax({
                url: 'functions/pdv/get_produtos_carrinho.php?produto_id=null',
                type: 'GET',
                success: function(response) {
                    if (response.length > 0) {
                        const produtos = [];

                        response.forEach(function(produto) {
                            produtos.push({
                                produto_id: produto.produto_id,
                                produto_sku: produto.produto_sku,
                                produto_descricao: produto.produto_descricao,
                                produto_qtde: produto.produto_qtde,
                                produto_valor_unidade: produto.produto_valor_unidade,
                                produto_valor_total: produto.produto_valor_total,
                                desconto: produto.desconto,
                                mod_desconto: produto.mod_desconto,
                                produto_valor_imposto: produto.produto_valor_imposto
                            });

                            $.ajax({
                                url: 'functions/pdv/atualiza_estoque_produto.php?operacao=diminuiEstoque',
                                type: 'POST',
                                data: {
                                    produto_id: produto.produto_id,
                                    qtde: produto.produto_qtde
                                },
                                success: function(response) {

                                },
                                error: function(xhr, status, error) {
                                    console.log('Erro ao atualizar o estoque do produto: ', error);
                                }
                            });
                        });

                        $.ajax({
                            url: 'functions/pdv/finaliza_compra.php',
                            type: 'POST',
                            data: {
                                cliente_id: cliente_id,
                                produtos: produtos,
                                valorTotalProdutos: $('#valorTotalProdutos').val(),
                                valorTotalImpostos: $('#valorTotalImpostos').val(),
                                valorTotalPedido: $('#valorTotalPedido').val()
                            },
                            success: function(response) {
                                if (response.success) {
                                    atualizaTabelaProdutos(null);
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Venda criada com sucesso!',
                                        text: 'Gerado Venda Nº: ' + response.venda_id,
                                        confirmButtonColor: "#28a745"
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Erro!',
                                        text: 'Contate o suporte. \n' + response.message, 
                                        confirmButtonColor: "rgb(221, 51, 51)"
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Erro na requisição AJAX:', error);
                            }
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Não há produtos no carrinho para finalizar a compra.',
                            confirmButtonColor: "rgb(221, 51, 51)"
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao obter produtos do carrinho:', error);
                }
            });
        }

        //Vendas Realizadas
        function abreModalVendas() {
            $('#modalExibeVendas').modal('show');

            $('#agrupadoPorClienteTable').DataTable({
                "paging": false,
                "info": false,
                "searching": true,
                "ordering": true,
                "lengthChange": false,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Pesquisar",
                    "zeroRecords": "Nenhum registro encontrado"
                },
                "columnDefs": [
                    { "orderable": false, "targets": -1 }
                ],
                "destroy": true,
                "processing": true,
                "serverSide": false,
                "ajax": {
                    "url": 'functions/vendas/consulta_vendas_agrupado.php',
                    "type": 'POST',
                    "dataType": 'json',
                    "dataSrc": ""
                },
                "columns": [
                    { "data": "cliente_nome" },
                    { "data": "qtde_vendas_cliente" },
                    {
                        "data": null,
                        render: function (data, type, row) {
                            return '<i class="fa fa-search fa-lg text-primary centerFaSearch2 pointer2" onclick="detalhesVendasConsumidor(\'' + row.cliente_id + '\')" title="Detalhes"></i>';
                        },
                        orderable: false
                    }
                ]
            });
        }

        function exibirAgrupadoPorCliente() {
            document.getElementById('agrupadoPorCliente').style.display = 'block';
            document.getElementById('semAgrupamento').style.display = 'none';

            document.getElementById('btnAgrupadoPorCliente').classList.remove('btn-secondary');
            document.getElementById('btnAgrupadoPorCliente').classList.add('btn-primary');
            document.getElementById('btnSemAgrupamento').classList.remove('btn-primary');
            document.getElementById('btnSemAgrupamento').classList.add('btn-secondary');

        }

        function detalhesVendasConsumidor(cliente_id) {
            $('#modalDetalhesAgrupado').modal('show');
            $('#modalExibeVendas').addClass('dimmed');

            $.ajax({
                url: 'functions/vendas/consulta_vendas_consumidor.php',
                type: 'POST',
                data: { 
                    cliente_id: cliente_id
                },
                dataType: 'json',
                success: function (vendas) {
                    var table = $('#vendasClienteDetalhe').DataTable({
                        data: vendas,
                        columns: [
                            { data: 'venda_id' },
                            { data: 'venda_valor_produtos', render: $.fn.dataTable.render.number('.', ',', 2, 'R$ ') },
                            { data: 'venda_valor_imposto', render: $.fn.dataTable.render.number('.', ',', 2, 'R$ ') },
                            { data: 'venda_valor_total', render: $.fn.dataTable.render.number('.', ',', 2, 'R$ ') },
                            {
                                data: null,
                                render: function (data, type, row) {
                                    return '<i class="fa fa-search fa-lg text-primary centerFaSearch pointer2" onclick="abreModalVendaDetalhado(\'' + row.venda_id + '\')" title="Detalhes"></i>';
                                },
                                orderable: false
                            }
                        ],
                        paging: false,
                        ordering: true,
                        searching: true,
                        info: false,
                        lengthChange: false,
                        language: {
                            search: '',
                            searchPlaceholder: 'Pesquisar',
                            zeroRecords: 'Nenhum registro encontrado'
                        },
                        destroy: true,
                        processing: true,
                        serverSide: false
                    });
                    
                    table.clear().rows.add(vendas).draw();
                    
                    if (vendas.length > 0) {
                        var venda = vendas[0];
                        $('#nomeConsumidorVendaAgrupado').text(venda.cliente_nome);
                        $('#cpfConsumidorVendaAgrupado').text(venda.cliente_cpf);
                        $('#emailConsumidorVendaAgrupado').text(venda.cliente_email);
                        $('#telConsumidorVendaAgrupado').text(venda.cliente_telefone);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Erro na requisição AJAX:', status, error);
                }
            });

            $('#modalDetalhesAgrupado').on('hidden.bs.modal', function () {
                $('#modalExibeVendas').removeClass('dimmed');
            });
        }

        function abreModalVendaDetalhado(venda_id) {
            $('#modalVendaAgrupadoDetalhes').modal('show');
            $('#modalDetalhesAgrupado').addClass('dimmed');
            $('#modalExibeVendas').addClass('dimmed');

            $('#vendaClienteAgrupadoTable').DataTable({
                "paging": false,
                "info": false,
                "searching": true,
                "ordering": true,
                "lengthChange": false,
                "destroy": true,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Pesquisar",
                    "zeroRecords": "Nenhum registro encontrado"
                },
                "ajax": {
                    "url": "functions/vendas/consulta_vendas_detalhado.php",
                    "type": "POST",
                    "data": { venda_id: venda_id },
                    "dataSrc": "data"
                },
                "columns": [
                    { "data": "produto_sku" },
                    { "data": "produto_descricao" },
                    { "data": "produto_valor_unidade", "render": function (data, type, row) { return 'R$ ' + parseFloat(data).toLocaleString('pt-br', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }},
                    { "data": "produto_qtde" },
                    { "data": "desconto", "render": function (data, type, row) { return 'R$ ' + parseFloat(data).toLocaleString('pt-br', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }},
                    { "data": "produto_valor_imposto", "render": function (data, type, row) { return 'R$ ' + parseFloat(data).toLocaleString('pt-br', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }},
                    { "data": "produto_valor_total", "render": function (data, type, row) { return 'R$ ' + parseFloat(data).toLocaleString('pt-br', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }}
                ],
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api();
                    
                    var parseValue = function (value) {
                        return typeof value === 'string' ? parseFloat(value.replace(/[\$,]/g, '')) : value;
                    };

                    var totalVlUnidade = 0;
                    var totalQtde = 0;
                    var totalImposto = 0;
                    var totalProdutos = 0;

                    var modDesconto = false;

                    data.forEach(function (row) {
                        totalVlUnidade += parseValue(row.produto_valor_unidade);
                        totalQtde += parseValue(row.produto_qtde);
                        totalImposto += parseValue(row.produto_valor_imposto);
                        totalProdutos += parseValue(row.produto_valor_total);

                        if (row.mod_desconto === 'R$') {
                            modDesconto = true;
                        }
                    });

                    var totalFinal = totalImposto + totalProdutos;

                    $(api.column(2).footer()).html('R$ ' + totalVlUnidade.toLocaleString('pt-br', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                    $(api.column(3).footer()).html(totalQtde);
                    if (modDesconto) {
                        $(api.column(5).footer()).html('R$ ' + totalImposto.toLocaleString('pt-br', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                    } else {
                        $(api.column(5).footer()).html(totalImposto.toLocaleString('pt-br', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '%');
                    }
                    $(api.column(6).footer()).html('R$ ' + totalProdutos.toLocaleString('pt-br', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

                    $('#totalFinal').html('R$ ' + totalFinal.toLocaleString('pt-br', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                }
            });

            $('#numeroVendaDetalhe').html(venda_id);

            $('#modalVendaAgrupadoDetalhes').on('hidden.bs.modal', function () {
                $('#modalDetalhesAgrupado, #modalExibeVendas').removeClass('dimmed');
            });

            
        }

        function exibirSemAgrupamento() {
            document.getElementById('agrupadoPorCliente').style.display = 'none';
            document.getElementById('semAgrupamento').style.display = 'block';

            document.getElementById('btnAgrupadoPorCliente').classList.remove('btn-primary');
            document.getElementById('btnAgrupadoPorCliente').classList.add('btn-secondary');
            document.getElementById('btnSemAgrupamento').classList.remove('btn-secondary');
            document.getElementById('btnSemAgrupamento').classList.add('btn-primary');

            $('#semAgrupamentoTable').DataTable({
                "paging": false,
                "info": false,
                "searching": true,
                "ordering": true,
                "lengthChange": false,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Pesquisar",
                    "zeroRecords": "Nenhum registro encontrado"
                },
                "destroy": true,
                "processing": true,
                "serverSide": false,
                "ajax": {
                    "url": 'functions/vendas/consulta_vendas_geral.php',
                    "type": 'POST',
                    "dataType": 'json',
                    "dataSrc": ""
                },
                "columns": [
                    { "data": "venda_id" },
                    { "data": "cliente_nome" },
                    { "data": "venda_valor_produtos", "render": function (data, type, row) { return 'R$ ' + parseFloat(data).toLocaleString('pt-br', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }},
                    { "data": "venda_valor_imposto", "render": function (data, type, row) { return 'R$ ' + parseFloat(data).toLocaleString('pt-br', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }},
                    { "data": "venda_valor_total", "render": function (data, type, row) { return 'R$ ' + parseFloat(data).toLocaleString('pt-br', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }},
                    {
                                "data": null,
                                render: function (data, type, row) {
                                    return '<i class="fa fa-search fa-lg text-primary centerFaSearch pointer2" onclick="abreModalVendaDetalhado(\'' + row.venda_id + '\')" title="Detalhes"></i>';
                                },
                                orderable: false
                            }
                ]
            });
        }

    </script>
</body>
</html>