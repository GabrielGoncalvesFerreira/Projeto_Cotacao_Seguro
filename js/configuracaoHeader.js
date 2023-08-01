$(document).ready(function () {
    carregarAgendamentosPendetes();
});

document.getElementById('scroll-right').addEventListener('click', function() {
    var container = document.getElementById('agendamentos');
    container.scrollLeft += 150; // Ajuste este valor conforme necessário
});

/**
 * Função para carregar os dados da tabela de seguro.
 */
function carregarAgendamentosPendetes() {
    // Limpando o valor do campo de ID e removendo o conteúdo da tabela
    $("#agendamentos").empty();
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para consultar os seguros.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_header.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'consutarAgendamento',
        },
        success: function(dados){
            /**
             * Adiciona os dados retornados na tabela de seguro.
             * @type {jQuery}
             */
            $("#agendamentos").append(dados);
            
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

function startSpinner() {
    // Mostrar o spinner
    document.getElementById('loadingSpinner').style.display = 'block';
  }
  
  function stopSpinner() {
    // Ocultar o spinner
    document.getElementById('loadingSpinner').style.display = 'none';
  }