<?php
    require_once 'header.php';

    if ($_SESSION["acessoSeguro"] != 1)
    {
    header("Location: index.php");
    }
?>
    <link rel="stylesheet" type="text/css" href="css/estilo_seguro.css" media="screen" />
    <script type="text/javascript" src="js/configuracaoSeguro.js"></script>
    <script>
            $(function() { 
            carregarSeguro(10);
        });
    </script>
    </br>
    <div class="card">
        <div class="card-body">
            <h5><i class="bi bi-building"></i> Seguradora</h5>
        </div>
    </div>
    </br>
    <div class="card">
        <div class="card-body pesquisa">
            <div class="row">
                <div class="col-md-2">
                    <label for="pesquisaId" class="col-form-label">ID:</label>
                    <input type="number" class="form-control" id="pesquisaId">
                </div>

                <div class="col-md-5">
                    <label for="pesquisaSeguro" class="col-form-label">Nome:</label>
                    <input type="text" class="form-control" id="pesquisaSeguro">
                </div>

                <div class="col-md-2 centralizarDiv">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="checkPesquisaStatus" checked>
                        <label class="form-check-label" for="checkPesquisaStatus">Ativo</label>
                    </div>
                </div> 

                <div class="col-md-3 centralizarDiv">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary" id="pesquisar" onclick="carregarSeguro('')">
                            <i class="bi bi-search"></i> Pesquisar
                        </button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalSeguradora" data-bs-whatever="Nova Seguradora" onclick="limparCampos();">
                            <i class="bi bi-plus-square-dotted"></i> Novo Seguro
                        </button> 
                    </div>                                
                </div> 
            </div>

            </br>

            <div class="row">
                <div class="table-responsive">
                    <div class="col-md-12">
                        <table class="table table-hover" id="tabelaSeguro">
                            <thead>
                                <tr>
                                    <th scope="col">Código</th>
                                    <th scope="col">Seguradora</th>
                                    <th scope="col">Descrição</th>
                                    <th scope="col">Status</th>
                                    <th scope="col"></th>
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

    <!-- Começo - Modal com os dados-->
    <div class="modal fade" id="modalSeguradora" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Dados da Seguradora</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <label for="idSeguradora" class="col-form-label">ID:</label>
                        <input type="text" class="form-control" id="idSeguradora" placeholder="Definido automáticamente" readonly>
                    </div>
                </div>    
                <div class="row">
                    <div class="col">
                        <label for="nomeSeguradora" class="col-form-label">Nome da Seguradora:</label>
                        <input type="text" class="form-control" id="nomeSeguradora" placeholder="Digite o nome do seguro">
                    </div>

                    <div class="col">
                        <label for="descricaoSeguro" class="col-form-label">Descrição:</label>
                        <input type="text" class="form-control" id="descricaoSeguro" placeholder="Digite uma pequena descrição">
                    </div>
                </div>    
                </br>
                <div class="row">
                    <div class="col">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="checkStatusSeguro">
                            <label class="form-check-label" for="checkStatusSeguro">Ativo</label>
                        </div>
                    </div> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="cadastrarSeguro();">Salvar</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Fim - Modal com os dados-->

    <script>                    
        var abrirModal = document.getElementById('modalSeguradora')
        abrirModal.addEventListener('show.bs.modal', function (event) 
        {
            var button = event.relatedTarget
            var titulo = button.getAttribute('data-titulo')
            var id = button.getAttribute('data-id')
            var modalTitle = abrirModal.querySelector('.modal-title')
            modalTitle.textContent = titulo
            $('#idSeguradora').val(id);
        });

        var pesquisarId = document.getElementById('pesquisaId')
        pesquisarId.addEventListener('keypress', function(tecla){
            if (tecla.which == 13){
                carregarSeguro('');
            }
        });

        var pesquisarNome = document.getElementById('pesquisaSeguro')
        pesquisarNome.addEventListener('keypress', function(tecla){
            if (tecla.which == 13){
                carregarSeguro('');
            }
        });
    </script>


<?php
    require_once 'footer.php';
?>