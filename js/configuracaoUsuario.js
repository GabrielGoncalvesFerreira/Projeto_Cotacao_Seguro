/**
 * Função para carregar os dados da tabela de usuario.
 */
function carregarUsuarios(limite) {
    // Limpando o valor do campo de ID e removendo o conteúdo da tabela
    $("#tabelaUsuario>tbody").empty();

    var vid = $("#pesquisaId").val();
    var vnome = $("#pesquisaNome").val();
    var vlimite = limite;
    var vstatus = $("#checkPesquisaStatus").is(':checked') == true ? 'A' : 'I' ;
    
    startSpinner();

    /**
     * Realiza uma requisição assíncrona para consultar os seguros.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_usuario.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'carregarDadosUsuario',
            id: vid,
            nome: vnome,
            status: vstatus,
            limite: vlimite,
        },
        success: function(dados){
            /**
             * Adiciona os dados retornados na tabela de seguro.
             * @type {jQuery}
             */
            $("#tabelaUsuario>tbody").append(dados);
            
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
 * Função para carregar os dados da tabela de usuario.
 */
function carregarUsuariosSelect(idSelect) {
    // Limpando o valor do campo de ID e removendo o conteúdo da tabela
    $("#"+idSelect).empty();
    
    startSpinner();

    /**
     * Realiza uma requisição assíncrona para consultar os seguros.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_usuario.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'carregarDadosUsuarioSelect',
        },
        success: function(dados){
            /**
             * Adiciona os dados retornados na tabela de seguro.
             * @type {jQuery}
             */
            $("#"+idSelect).append(dados);
            
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
 * Função para buscar dados de usuário
 */
function buscarUsuario() {
    /** @type {string} */
    var vId =  $("#codigoUsuario").val();
    
    startSpinner();

    /**
     * Realiza uma requisição assíncrona para cadastrar o categoria.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_usuario.php',
        cache: false,
        type: "POST",
        dataType: "JSON", 
        data: {
            id: vId,
            tipoTransacao: 'buscarUsuario',
        },
        success: function(dados){
            $('#nomeUsuario').val(dados.nome);
            $('#emailUsuario').val(dados.usuario);
            $('#usuario').val(dados.email);
            $('#checkClientes').prop('checked', dados.acessoCliente);
            $('#checkSeguro').prop('checked', dados.acessoSeguro);
            $('#checkUsuario').prop('checked', dados.acessoUsuario);
            $('#checkServico').prop('checked', dados.acessoServico);
            $('#checkAgendamento').prop('checked', dados.acessoAgendamento);
            $('#checkConfiguracao').prop('checked', dados.acessoConfiguracao);
            $('#checkCotacao').prop('checked', dados.acessoCotacao);
            $('#checkStatusUsuario').prop('checked', dados.ativo);
            
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
 * Função para cadastrar um usuário.
 */
function cadastrarUsuario() {
    /** @type {string} */
    var vid =  $("#codigoUsuario").val();
    var vnome =  $("#nomeUsuario").val();
    var vusuario =  $("#usuario").val();
    var vemail =  $("#emailUsuario").val();
    var vsenha =  $("#senhaUsuario").val();
    var vacessoCliente =  $("#checkClientes").is(':checked') == true ? '1' : '0' ;
    var vacessoSeguro =  $("#checkSeguro").is(':checked') == true ? '1' : '0' ;
    var vacessoUsuario =  $("#checkUsuario").is(':checked') == true ? '1' : '0' ;
    var vacessoServico =  $("#checkServico").is(':checked') == true ? '1' : '0' ;
    var vacessoAgendamento =  $("#checkAgendamento").is(':checked') == true ? '1' : '0' ;
    var vacessoCotacao =  $("#checkCotacao").is(':checked') == true ? '1' : '0' ;
    var vacessoConfiguracao =  $("#checkConfiguracao").is(':checked') == true ? '1' : '0' ;
    var vativo =  $("#checkStatusUsuario").is(':checked') == true ? 'A' : 'I' ;

    if (((vid == "" || vid == "0") && vsenha != "") || (vid != ""))
    {
        
        startSpinner();
        
        /**
         * Realiza uma requisição assíncrona para cadastrar o categoria.
         * @type {$.ajax}
         */
        $.ajax({
            url: 'configuracao_usuario.php',
            cache: false,
            type: "POST",
            data: {
                id: vid,
                nome: vnome,
                usuario: vusuario,
                email: vemail,
                acessoCliente: vacessoCliente,
                acessoSeguro: vacessoSeguro,
                acessoUsuario: vacessoUsuario,
                acessoServico: vacessoServico,
                acessoAgendamento: vacessoAgendamento,
                acessoCotacao: vacessoCotacao,
                acessoConfiguracao: vacessoConfiguracao,
                ativo: vativo,
                senha: vsenha,
                tipoTransacao: 'cadastroUsuario',
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
                carregarUsuarios('');
                
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
    else
    {
        Swal.fire({
            title: "Aviso",
            text: "Digite uma senha.",
            icon: "info",
        });
    }
}

function limparDados()
{
    $('#checkClientes').prop('checked',      false);
    $('#checkSeguro').prop('checked',        false);
    $('#checkUsuario').prop('checked',       false);
    $('#checkServico').prop('checked',       false);
    $('#checkAgendamento').prop('checked',   false);
    $('#checkConfiguracao').prop('checked',  false);
    $('#checkStatusUsuario').prop('checked', false);
    $('#codigoUsuario').val("");
    $("#nomeUsuario").val("");
    $("#usuario").val("");
    $("#emailUsuario").val("");
    $("#senhaUsuario").val("");

    desabilitarSenha();
}

function validarTrocaSenha()
{
    if(document.getElementById('checkSenha').checked)
    {
        habilitarSenha(); 
    }
    else
    {
        desabilitarSenha();
    }
}

function habilitarSenha()
{
    $('#senhaUsuario').val('');
    $('#senhaUsuario').prop("disabled", false);
}

function desabilitarSenha()
{
    $('#senhaUsuario').val('');
    $('#senhaUsuario').prop("disabled", true);
}