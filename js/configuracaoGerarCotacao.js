$('#tipoCotacao').change(function() {
    var idCategoria = $(this).val();
    consultarServicos(idCategoria);
});

/**
 * Função para carregar os dados dos corretores da tabela usuários.
 */
function carregarCorretores() {

    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para consultar os corretores.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_cotacao.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'consultarCorretores',
        },
        dataType: 'json',
        success: function(data){
            /**
             * Adiciona os dados retornados no campo select.
             * @type {jQuery}
             */
            var select = $('#nomeCorretor');
            data.forEach(function(corretor) {
                var option = $('<option>', {
                    value: corretor.id,
                    text: corretor.nome
                });
                select.append(option);
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: errorThrown,
                icon: "error",
            });
            
            stopSpinner();
        }
    });
}

/**
 * Função para carregar os dados de tipos de cotacao.
 */
function carregarTiposCotacao() {

    startSpinner();
    /**
     * Realiza uma requisição assíncrona para consultar os corretores.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_cotacao.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'consultarTiposCotacao',
        },
        dataType: 'json',
        success: function(data){
            /**
             * Adiciona os dados retornados no campo select.
             * @type {jQuery}
             */
            var select = $('#tipoCotacao');
            data.forEach(function(tipoCotacao) {
                var option = $('<option>', {
                    value: tipoCotacao.idcategoria,
                    text: tipoCotacao.nome
                });
                select.append(option);
            });
            
            stopSpinner();
        },
        error: function(jqXHR, textStatus, errorThrown){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: errorThrown,
                icon: "error",
            });
            
            stopSpinner();
        }
    });
}

/**
 * Função para carregar os dados de seguradoras.
 */
function consultarSeguradoras() {

    startSpinner();
    /**
     * Realiza uma requisição assíncrona para consultar as seguradoras.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_cotacao.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'consultarSeguradoras',
        },
        dataType: 'json',
        success: function(data){
            /**
             * Adiciona os dados retornados no campo select.
             * @type {jQuery}
             */
            var select = $('#selectSeguradoras');
            data.forEach(function(nomeSeguradora) {
                var option = $('<option>', {
                    value: nomeSeguradora.id,
                    text: nomeSeguradora.nome
                });
                select.append(option);
            });
            
            stopSpinner();
        },
        error: function(jqXHR, textStatus, errorThrown){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: errorThrown,
                icon: "error",
            });
            
            stopSpinner();
        }
    });
}

/**
 * Função para carregar os dados de seguradoras.
 */
function consultarServicos(idCategoria) {
    return new Promise((resolve, reject) => {
        $('#selectCoberturas').empty();
        $.ajax({
            url: 'configuracao_cotacao.php',
            cache: false,
            type: "POST",
            data: {
                tipoTransacao: 'consultarServicos',
                idCategoria: idCategoria
            },
            dataType: 'json',
            success: function(data){
                const descricaoCoberturas = {};
                var select = $('#selectCoberturas');
                data.forEach(function(nomeCobertura) {
                    var option = $('<option>', {
                        value: nomeCobertura.idservico,
                        text: nomeCobertura.nome
                    });
                    select.append(option);

                    descricaoCoberturas[nomeCobertura.nome] = nomeCobertura.descricao;
                });
                resolve(descricaoCoberturas);
            },
            error: function(jqXHR, textStatus, errorThrown){
                Swal.fire({
                    title: "Aviso",
                    text: errorThrown,
                    icon: "error",
                });
                reject(errorThrown);
            }
        });
    });
}
