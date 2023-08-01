<?php
    require_once 'header.php';

    if ($_SESSION["acessoServico"] != 1)
    {
        header("Location: index.php");
    }
?>

    <link rel="stylesheet" type="text/css" href="css/estilo_servico.css" media="screen" />
    <script type="text/javascript" src="js/configuracaoServico.js"></script>
    <script>
        $(function() { 
            carregarServico(10);
        });
    </script>
    </br>
    <div class="card">
        <div class="card-body">
            <h5><i class="bi bi-briefcase"></i> Registro de serviço</h5>
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

                <div class="col-md-4">
                    <label for="pesquisaNome" class="col-form-label">Nome:</label>
                    <input type="text" class="form-control" id="pesquisaNome">
                </div>

                <div class="col-md-1 centralizarDiv">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="checkPesquisaStatus" checked>
                        <label class="form-check-label" for="checkPesquisaStatus">Ativo</label>
                    </div>
                </div> 

                <div class="col-md-5 centralizarDiv">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary" id="pesquisar" onclick="carregarServico('')">
                            <i class="bi bi-search"></i> Pesquisar serviço
                        </button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalServico" data-bs-whatever="Novo serviço">
                            <i class="bi bi-plus-square-dotted"></i> Novo serviço
                        </button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCategoria" data-bs-whatever="Editar Cotação">
                            <i class="bi bi-plus-square-dotted"></i> Nova Categoria
                        </button>   
                    </div>                                
                </div> 
            </div>
            </br>
            <div class="row">
                <div class="table-responsive">
                    <div class="col-md-12">
                        <table class="table table-hover" id="tabelaServico">
                            <thead>
                                <tr>
                                    <th scope="col">Código</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Descrição</th>
                                    <th scope="col">Categoria</th>
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

    <!-- Começo - Modal com os dados categoria-->
    <div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Dados de categorias</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <label for="idCategoria" class="col-form-label">ID:</label>
                        <input type="text" class="form-control" id="idCategoria" placeholder="Definido automáticamente" readonly>
                    </div>
                </div>    
                <div class="row">
                    <div class="col">
                        <label for="nomeCategoria" class="col-form-label">Nome da Categoria:</label>
                        <input type="text" class="form-control" id="nomeCategoria" placeholder="Digite o nome da categoria">
                    </div>

                    <div class="col">
                        <label for="descricaoCategoria" class="col-form-label">Descrição:</label>
                        <input type="text" class="form-control" id="descricaoCategoria" placeholder="Digite uma pequena descrição">
                    </div>
                </div>  
                </br> 
                
                <div class="card">
                    <h5 class="card-header">Categorias Cadatradas</h5>
                    <div class="card-body">
                        <table class="table table-hover" id="tabelaCategoria">
                            <thead>
                                <tr>
                                    <th scope="col">Código</th>
                                    <th scope="col">Categoria</th>
                                    <th scope="col">Descrição</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                
                </br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="cadastrarCategoria();">Salvar</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Fim - Modal com os dados categoria-->

    <!-- Começo - Modal com os dados serviço-->
    <div class="modal fade" id="modalServico" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Dados do serviço</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <label for="idServico" class="col-form-label">ID:</label>
                        <input type="text" class="form-control" id="idServico" placeholder="Definido automáticamente" readonly>
                    </div>
                </div>    
                <div class="row">
                    <div class="col">
                        <label for="nomeServico" class="col-form-label">Nome do serviço:</label>
                        <input type="text" class="form-control" id="nomeServico" placeholder="Ex: Guincho">
                    </div>

                    <div class="col">
                        <label for="descricaoServico" class="col-form-label">Descrição:</label>
                        <input type="text" class="form-control" id="descricaoServico" placeholder="Ex: 400 KM">
                    </div>
                </div>    
                <div class="row">
                    <div class="col">
                        <label for="categoriasServico" class="col-form-label">Categoria:</label>
                        <select class="form-select" id="categoriasServico">
                            <option value="0" selected>Todos</option>
                        </select>
                    </div>
                </div>
                </br>
                <div class="row">
                    <div class="col">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="checkStatusServico" checked>
                            <label class="form-check-label" for="checkStatusServico">Ativo</label>
                        </div>
                    </div> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="cadastrarServico();">Salvar</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Fim - Modal com os dados serviço-->

    <script>                    
        var abrirModalServico = document.getElementById('modalServico')
        abrirModalServico.addEventListener('show.bs.modal', function (event) 
        {
            var button = event.relatedTarget
            var titulo = button.getAttribute('data-titulo')
            var id = button.getAttribute('data-id')
            //var modalTitle = abrirModal.querySelector('.modal-title')
            //modalTitle.textContent = titulo
            $('#idServico').val(id);
            carregarCategoriasSelect();
        })

        var abrirModalCategoria = document.getElementById('modalCategoria')
        abrirModalCategoria.addEventListener('show.bs.modal', function (event) 
        {
            var button = event.relatedTarget
            var id = button.getAttribute('data-id')
            //var modalTitle = abrirModal.querySelector('.modal-title')
            //modalTitle.textContent = 'Categorias'
            carregarCategorias();
        });

        var pesquisarId = document.getElementById('pesquisaId')
        pesquisarId.addEventListener('keypress', function(tecla){
            if (tecla.which == 13){
                carregarServico('');
            }
        });

        var pesquisarNome = document.getElementById('pesquisaNome')
        pesquisarNome.addEventListener('keypress', function(tecla){
            if (tecla.which == 13){
                carregarServico('');
            }
        });
    </script>


<?php
    require_once 'footer.php';
?>

