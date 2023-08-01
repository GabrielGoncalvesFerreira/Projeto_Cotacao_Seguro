<?php
    require_once 'header.php';

    if ($_SESSION["acessoCliente"] != 1)
    {
        header("Location: index.php");
    }
?>
                <link rel="stylesheet" type="text/css" href="css/estilo_cliente.css" media="screen" />
                <script type="text/javascript" src="js/ConfiguracaoCliente.js"></script>
                <script>
                    $(function() { 
                        carregarDadosClientes(10);
                    });
                </script>
                </br>
                <div class="card">
                    <div class="card-body">
                        <h5><i class="bi bi-person-lines-fill"></i> Cliente</h5>
                    </div>
                </div>
                </br>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="pesquisaId" class="col-form-label">ID:</label>
                                <input type="number" class="form-control" id="pesquisaId">
                            </div>

                            <div class="col-md-5">
                                <label for="pesquisaNome" class="col-form-label">Nome:</label>
                                <input type="text" class="form-control" id="pesquisaNome">
                            </div>

                            <div class="col-md-2 centralizarDiv">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="checkPesquisaStatus" checked>
                                    <label class="form-check-label" for="checkPesquisaStatus">Ativo</label>
                                </div>
                            </div> 

                            <div class="col-md-3 centralizarDiv">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-primary" id="pesquisar" onclick="carregarDadosClientes('')">
                                        <i class="bi bi-search"></i> Pesquisar
                                    </button>
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCliente" onclick="limparCampos();">
                                        <i class="bi bi-plus-square-dotted"></i> Novo Cliente
                                    </button>
                                </div>                                
                            </div> 
                        </div>                   
                        <br>
                        <div class="row">
                            <div class="table-responsive">
                                <div class="col-md-12">
                                    <table class="table table-hover" id="tabelaClientes">
                                        <thead>
                                            <tr>
                                                <th scope="col">Código</th>
                                                <th scope="col">Cliente</th>
                                                <th scope="col">Tipo</th>
                                                <th scope="col">CNPJ / CPF</th>
                                                <th scope="col">Origem</th>
                                                <th scope="col">Cidade</th>
                                                <th scope="col">CEP</th>
                                                <th></th>
                                                <th></th>
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

                <!--Modal com os dados-->
                <div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Dados do cliente</h1>
                            </div>
                            <div class="modal-body">
                                <form>
                                <div class="form-group col">
                                        <label for="nomeCliente" class="col-form-label">Código</label>
                                        <input type="text" class="form-control" id="codigoCliente" readonly placeholder="Definido automaticamente">
                                    </div>
                                    <div class="form-group col">
                                        <label for="nomeCliente" class="col-form-label">Cliente</label>
                                        <input type="text" class="form-control" id="nomeCliente" placeholder="Digite o cliente">
                                    </div>
                                    <div class="form-group col">
                                        <label for="tipoCliente" class="col-form-label">Tipo</label>
                                        <select class="form-control" id="tipoCliente">
                                            <option value=""></option>
                                            <option value="cpf">CPF</option>
                                            <option value="cnpj">CNPJ</option>
                                        </select>
                                    </div>
                                    <div class="form-group col">
                                        <label for="identificador" class="col-form-label">CPF / CNPJ</label>
                                        <input type="number" class="form-control" id="identificador" placeholder="Digite somente números">
                                    </div>
                                    <div class="form-group col">
                                        <label for="cep" class="col-form-label">CEP</label>
                                        <input type="text" class="form-control" id="cep" placeholder="Digite o CEP">
                                    </div>
                                    <div class="form-group col">
                                        <label for="cidade" class="col-form-label">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" placeholder="Digite a Cidade">
                                    </div>
                                    <div class="form-group col">
                                        <label for="origem" class="col-form-label">Origem</label>
                                        <input type="text" class="form-control" id="origem" placeholder="Digite a origem do cliente (Ex.: Instagram)">
                                    </div>
                                    <br>
                                    <div class="card">
                                        <div class="card-header">
                                            Histórico de Agendamentos
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="table-responsive">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped" id="tabelaHistoricoAgendamentos">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Cód. agendamento</th>
                                                                    <th scope="col">Status</th>
                                                                    <th scope="col">Data Agendamento</th>
                                                                    <th scope="col">Tipo</th>
                                                                    <th scope="col">Observação</th>
                                                                    <th scope="col">Cód. usuário</th>
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
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                <button type="button" class="btn btn-primary" onclick="cadastrarCliente();">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div> <!-- Fim do Modal com os dados -->

                <script>                    
                    var abrirModal = document.getElementById('modalCliente')
                    abrirModal.addEventListener('show.bs.modal', function (event) 
                    {
                        var button = event.relatedTarget
                        var titulo = button.getAttribute('data-titulo')
                        var id = button.getAttribute('data-id')
                        var modalTitle = abrirModal.querySelector('.modal-title')
                        modalTitle.textContent = titulo
                        $('#codigoCliente').val(id);
                    });

                    var pesquisarId = document.getElementById('pesquisaId')
                    pesquisarId.addEventListener('keypress', function(tecla){
                        if (tecla.which == 13){
                            carregarDadosClientes('');
                        }
                    });

                    var pesquisarNome = document.getElementById('pesquisaNome')
                    pesquisarNome.addEventListener('keypress', function(tecla){
                        if (tecla.which == 13){
                            carregarDadosClientes('');
                        }
                    });
                </script>

            </div> <!-- Fechar container -->
        </div> <!-- Fechar col-md-10 -->
    </div> <!-- Fechar row do header -->                

<?php
    require_once 'footer.php';
?>