
/**
 * Função para carregar os dados da tabela de clientes.
 */
function carregarDadosClientes(valorLimite) {
    // Limpando o valor do campo de ID e removendo o conteúdo da tabela
    $("#idSeguradora").val("");
    $("#tabelaClientes>tbody").empty();

    var vid = $("#pesquisaId").val();
    var vnome = $("#pesquisaNome").val();
    var vlimite = valorLimite;
    var vstatus = $("#checkPesquisaStatus").is(':checked') == true ? 'A' : 'I' ;
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para consultar os seguros.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_cliente.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'consultarClientes',
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
            $("#tabelaClientes>tbody").append(dados);
            
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
 * Cadastrar novos clientes
 */
function cadastrarCliente(){
    /** @type {string} */ 
    var vcodigoCliente = $("#codigoCliente").val();
    /** @type {string} */ 
    var vnomeCliente = $("#nomeCliente").val();
    /** @type {string} */ 
    var vtipo = $("#tipoCliente").val() == 'cpf' ? 'PF' : 'PJ';
    /** @type {string} */ 
    var videntificacao = $("#identificador").val();
    /** @type {string} */ 
    var vorigem = $("#origem").val();
    /** @type {string} */ 
    var vcidade = $("#cidade").val();
    /** @type {string} */ 
    var vcep = $("#cep").val();
    
    startSpinner();

    /**
     * Realiza uma requisição assíncrona para cadastrar o novo cliente.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_cliente.php',
        cache: false,
        type: "POST",
        data: {
            codigoCliente: vcodigoCliente,
            nomeCliente: vnomeCliente,
            tipo: vtipo,
            identificacao: videntificacao,
            origem: vorigem,
            cidade: vcidade,
            cep: vcep,
            tipoTransacao: 'cadastrarCliente',
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

            // Ocultando o modal e realizando a limpeza dos campos
            $('#modalCliente').modal('hide');
            limparCampos();

            // Carregando os dados atualizados da tabela de clientes
            carregarDadosClientes();
            
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
 * Função para buscar o registro de um cliente
 */
function buscarRegistroCliente(){
    /** @type {string} */
    var vIdCliente =  $("#codigoCliente").val();
    
    startSpinner();

    /**
     * Realiza uma requisição assíncrona para buscar o registro de cliente.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_cliente.php',
        cache: false,
        type: "POST",
        dataType: "JSON", 
        data: {
            id: vIdCliente,
            tipoTransacao: 'buscarRegistroCliente',
        },
        success: function(dados){
            /**
             * Preenche os campos com os dados retornados na resposta.
             */
            $('#nomeCliente').val(dados.nomeCliente);
            $('#tipoCliente').val(dados.tipo == "PJ" ? 'cnpj' : 'cpf');
            $('#identificador').val(dados.identificacao);
            $('#origem').val(dados.origem);
            $('#cidade').val(dados.cidade);
            $('#cep').val(dados.cep);
            
            stopSpinner();
        },
        error: function(xhr, status, error){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: JSON.stringify(dadosErro),
                icon: "error",
            });

            console.log(error.message);
            
            stopSpinner();
        }
    });
}

/**
 * Função para carregar os dados de histórico de agendamentos de um cliente.
 */
function carregarDadosHistoricoAgendamentosClientes() {  
    /** @type {string} */
    var vIdCliente =  $("#codigoCliente").val();

    $("#tabelaHistoricoAgendamentos>tbody").empty();
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para consultar os seguros.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_cliente.php',
        cache: false,
        type: "POST",
        data: {
            id: vIdCliente,
            tipoTransacao: 'carregarDadosHistoricoAgendamentosClientes',
        },
        success: function(dados){
            /**
             * Adiciona os dados retornados na tabela de historico de clientes.
             * @type {jQuery}
             */
            $("#tabelaHistoricoAgendamentos>tbody").append(dados);
            
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
            console.log(error.message);
            
            stopSpinner();
        }
    });
}

/**
 * Função para limpar os campos do modal de clientes.
 */
function limparCampos() {
    $("#codigoCliente").val("");
    $("#nomeCliente").val("");
    $("#tipoCliente").val("");
    $("#identificador").val("");
    $("#origem").val("");
    $("#cidade").val("");
    $("#cep").val("");
}