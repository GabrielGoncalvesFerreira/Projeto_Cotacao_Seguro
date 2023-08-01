<?php
    require_once 'header.php';

    if ($_SESSION["acessoUsuario"] != 1)
    {
        header("Location: index.php");
    }
?>
    <link rel="stylesheet" type="text/css" href="css/estilo_usuario.css" media="screen" />
    <script type="text/javascript" src="js/configuracaoUsuario.js"></script>
    <script>
        $(function() { 
            carregarUsuarios(10);
        });
    </script>
    </br>
    <div class="card">
        <div class="card-body">
            <h5><i class="bi bi-person-bounding-box"></i> Usuário</h5>
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
                        <button type="button" class="btn btn-primary" id="pesquisar" onclick="carregarUsuarios('')">
                            <i class="bi bi-search"></i> Pesquisar
                        </button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUsuario" data-id='0' data-bs-whatever="@mdo">
                            <i class="bi bi-plus-square-dotted"></i> Novo Usuário
                        </button>
                    </div>                                
                </div> 
            </div>
            </br>
            <div class="row">
                <div class="table-responsive">
                    <div class="col-md-12">
                        <table class="table table-hover" id="tabelaUsuario">
                            <thead>
                                <tr>
                                    <th scope="col">Código</th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Usuário</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">E-mail</th>
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


    <!--Modal com os dados-->
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Dados do usuário</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <label for="idServico" class="col-form-label">ID:</label>
                            <input type="text" class="form-control" id="codigoUsuario" placeholder="Definido automáticamente" readonly>
                        </div>
                    </div>  
                    <div class="row">
                        <div class="form-group col">
                            <label for="nomeUsuario" class="col-form-label">Nome</label>
                            <input type="text" class="form-control" id="nomeUsuario" placeholder="Digite o nome">
                        </div>
                    </div>  
                    <div class="row">
                        <div class="form-group col">
                            <label for="emailUsuario" class="col-form-label">E-mail</label>
                            <input type="text" class="form-control" id="emailUsuario" placeholder="Digite o E-mail">
                        </div>
                    </div> 
                    <div class="row">
                        <div class="form-group col">
                            <label for="usuario" class="col-form-label">Usuário</label>
                            <input type="text" class="form-control" id="usuario" placeholder="Digite o usuário">
                        </div>
                        <div class="form-group col">
                            <div class="form-group col">
                                <label for="senhaUsuario" class="col-form-label">Senha</label>
                                <input type="password" class="form-control" id="senhaUsuario" placeholder="Digite uma senha">
                            </div>
                            <div class="form-group col">
                                <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="checkSenha" onclick="validarTrocaSenha();">
                                        <label class="form-check-label" for="checkSenha">Alterar senha</label>
                                </div>
                            </div>
                        </div>
                    </div> 
                    </br>
                    <div class="row">
                        <div class="col">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="checkStatusUsuario" checked>
                                <label class="form-check-label" for="checkStatusUsuario">Usuário Ativo</label>
                            </div>
                        </div> 
                    </div> 
                    </br>
                    <div class="card">
                        <h5 class="card-header">Acessos</h5>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="checkClientes">
                                        <label class="form-check-label" for="checkClientes">Clientes</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="checkSeguro">
                                        <label class="form-check-label" for="checkSeguro">Seguro</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="checkUsuario">
                                        <label class="form-check-label" for="checkUsuario">Usuario</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="checkServico">
                                        <label class="form-check-label" for="checkServico">Serviço</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="checkAgendamento">
                                        <label class="form-check-label" for="checkAgendamento">Agendamento</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="checkCotacao">
                                        <label class="form-check-label" for="checkCotacao">Cotação</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="checkConfiguracao">
                                        <label class="form-check-label" for="checkConfiguracao">Configuração</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="cadastrarUsuario();">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <script>                    
        var abrirModal = document.getElementById('modalUsuario')
        abrirModal.addEventListener('show.bs.modal', function (event) 
        {
            var button = event.relatedTarget
            var titulo = button.getAttribute('data-titulo')
            var id = button.getAttribute('data-id')
            var modalTitle = abrirModal.querySelector('.modal-title')
            modalTitle.textContent = titulo

            if (id != "" || id != "0")
            { //senhaUsuario
                limparDados();
                $('#codigoUsuario').val(id);
                buscarUsuario();
                desabilitarSenha();
            }
            else
            {
                limparDados();
            }
        });

        var pesquisarId = document.getElementById('pesquisaId')
        pesquisarId.addEventListener('keypress', function(tecla){
            if (tecla.which == 13){
                carregarUsuarios('');
            }
        });

        var pesquisarNome = document.getElementById('pesquisaNome')
        pesquisarNome.addEventListener('keypress', function(tecla){
            if (tecla.which == 13){
                carregarUsuarios('');
            }
        });
    </script>
<?php
    require_once 'footer.php';
?>