/**
 * Função para carregar os dados da tabela de categorias.
 */
function carregarCategorias() {
    // Limpando o valor do campo de ID e removendo o conteúdo da tabela
    $("#idCategoria").val("");
    $("#tabelaCategoria>tbody").empty();
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para consultar os seguros.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_servico.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'consultarCategoria',
        },
        success: function(dados){
            /**
             * Adiciona os dados retornados na tabela de seguro.
             * @type {jQuery}
             */
            $("#tabelaCategoria>tbody").append(dados);
            
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
 * Função para carregar os dados da tabela de categorias.
 */
function carregarCategoriasSelect() {   

    $("#categoriasServico").empty();
    $("#categoriasServico").append('<option value="0" selected>Todos</option>');
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para consultar os seguros.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_servico.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'carregarDadosCategoriaSelect',
        },
        success: function(dados){
            /**
             * Adiciona os dados retornados na tabela de seguro.
             * @type {jQuery}
             */
            $("#categoriasServico").append(dados);
            
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
 * Função para cadastrar uma categoria.
 */
function cadastrarCategoria() {
    /** @type {string} */
    var vId =  $("#idCategoria").val();
    /** @type {string} */
    var vNome =  $("#nomeCategoria").val();
    /** @type {string} */
    var vDescricao =  $("#descricaoCategoria").val();
    
    startSpinner();

    /**
     * Realiza uma requisição assíncrona para cadastrar o categoria.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_servico.php',
        cache: false,
        type: "POST",
        data: {
            idCategoria: vId,
            nomeCategoria: vNome,
            descricaoCategoria: vDescricao,
            tipoTransacao: 'cadastrarCategoria',
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
            carregarCategorias();
            limparCamposCategoria();
            
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
 * Função para carregar os dados da tabela de servico.
 */
function carregarServico(limite) {
    // Limpando o valor do campo de ID e removendo o conteúdo da tabela
    $("#idServico").val("");
    $("#tabelaServico>tbody").empty();

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
        url: 'configuracao_servico.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'consultarServico',
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
            $("#tabelaServico>tbody").append(dados);
            
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
 * Função para cadastrar uma serviço.
 */
function cadastrarServico() {
    /** @type {string} */
    var vIdServico =  $("#idServico").val();
    /** @type {string} */
    var vNomeServico =  $("#nomeServico").val();
    /** @type {string} */
    var vDescricaoServico =  $("#descricaoServico").val();
    /** @type {string} */
    var vCategoriaServico =  $("#categoriasServico").val();
    /** @type {boolean} */
    var vStatus =  $("#checkStatusServico").is(':checked') == true ? 'A' : 'D' ;
    
    startSpinner();

    /**
     * Realiza uma requisição assíncrona para cadastrar o categoria.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_servico.php',
        cache: false,
        type: "POST",
        data: {
            idServico: vIdServico,
            nomeServico: vNomeServico,
            descricaoServico: vDescricaoServico,
            categoriaServico: vCategoriaServico,
            statusServico: vStatus,
            tipoTransacao: 'cadastrarServico',
        },
        success: function(dados){
            /**
             * Exibe um alerta com as informações retornadas na resposta.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: dados,
                icon: "success",
            });

            // Carregando os dados atualizados da tabela de seguro
            carregarCategorias();
            limparCamposCategoria();
            carregarServico('');
            
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
 * Função para buscar um registro de seguro.
 */
function buscarServico() {
    /** @type {string} */
    var vId =  $("#idServico").val();
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para buscar o registro de seguro.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_servico.php',
        cache: false,
        type: "POST",
        dataType: "JSON", 
        data: {
            id: vId,
            tipoTransacao: 'buscarServico',
        },
        success: function(dados){
            /**
             * Preenche os campos com os dados retornados na resposta.
             */
            $('#nomeServico').val(dados.nome);
            $('#descricaoServico').val(dados.descricao);
            $('#categoriasServico').val(dados.idCategoria);
            dados.status == 'A' ? $('#checkStatusServico').prop("checked", true) : $('#checkStatusServico').prop("checked", false);
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

function preencherCamposDadosCategoria(button)
{
    var idCategoria = button.id;
    var row = button.parentNode.parentNode;
    var nome = row.children[1].innerText;
    var descricao = row.children[2].innerText;

    $("#idCategoria").val(idCategoria);
    $("#nomeCategoria").val(nome);
    $("#descricaoCategoria").val(descricao);
}

function excluirCategoria(vId){
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para buscar o registro de seguro.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_servico.php',
        cache: false,
        type: "POST",
        dataType: "JSON", 
        data: {
            id: vId,
            tipoTransacao: 'excluirCategoria'
        },
        success: function(){
            /**
             * Exibe um alerta com as informações retornadas na resposta.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: "Categoria excluida com sucesso.",
                icon: "success",
            });
            carregarCategorias();
            
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

function limparCamposCategoria()
{
    $("#idCategoria").val("");
    $("#nomeCategoria").val("");
    $("#descricaoCategoria").val("");
}