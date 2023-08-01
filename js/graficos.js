Chart.register(ChartDataLabels);

/**
 * Função para buscar um registro de agendamento.
 */
async function buscarDadosAgendamento() {

    var array = [];
    /**
     * Realiza uma requisição assíncrona para buscar o registro de agendamentos.
     * @type {$.ajax}
     */
    await $.ajax({
        url: 'configuracao_grafico.php',
        cache: false,
        type: "POST",
        dataType: "JSON", 
        data: {
            tipoTransacao: 'carregarDadosAgendamento',
        },
        success: function(dados){
            /**
             * Preenche os campos com os dados retornados na resposta.
             */
            array = dados;
        
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
        }
    });

    return await Promise.resolve(array);
}

function buscarDadosIndicadores() {

    /**
     * Realiza uma requisição assíncrona para buscar o registro de agendamentos.
     * @type {$.ajax}
     */
    $.ajax({
        url: 'configuracao_grafico.php',
        cache: false,
        type: "POST",
        dataType: "JSON", 
        data: {
            tipoTransacao: 'carregarDadosIndicadores',
        },
        success: function(dados){
            /**
             * Preenche os campos com os dados retornados na resposta.
             */
            $("#totalClientes").text(dados.totalClientes['total']);
            $("#totalAgendamento").text(dados.totalAgendamento['total']);
            $("#totalUsuario").text(dados.totalUsuarios['total']);
        
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
        }
    });
}


// Função para criar uma matriz com campos de data, valor e status
function criarDatasArray(vstatus) {
    var arr = [];
    for (var i = 0; i < 31; i++) {
        arr.push({date: null, value: 0, status: vstatus});
    }
    return arr;
}

// Função para preencher a matriz com datas dos últimos -15 dias e +15 dias e valores
function analisaDatas(array) {
    var arr = array;
    var today = new Date();

    for (var i = 0; i < arr.length; i++) 
    {
        var date = new Date();
        date.setDate(today.getDate() - 15 + i);
        arr[i].date = date.getDate() + '-' + (date.getMonth() + 1);
        arr[i].value = 0; // Gera um valor aleatório entre 0 e 99
    }

    return arr;
}

function estruturarArrayAgendamento()
{
    buscarDadosAgendamento().then((dadosArrayAgendamento) => {
        var dadosAgendamento = dadosArrayAgendamento
        var dadosGraficosA = criarDatasArray("A");
        var dadosGraficosP = criarDatasArray("P");
        var dadosGraficosC = criarDatasArray("C");
        var dadosGraficosO = criarDatasArray("O");
        
        dadosGraficosA = analisaDatas(dadosGraficosA);
        dadosGraficosP = analisaDatas(dadosGraficosP);
        dadosGraficosC = analisaDatas(dadosGraficosC);
        dadosGraficosO = analisaDatas(dadosGraficosO);

        for (var indiceAgenda = 0; indiceAgenda < dadosAgendamento.length; indiceAgenda++) {
            var dataAgenda = new Date(dadosAgendamento[indiceAgenda].data);
            dataAgenda = (dataAgenda.getDate() + 1) + '-' + (dataAgenda.getMonth() + 1);

            for (var indiceGrafico = 0; indiceGrafico < dadosGraficosA.length; indiceGrafico++) {
                
                if (dataAgenda == dadosGraficosA[indiceGrafico].date)
                {
                    if (dadosAgendamento[indiceAgenda].status == "A")
                    {
                        dadosGraficosA[indiceGrafico].value += dadosAgendamento[indiceAgenda].total; 
                    }
                    else if (dadosAgendamento[indiceAgenda].status == "P")
                    {
                        dadosGraficosP[indiceGrafico].value += dadosAgendamento[indiceAgenda].total; 
                    }
                    else if (dadosAgendamento[indiceAgenda].status == "C")
                    {
                        dadosGraficosC[indiceGrafico].value += dadosAgendamento[indiceAgenda].total; 
                    }
                    else if (dadosAgendamento[indiceAgenda].status == "O")
                    {
                        dadosGraficosO[indiceGrafico].value += dadosAgendamento[indiceAgenda].total; 
                    }
                }
            }
        }

        criarGraficoLinha(dadosGraficosA, dadosGraficosP, dadosGraficosC, dadosGraficosO)
    });

}

function transformaMatrizArray(matriz, campoArray)
{
    var arrayDados = [];

    matriz.forEach(function(item, index) {
        arrayDados.push(item[campoArray])
    });

    return arrayDados;
}

function somaValoresArray(array) {
    var total = 0;
    array.forEach(function(valor) {
        total += Number(valor);
    });
    return total;
}

function criarGraficoLinha(arrayAgendados, arrayPendente, arrayCancelado, arrayConcluido)
{
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: transformaMatrizArray(arrayAgendados, "date"),
            datasets: [{
                label: 'Pendentes',
                data: transformaMatrizArray(arrayAgendados, "value"),
                borderColor: 'rgba(255, 99, 132, 1)',
                fill: false
            }, {
                label: 'Concluídos',
                data: transformaMatrizArray(arrayPendente, "value"),
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false
            }, {
                label: 'Prorrogados',
                data: transformaMatrizArray(arrayCancelado, "value"),
                borderColor: 'rgba(255, 159, 64, 1)',
                fill: false
            }, {
                label: 'Cancelados',
                data: transformaMatrizArray(arrayConcluido, "value"),
                borderColor: 'rgba(153, 102, 255, 1)',
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                datalabels: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    var ctxRosca = document.getElementById('myChartRosca').getContext('2d');
    var dataValues = [
        somaValoresArray(transformaMatrizArray(arrayAgendados, "value")),
        somaValoresArray(transformaMatrizArray(arrayPendente, "value")),
        somaValoresArray(transformaMatrizArray(arrayCancelado, "value")),
        somaValoresArray(transformaMatrizArray(arrayConcluido, "value"))
    ];
    
    // Remove NaN values
    dataValues = dataValues.map(value => isNaN(value) ? 0 : value);
    
    var myChartRosca = new Chart(ctxRosca, {
        type: 'doughnut',
        data: {
            labels: ['Pendentes', 'Concluídos', 'Prorrogados', 'Cancelados'],
            datasets: [{
                data: dataValues,
                backgroundColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(153, 102, 255, 1)'
                ]
            }]
        },
        options: {
            responsive: true,
            cutout: '80%',
            plugins: {
                datalabels: {
                    color: '#fff',
                    formatter: (value, ctx) => {
                        let datasets = ctx.chart.data.datasets;
                        if (datasets.indexOf(ctx.dataset) === datasets.length - 1) {
                            let sum = datasets[0].data.reduce((a, b) => a + b, 0);
                            let percentage = Math.round((value / sum) * 100) + '%';
                            return percentage;
                        } else {
                            return null;
                        }
                    }
                }
            }
        }
    });
    
    
    
    
}

$(document).ready(function() {
    estruturarArrayAgendamento();
    buscarDadosIndicadores();
});