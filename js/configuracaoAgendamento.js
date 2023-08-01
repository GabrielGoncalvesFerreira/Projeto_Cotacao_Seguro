var user_busy_days = [];

/**
 * Função para carregar os dados da tabela de tipos.
 */
function carregarTipos() {
    // Limpando o valor do campo de ID e removendo o conteúdo da tabela
    $("#idTipo").val("");

    startSpinner();
    /**
     * Realiza uma requisição assíncrona para consultar os seguros.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_agendamento.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'consultarTipo',
        },
        success: function(dados){
            /**
             * Adiciona os dados retornados na tabela de seguro.
             * @type {jQuery}
             */
            $("#tabelaTipo>tbody").append(dados);
            
            stopSpinner();
        },
        error: function(dadosErro){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: dadosErro,
                icon: "error",
            });
            
            stopSpinner();
        }
    });
}

/**
 * Função para cadastrar uma tipos.
 */
function cadastrarTipo() {
/** @type {string} */
var vId =  $("#idTipo").val();
/** @type {string} */
var vNome =  $("#nomeTipo").val();
/** @type {string} */
var vDescricao =  $("#descricaoTipo").val();

$("#tabelaTipo>tbody").empty();

    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para cadastrar o categoria.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_agendamento.php',
        cache: false,
        type: "POST",
        data: {
            idTipo: vId,
            nomeTipo: vNome,
            descricaoTipo: vDescricao,
            tipoTransacao: 'cadastrarTipo',
        },
        success: function(dados){
            /**
             * Exibe um alerta com as informações retornadas na resposta.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: dados,
                icon: "info",
            });

            // Carregando os dados atualizados da tabela de seguro
            carregarTipos();
            limparCamposTipos();
            
            stopSpinner();
        },
        error: function(xhr, status, error){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: error.message,
                icon: "error",
            });
            
            stopSpinner();
        }
    });
}

function cadastrarAgendamento() {
    var vidAgendamento =  $("#idAgendamento").val();
    var vcodigocliente =  $("#clienteAgendamento").val();
    var vcodigotipo =  $("#tipoAgendamento").val();
    //var vcodigousuario =  $("#clienteAgendamento").val();
    var vdata =  $("#dataAgendamento").val();
    var vhora =  $("#horaAgendamento").val();
    var vhoraFinal =  $("#horaAgendamentoFinal").val();
    var vobservacao =  $("#descricaoAgendamento").val();
    var vstatus =  $("#statusAgendamento").val();
    var vparticipantes = $('#participantes').val();
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para cadastrar o categoria.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_agendamento.php',
        cache: false,
        type: "POST",
        data: {
            idAgendamento : vidAgendamento,
            codigocliente : vcodigocliente,
            codigotipo : vcodigotipo,
            //codigousuario : vcodigousuario,
            data : vdata,
            hora : vhora,
            horaFinal : vhoraFinal,
            observacao : vobservacao,
            status : vstatus,
            participantes : vparticipantes,
            tipoTransacao: 'cadastrarAgendamento',
        },
        success: function(dados){
            /**
             * Exibe um alerta com as informações retornadas na resposta.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: dados,
                icon: "info",
            });

            Swal.fire({
                title: dados +' Deseja atualizar o calendário?',
                showDenyButton: true,
                confirmButtonText: 'Sim',
                denyButtonText: `Não`,
              }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    recarregarCalendario();
                    $('#modalAgendamento').modal('hide');
                    carregarAgendamentosPendetes();
                } else if (result.isDenied) {
                    $('#modalAgendamento').modal('hide');
                    carregarAgendamentosPendetes();
                }
              })
              
              stopSpinner();
        },
        error: function(xhr, status, error){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: error.message,
                icon: "error",
            });
            
            stopSpinner();
        }
    });
}

function deletarAgendamento() {
    var vidAgendamento =  $("#idAgendamento").val();
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para cadastrar o categoria.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_agendamento.php',
        cache: false,
        type: "POST",
        data: {
            idAgendamento : vidAgendamento,
            tipoTransacao: 'deletarAgendamento',
        },
        success: function(dados){
            /**
             * Exibe um alerta com as informações retornadas na resposta.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: dados,
                icon: "info",
            });

            $('#modalAgendamento').modal('hide');
            carregarAgendamentosPendetes();
            
            stopSpinner();
        },
        error: function(xhr, status, error){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: error.message,
                icon: "error",
            });
            
            stopSpinner();
        }
    });
}
    
/**
 * Função para carregar os dados da tabela de clientes.
 */
function carregarClientesSelect() {   

    $("#clienteAgendamento").empty();
    $("#clienteAgendamento").append('<option value="0" selected>Todos</option>');
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para consultar os seguros.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_agendamento.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'carregarDadosClientes',
        },
        success: function(dados){
            /**
             * Adiciona os dados retornados na tabela de seguro.
             * @type {jQuery}
             */
            $("#clienteAgendamento").append(dados);
            
            stopSpinner();
        },
        error: function(dadosErro){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: dadosErro,
                icon: "error",
            });
            
            stopSpinner();
        }
    });
}

/**
 * Função para carregar os dados da tabela de tipos.
 */
function carregarTiposSelect() {   

    $("#tipoAgendamento").empty();
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para consultar os seguros.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_agendamento.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'carregarDadosTipos',
        },
        success: function(dados){
            /**
             * Adiciona os dados retornados na tabela de seguro.
             * @type {jQuery}
             */
            $("#tipoAgendamento").append(dados);
            
            stopSpinner();
        },
        error: function(dadosErro){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: dadosErro,
                icon: "error",
            });
            
            stopSpinner();
        }
    });
}

/**
 * Função para carregar os dados de agendamentos.
 */
function carregarAgendamentos(vData) {   

    $("#divPrincipal").empty();
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para consultar os seguros.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_agendamento.php',
        cache: false,
        type: "POST",
        data: {
            data: vData,
            tipoTransacao: 'carregarAgendamentos',
        },
        success: function(dados){
            /**
             * Adiciona os dados retornados na tabela de seguro.
             * @type {jQuery}
             */
            $("#divPrincipal").append(dados);
            
            stopSpinner();
        },
        error: function(dadosErro){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: dadosErro,
                icon: "error",
            });
            
            stopSpinner();
        }
    });
}

/**
 * Função para buscar um registro de agendamento.
 */
function buscarAgendamento() {
    /** @type {string} */
    var vId =  $("#idAgendamento").val();
    
    startSpinner();
    
    
    /**
     * Realiza uma requisição assíncrona para buscar o registro de agendamentos.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_agendamento.php',
        cache: false,
        type: "POST",
        dataType: "JSON", 
        data: {
            id: vId,
            tipoTransacao: 'buscarDadosAgendamento',
        },
        success: function(dados){
            /**
             * Preenche os campos com os dados retornados na resposta.
             */
            limparCampos();
            $('#idAgendamento').val(vId);
            $('#dataAgendamento').val(dados.data);
            $('#horaAgendamento').val(dados.hora);
            $('#horaAgendamentoFinal').val(dados.horafinal);
            $('#statusAgendamento').val(dados.status);
            $('#clienteAgendamentoInput').val(dados.nomeCliente);
            $('#tipoAgendamento').val(dados.codigotipo);
            $('#descricaoAgendamento').val(dados.observacao);
            $('#participantes').val(dados.participantes).trigger('change');
            
            stopSpinner();
        
        },
        error: function(xhr, status, error){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: JSON.stringify(error.message),
                icon: "error",
            });

            console.log(error.message);
            
            stopSpinner();
        }
    });
}


async function buscarAgendamentosPendentes() { 
    
    var arrayDatas = [];
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para buscar o registro de agendamentos.
     * @type {$.ajax}
     */
    await $.ajax({
        url: 'configuracao_agendamento.php',
        cache: false,
        type: "POST",
        dataType: "JSON", 
        data: {
            tipoTransacao: 'buscarAgendamentosPendetes',
        },
        success: function(dados){
            /**
             * Preenche os campos com os dados retornados na resposta.
             */
            arrayDatas = dados;
            
            stopSpinner();
        
        },
        error: function(xhr, status, error){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: JSON.stringify(error.message),
                icon: "error",
            });

            console.log(error.message);
            
            stopSpinner();
        }
    });

    return await Promise.resolve(arrayDatas);
}


function limparCampos()
{
    $('#idAgendamento').val('');
    $('#dataAgendamento').val('');
    $('#horaAgendamento').val('');
    $('#horaAgendamentoFinal').val('');
    $('#statusAgendamento').val('');
    $('#clienteAgendamentoInput').val('');
    $('#tipoAgendamento').val('');
    $('#descricaoAgendamento').val('');
}

function pegarIdTipo(id)
{
    $("#idTipo").val(id);
}

function recarregarCalendario()
{
    buscarAgendamentosPendentes().then((response) => {
        user_busy_days = response;

        // Atualize o DatePicker
        calendario();
    });
}

function calendario()
{
    startSpinner();
    
    buscarAgendamentosPendentes().then((response) => {
        //var events = {};
        var today               = new Date();
        var today_formatted     = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+('0'+today.getDate()).slice(-2);
        user_busy_days = response;
        
        $('#calendar').datepicker('destroy');

        $('#calendar').datepicker({
            language: 'pt-BR',
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            todayBtn: "linked",
            weekStart: 1,
            daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],

            beforeShowDay: function (date) {

                var ano = date.getFullYear();
                var mes = (date.getMonth()+1).toString().length == 1 ? '0'+(date.getMonth()+1) : (date.getMonth()+1);
                var dia = ('0'+date.getDate()).slice(-2);

                calender_date = ano + '-' + mes + '-'+ dia;

                var search_index = $.inArray(calender_date, user_busy_days);

                if (search_index > -1) {
                    return {classes: 'highlight-event', tooltip: 'User available on this day.'};
                }else{
                    return {classes: 'highlighted-cal-dates', tooltip: 'User not available on this day.'};
                }

            }
        });
    });
    
    stopSpinner();
}

function configuracaoGrafico()
{
    $.fn.datepicker.dates['pt-BR'].daysMin = ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"];
}

$(document).ready(function() {
    $('#calendar').on('changeDate', function(e) {
        var date = $('#calendar').datepicker('getFormattedDate');
        $('#dataAgendamento').val(date);
        carregarAgendamentos(date);
    })
});

            /*dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
            dayNamesMin: ['D','S','T','Q','Q','S','S'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],*/

