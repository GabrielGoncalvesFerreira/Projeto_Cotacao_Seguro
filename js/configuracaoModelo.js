$(document).ready(function () {
    carregarCategoriasSelect();
    document.getElementById('dadoExistente').style.display = 'none';
});

function tipoModelo(valor)
{
    if (valor == 'novo')
    {
        document.getElementById('dadoExistente').style.display = 'none';
        document.getElementById('dadoNovo').style.display = 'block';
    }
    else
    {
        document.getElementById('dadoNovo').style.display = 'none';
        document.getElementById('dadoExistente').style.display = 'block';
    }
}

function salvarModelo(){
    /** @type {string} */
    var vIdModelo         =  document.querySelector('input[name="tipoModelo"]:checked').value == 'N' ? '' : $('#modelo').val();
    var vNomeModelo       =  document.querySelector('input[name="tipoModelo"]:checked').value == 'N' ? $('#inputModelo').val() : $('#modelo').text();
    var vIdSeguros        =  $('#selectSeguradoras').val();
    var vIdCoberturas     =  $('#selectCoberturas').val();
    var vValoresCobertura =  pegarDadosCobertura();
    var vFormaPagamento   =  pegarDadosPagamento();
    var vIdCorretor       =  $('#nomeCorretor').val();
    var vContato          =  $('#contatoCorretor').val();
    var vIdTipoCotacao    =  $('#tipoCotacao').val();
    var vtipoContrato     =  $('input[name="tipoContrato"]:checked').next('label').text();
    var vDadosTabela      =  extrairDadosTabela();

    startSpinner();

    /**
     * Realiza uma requisição assíncrona para buscar o registro de cliente.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_modelo.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'inserirModelo',
            idModelo: vIdModelo,
            nomeModelo: vNomeModelo,
            idSeguros: vIdSeguros,
            idCoberturas: vIdCoberturas,
            valoresCobertura: vValoresCobertura,
            formaPagamento: vFormaPagamento,
            idCorretor: vIdCorretor,
            contato: vContato,
            idTipoCotacao: vIdTipoCotacao,
            tipoContrato: vtipoContrato,
            dadosTabela: vDadosTabela,
        },
        success: function(dados){
            /**
             * Preenche os campos com os dados retornados na resposta.
             */
            Swal.fire({
                title: "Aviso",
                text: dados,
                icon: "info",
            });

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
            stopSpinner();
            console.log(error.message);
        }
    });
}

function deletarModelo(){
    /** @type {string} */
    var vIdModelo =  $('#modelo').val();
    
    startSpinner();

    if (document.querySelector('input[name="tipoModelo"]:checked').value == 'N')
    {
        Swal.fire({
            title: "Aviso",
            text: "Não é possivél deletar um modelo que esteja em criação.",
            icon: "info",
        });
        
        stopSpinner();
    }
    else if (parseFloat(vIdModelo) <= 0) 
    {
        Swal.fire({
            title: "Aviso",
            text: "Selecione um modelo para continuar.",
            icon: "info",
        });
        
        stopSpinner();
    }
    else
    {
        Swal.fire({
            title: 'Realmente deseja deletar o modelo?',
            text: "Após esse procedimento não será possivél nenhum tipo de recuperação dos dados.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Deletar'
        }).then((result) => {
            if (result.isConfirmed) {
            /**
             * Realiza uma requisição assíncrona para buscar o registro de cliente.
             * @type {$.ajax}
                */
                $.ajax({
                    url: 'configuracao_modelo.php',
                    cache: false,
                    type: "POST",
                    data: {
                        tipoTransacao: 'deletarModelo',
                        idModelo: vIdModelo,
                    },
                    success: function(dados){
                        
                        stopSpinner();
                        
                        window.location.reload(true);
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
                        
                        window.location.reload(true);
                    }
                });
            }
        })
        
        stopSpinner();
    }
}

function pegarDadosPagamento() {
    var paymentData = [];
    $("#paymentTable tbody tr").each(function(index) {
        var row = [];
        $(this).find('input[type="text"]').each(function() {
            row.push($(this).val());
        });
        if (index == 0) {
            row.unshift("Total");
        } else {
            row.unshift($(this).find('select').val());
        }
        paymentData.push(row);
    });

    return paymentData;
}


function pegarDadosCobertura() {
    var array = []; // Array para armazenar os valores
    var itensPorLinha = $('#coverageInsuranceTable thead th').length - 1; // O número de seguros
    var linhaAtual = [];

    $('#coverageInsuranceTable tbody tr td input[type="text"]').each(function(index, element) {
        linhaAtual.push($(element).val());

        // Se já adicionamos 'itensPorLinha' ao 'linhaAtual', adicionamos 'linhaAtual' ao array principal
        // e começamos uma nova linha
        if (linhaAtual.length == itensPorLinha) {
            array.push(linhaAtual);
            linhaAtual = [];
        }
    });

    // Se tivermos itens restantes na linha atual após terminar o loop, os adicionamos ao array principal
    if (linhaAtual.length > 0) {
        array.push(linhaAtual);
    }

    return array;
}

function carregarCategoriasSelect() {   

    $("#modelo").empty();
    $("#modelo").append('<option value="0" selected>---- Selecione um Modelo ----</option>');
    
    /**
     * Realiza uma requisição assíncrona para consultar os seguros.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_modelo.php',
        cache: false,
        type: "POST",
        data: {
            tipoTransacao: 'carregarSelectModelo',
        },
        success: function(dados){
            /**
             * Adiciona os dados retornados na tabela de seguro.
             * @type {jQuery}
             */
            $("#modelo").append(dados);
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

function selecionarOptions(idSelect, values) {
    var select = document.getElementById(idSelect);
    var options = select.options;

    // Primeiro desmarque todas as opções
    for (var i = 0; i < options.length; i++) {
        options[i].selected = false;
    }

    // Em seguida, percorra os valores fornecidos e selecione as opções correspondentes
    for (var j = 0; j < values.length; j++) {
        for (var i = 0; i < options.length; i++) {
            if (options[i].value == values[j]) {
                // Crie um novo evento 'mousedown'
                var mouseEvent = new MouseEvent('mousedown', {
                    bubbles: true,
                    cancelable: true,
                    view: window
                });

                // Dispare o evento 'mousedown' na opção
                options[i].dispatchEvent(mouseEvent);

                // Agora selecione a opção
                options[i].selected = true;

                break;
            }
        }
    }

    // Criando o evento change
    var event = new Event('change');

    // Disparando o evento change
    select.dispatchEvent(event);
}

function extrairDadosTabela() {
    // Obtenha todas as tabelas dentro do container
    let tablesContainer = document.getElementById('tablesContainer');
    let cards = tablesContainer.getElementsByClassName('card');
    let tablesData = [];

    for (let card of cards) {
        // Obtenha o nome da tabela
        let tableName = card.getElementsByClassName('card-header')[0].textContent;

        // Obtenha o conteúdo dos campos
        let table = card.getElementsByTagName('table')[0];
        let rows = table.getElementsByTagName('tr');
        let tableRows = [];

        // Obtenha os nomes dos campos da primeira linha (cabeçalho da tabela)
        let headerCells = rows[0].getElementsByTagName('th');
        let fieldNames = [];
        for (let cell of headerCells) {
            fieldNames.push(cell.textContent);
        }

        // Obtenha os valores das outras linhas
        for (let i = 1; i < rows.length; i++) {
            let row = rows[i];
            let cells = row.getElementsByTagName('td');
            let rowCells = {};
            for (let j = 0; j < cells.length; j++) {
                let cell = cells[j];
                let input = cell.getElementsByTagName('input')[0];
                if (input) {
                    // Use o nome do campo correspondente como a chave no objeto rowCells
                    rowCells[fieldNames[j]] = input.value;
                }
            }
            tableRows.push(rowCells);
        }
        tablesData.push({ tableName: tableName, rows: tableRows });
    }
    return tablesData;
}



function carregarModelo() {
    consultarModelo().then((dados) => {
        console.log(dados);

        var idUsuario = dados.modelo[0].idUsuario;
        selecionarOptions('nomeCorretor', [idUsuario.toString()]);

        var contatoCorretorModelo = dados.modelo[0].contato;
        var campoContatoCorretor = document.getElementById("contatoCorretor");
        campoContatoCorretor.value = contatoCorretorModelo;

        var idTipoCotacaoModelo = dados.modelo[0].idTipoCotacao;
        selecionarOptions('tipoCotacao', [idTipoCotacaoModelo.toString()]);

        var tipoContratoModelo = dados.modelo[0].tipoContrato;
        var radios = document.querySelectorAll('input[name="tipoContrato"]');
        for (var i = 0; i < radios.length; i++) {
            var label = document.querySelector('label[for="' + radios[i].id + '"]');
            if (label.textContent === tipoContratoModelo) {
                radios[i].checked = true;
                break;
            }
        }

        var idCoberturas = [...new Set(dados.cobertura.map(function(obj) {
            return String(obj.idCobertura);
        }))];

        var idSeguros = [...new Set(dados.seguro.map(function(obj) {
            return String(obj.idSeguro);
        }))];

        selecionarOptions('selectCoberturas', idCoberturas);
        selecionarOptions('selectSeguradoras', idSeguros);

        var descricoes = dados.cobertura.map(function(obj) {
            return obj.descricao;
        });

        var table = document.getElementById('coverageInsuranceTable');
        var rows = Array.from(table.querySelectorAll('tbody tr'));
        var numCols = rows[0].querySelectorAll('input').length;
        var inputsByColumn = [];

        for (var col = 0; col < numCols; col++) {
            for (var row = 0; row < rows.length; row++) {
                var input = rows[row].querySelectorAll('input')[col];
                inputsByColumn.push(input);
            }
        }

        for (var i = 0; i < descricoes.length && i < inputsByColumn.length; i++) {
            inputsByColumn[i].value = descricoes[i];
        }

        // Limpa a tabela de pagamento
        var paymentTable = document.getElementById('paymentTable');
        var paymentRows = Array.from(paymentTable.querySelectorAll('tbody tr:not(:first-child)'));
        paymentRows.forEach(function(row) {
            row.remove();
        });

        // Adicione uma nova linha para cada "formaPagamento" única, exceto "Total"
        var uniqueFormasPagamento = [...new Set(dados.pagamento.map(function(pagamento) {
            return pagamento.formaPagamento;
        }))].filter(function(formaPagamento) {
            return formaPagamento !== 'Total';
        });
        var addButton = document.getElementById('addPaymentBtn');
        for (var i = 0; i < uniqueFormasPagamento.length; i++) {
            addButton.click();
        }

        // Atualiza a lista de linhas de pagamento
        paymentRows = Array.from(paymentTable.querySelectorAll('tbody tr'));

        // Seleciona a forma de pagamento para cada linha
        for (var i = 1; i < paymentRows.length; i++) {
            var select = paymentRows[i].querySelector('select');
            if (select) {
                select.value = uniqueFormasPagamento[i - 1];
            }
        }

        // Agrupa os pagamentos por idSeguro
        var groupedPayments = dados.pagamento.reduce(function(acc, pagamento) {
            if (!acc[pagamento.idSeguro]) {
                acc[pagamento.idSeguro] = [];
            }
            acc[pagamento.idSeguro].push(pagamento);
            return acc;
        }, {});

        // Para cada grupo de pagamentos
        Object.values(groupedPayments).forEach(function (payments, groupIndex) {
            // Encontre a coluna correspondente ao id do seguro
            var selectSeguradoras = document.getElementById('selectSeguradoras');
            var option = Array.from(selectSeguradoras.options).find(function(option) {
                return option.value == payments[0].idSeguro;
            });
            var index = Array.from(paymentTable.querySelectorAll('th')).findIndex(function(th) {
                return th.textContent == option.textContent;
            }) - 1; // Subtrai 1 porque a primeira coluna é a forma de pagamento

            // Para cada pagamento no grupo
            payments.forEach(function (pagamento) {
                // Se a forma de pagamento for "Total", use a primeira linha
                var rowIndex = pagamento.formaPagamento === 'Total' ? 0 :
                    paymentRows.findIndex(function(row) {
                        var select = row.querySelector('select');
                        return select && select.value == pagamento.formaPagamento;
                    });

                // Se a coluna e a linha existirem, preencha o input com a descrição
                if (index >= 0 && rowIndex >= 0) {
                    var input = paymentRows[rowIndex].querySelectorAll('input[type="text"]')[index];
                    if (input) {
                        input.value = pagamento.descricao;
                    }
                }
            });
        });
    });
}

async function consultarModelo()
{
    var vIdModelo = $("#modelo").val();
    var arrayModelos = [];

    if (vIdModelo > 0)
    {
        await $.ajax({
            url: 'configuracao_modelo.php',
            cache: false,
            type: "POST",
            data: {
                tipoTransacao: 'consultarModelo',
                idModelo: vIdModelo,
            },
            dataType: 'json',
            success: function(dados){
                arrayModelos = dados;
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
            }
        });
    }

    return await Promise.resolve(arrayModelos);
}