
/**
 * Função para carregar os dados de acesso
 */
function carregarDadosAcesso() {

    $.ajax({
        url: 'configuracao_acesso.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'consultarAcesso',
        },
        success: function(dados){
            /**
             * Adiciona os dados retornados na tabela de seguro.
             * @type {jQuery}
             */
            $(".menu").append(dados);
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
        }
    });
}