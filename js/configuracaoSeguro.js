/**
 * Função para cadastrar um seguro.
 */
function cadastrarSeguro() {
    /** @type {string} */
    var vId =  $("#idSeguradora").val();
    /** @type {string} */
    var vNome =  $("#nomeSeguradora").val();
    /** @type {string} */
    var vDescricao =  $("#descricaoSeguro").val();
    /** @type {boolean} */
    var vStatus =  $("#checkStatusSeguro").is(':checked') == true ? 'A' : 'D' ;
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para cadastrar o seguro.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_seguro.php',
        cache: false,
        type: "POST",
        data: {
            id: vId,
            nome: vNome,
            descricao: vDescricao,
            status: vStatus, 
            tipoTransacao: 'cadastrarSeguro',
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
            $('#modalSeguradora').modal('hide');
            limparCampos();

            // Carregando os dados atualizados da tabela de seguro
            carregarSeguro(10);
            
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
 * Função para carregar os dados da tabela de seguro.
 */
function carregarSeguro(limite) {
    // Limpando o valor do campo de ID e removendo o conteúdo da tabela
    $("#idSeguradora").val("");
    $("#tabelaSeguro>tbody").empty();
    
    var vid = $("#pesquisaId").val();
    var vnome = $("#pesquisaSeguro").val();
    var vlimite = limite;
    var vstatus = $("#checkPesquisaStatus").is(':checked') == true ? 'A' : 'I' ;
    
    startSpinner();
        
    /**
     * Realiza uma requisição assíncrona para consultar os seguros.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_seguro.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'consultarSeguro',
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
            $("#tabelaSeguro>tbody").append(dados);
            
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
 * Função para buscar um registro de seguro.
 */
function buscarRegistro() {
    /** @type {string} */
    var vId =  $("#idSeguradora").val();
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para buscar o registro de seguro.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_seguro.php',
        cache: false,
        type: "POST",
        dataType: "JSON", 
        data: {
            id: vId,
            tipoTransacao: 'buscarRegistro',
        },
        success: function(dados){
            /**
             * Preenche os campos com os dados retornados na resposta.
             */
            $('#nomeSeguradora').val(dados.nome);
            $('#descricaoSeguro').val(dados.descricao);
            console.log(dados);
            
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

/**
 * Função para limpar os campos de seguro.
 */
function limparCampos() {
    $("#idSeguradora").val("");
    $("#nomeSeguradora").val("");
    $("#descricaoSeguro").val("");
}