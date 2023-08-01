
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="js/configuracaoAgendamento.js"></script>
    <script type="text/javascript" src="js/configuracaoUsuario.js"></script>
    <link rel="stylesheet" type="text/css" href="css/estilo_agendamento.css" media="screen" />

    <!-- Começo - Modal com os dados tipo-->
    <div class="modal fade" id="modalTipo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Dados</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <label for="idTipo" class="col-form-label">ID:</label>
                        <input type="text" class="form-control" id="idTipo" placeholder="Definido automáticamente" readonly>
                    </div>
                </div>    
                <div class="row">
                    <div class="col">
                        <label for="nomeTipo" class="col-form-label">Nome do tipo:</label>
                        <input type="text" class="form-control" id="nomeTipo" placeholder="Digite o nome da tipo">
                    </div>

                    <div class="col">
                        <label for="descricaoTipo" class="col-form-label">Descrição:</label>
                        <input type="text" class="form-control" id="descricaoTipo" placeholder="Digite uma pequena descrição">
                    </div>
                </div>  
                </br> 
                
                <div class="card">
                    <h5 class="card-header">Tipos Cadastrados</h5>
                    <div class="card-body">
                        <table class="table table-hover" id="tabelaTipo">
                            <thead>
                                <tr>
                                    <th scope="col">Código</th>
                                    <th scope="col">Tipo</th>
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
                <button type="button" class="btn btn-primary" onclick="cadastrarTipo();">Salvar</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Fim - Modal com os dados tipo-->

    <!-- Começo - Modal com os dados agendamento-->
    <div class="modal fade" id="modalAgendamento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Dados do Agendamento</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <label for="idAgendamento" class="col-form-label">ID:</label>
                        <input type="text" class="form-control" id="idAgendamento" placeholder="Definido automáticamente" readonly>
                    </div>
                </div>   
                <div class="row"> 
                    <div class="col">
                        <label for="dataAgendamento" class="col-form-label">Data:</label>
                        <input type="date" class="form-control" id="dataAgendamento">
                    </div>
                    <div class="col">
                        <label for="horaAgendamento" class="col-form-label">Hora Inical:</label>
                        <input type="time" class="form-control" id="horaAgendamento">
                    </div>
                    <div class="col">
                        <label for="horaAgendamentoFinal" class="col-form-label">Hora Final:</label>
                        <input type="time" class="form-control" id="horaAgendamentoFinal">
                    </div>
                    <div class="col">
                        <label for="statusAgendamento" class="col-form-label">Status:</label>
                        <select class="form-select" id="statusAgendamento">
                            <option value="A" selected>Agendado</option>
                            <option value="P" >Prorrogado</option>
                            <option value="C" >Cancelado</option>
                            <option value="O" >Concluído</option>
                        </select>
                    </div>
                <div>
                <div class="row">
                    <div class="col">
                        <label for="clienteAgendamento" class="col-form-label">Cliente:</label>
                        <select class="form-select novoAgendamento" id="clienteAgendamento"></select>
                        <input class="form-control" id="clienteAgendamentoInput" style="display:none" readonly>
                    </div>
                    <div class="col">
                        <label for="tipoAgendamento" class="col-form-label">Tipo Agendamento:</label>
                        <select class="form-select" id="tipoAgendamento">
                            <option value="0" selected>Todos</option>
                        </select>
                    </div>
                </div>  
                <div class="row">
                    <div class="col">
                        <label for="participantes" class="col-form-label">Participantes:</label>
                        <select class="form-select" id="participantes" multiple="multiple">
                            <option value="participante1">Participante 1</option>
                            <option value="participante2">Participante 2</option>
                            <option value="participante3">Participante 3</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="descricaoAgendamento" class="col-form-label">Descrição:</label>
                        <input type="text" class="form-control" id="descricaoAgendamento" placeholder="Digite uma pequena descrição">
                    </div>
                <div>                           
                </br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="deletarAgendamento();">Deletar</button>
                <button type="button" class="btn btn-primary" onclick="cadastrarAgendamento();">Salvar</button>
            </div>
            </div>
        </div>
    </div>
    <!-- Fim - Modal agendamento-->

    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
    <script>

        $(document).ready(function() {
            $('#clienteAgendamento').select2({
                dropdownParent: $('#modalAgendamento'),
                dropdownAutoWidth: true,
                width: '100%'
            });
        });
        
        $(document).ready(function() {
            $('#participantes').select2({
                dropdownParent: $('#modalAgendamento'),
                dropdownAutoWidth: true,
                width: '100%'
            });
        });

        
        
        //Modal
        var abrirModalTipo = document.getElementById('modalTipo')
        abrirModalTipo.addEventListener('show.bs.modal', function (event) 
        {
            var button = event.relatedTarget
            var titulo = button.getAttribute('data-titulo')
            var id = button.getAttribute('data-id')
            //var modalTitle = abrirModal.querySelector('.modal-title')
            //modalTitle.textContent = titulo
            carregarTipos();
        });

        var abrirModalAgendamento = document.getElementById('modalAgendamento')
        abrirModalAgendamento.addEventListener('show.bs.modal', function (event) 
        {
            limparCampos();
            var button = event.relatedTarget
            var titulo = button.getAttribute('data-titulo')
            var id = button.getAttribute('data-id')
            //var modalTitle = abrirModal.querySelector('.modal-title')
            //modalTitle.textContent = titulo
            carregarUsuariosSelect('participantes');

            if (id > 0)
            {
                $('#idAgendamento').val(id);
                buscarAgendamento();

                $('.novoAgendamento').hide();
                $('#clienteAgendamentoInput').prev().show();
            }
            else
            {
                $('#clienteAgendamentoInput').hide();
                $('#clienteAgendamentoInput').prev().show();
            }

            carregarClientesSelect();
            carregarTiposSelect();
        });
    </script>  
