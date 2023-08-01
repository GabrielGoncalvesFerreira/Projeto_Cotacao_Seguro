/**
 * A função obterDadosTabela é usada para extrair os dados de uma tabela HTML específica na página.
 * A tabela deve ter um ID de 'coverageInsuranceTable'. A primeira linha da tabela deve conter os cabeçalhos das colunas,
 * e as células subsequentes devem conter os dados da tabela.
 * 
 * @returns {Object} Um objeto contendo os dados da tabela. Cada chave do objeto é o título de uma cobertura, 
 * e o valor associado a cada chave é outro objeto, onde cada chave é o nome de uma seguradora e o valor associado 
 * é o texto padrão da linha correspondente.
 */
function obterDadosTabela() {
    // Obtenha a tabela pelo seu ID
    var tabela = document.getElementById('coverageInsuranceTable');

    // Inicialize um objeto vazio para armazenar os dados
    var dados = {};

    // Percorra todas as linhas da tabela, começando pela segunda linha (índice 1), pois a primeira linha contém os cabeçalhos
    for (var i = 1; i < tabela.rows.length; i++) {
        // Obtenha a linha atual
        var linhaAtual = tabela.rows[i];

        // Obtenha o título da cobertura (primeira célula da linha)
        var tituloCobertura = linhaAtual.cells[0].textContent;

        // Inicialize um objeto vazio para armazenar os dados da linha atual
        dados[tituloCobertura] = {};

        // Percorra todas as células da linha atual, começando pela segunda célula (índice 1), pois a primeira célula contém o título da cobertura
        for (var j = 1; j < linhaAtual.cells.length; j++) {
            // Obtenha a célula atual
            var celulaAtual = linhaAtual.cells[j];

            // Obtenha o nome da seguradora (cabeçalho da coluna)
            var nomeSeguradora = tabela.rows[0].cells[j].textContent;

            // Obtenha o texto padrão da linha (valor do campo de entrada na célula atual)
            var textoPadraoLinha = celulaAtual.querySelector('input').value;

            // Adicione os dados da célula atual ao objeto de dados da linha atual
            dados[tituloCobertura][nomeSeguradora] = textoPadraoLinha;
        }
    }

    // Retorne os dados
    return dados;
}




/**
 * Obtém o texto ou textos de um elemento HTML com base em seu ID.
 * Se o elemento for um select, retorna um array com o texto ou textos selecionados.
 * Se o elemento for um input do tipo radio, retorna o texto do botão de opção selecionado.
 * Para outros tipos de input, retorna o valor.
 * Se o elemento com o ID fornecido não for encontrado, retorna a string "Elemento não encontrado".
 * 
 * @param {string} id - O ID do elemento HTML.
 * @returns {(string|string[])} O texto ou textos do elemento HTML.
 */
function obterValorCampo(id) {
    const elemento = document.getElementById(id);

    // Verifica se o elemento existe
    if (!elemento) {
        return "Elemento não encontrado";
    }

    // Verifica se o elemento é um select
    if (elemento.tagName === 'SELECT') {
        const texts = Array.from(elemento.selectedOptions).map(option => option.textContent);
        return texts.length === 1 ? texts[0] : texts;
    } 
    // Verifica se o elemento é um input do tipo radio
    else if (elemento.type === 'radio') {
        const name = elemento.name;
        const selected = document.querySelector(`input[name="${name}"]:checked`);
        return selected ? selected.nextElementSibling.textContent : null;
    } 
    else {
        return elemento.value;
    }
}

function abrirPDF() {
    // Criar um array de bytes que representa o conteúdo do PDF
    var pdfBytes = gerarPDF();

    pdfBytes.open();
}

function createPdfmakeTable(tableId, paymentTableId) {
    let table = document.getElementById(tableId);
    let paymentTable = document.getElementById(paymentTableId);

    let jsTable = Array.from(table.rows).map(r => Array.from(r.cells).map(c => {
        if (c.children.length > 0 && c.children[0].tagName === "INPUT") {
            return c.children[0].value;
        } else {
            return c.innerText;
        }
    }));

    let paymentJsTable = Array.from(paymentTable.rows).slice(1).map((r, rowIndex) => Array.from(r.cells).map(c => {
        if (c.children.length > 0 && c.children[0].tagName === "INPUT") {
            console.log(c.children[0].tagName);
            return c.children[0].value;
        } else if (c.children.length > 0 && c.children[0].tagName === "SELECT") {
            return c.children[0].value;
        } else if (c.children.length > 0 && c.children[0].tagName === "BUTTON") {
            return null;
        } else {
            return c.innerText;
        }
    })).map(row => row.filter(cell => cell !== null));

    let availableWidth = 900;

    let tabela = {
        width: availableWidth,
        columns: [
            { width: '*', text: '' },
            {
                width: 'auto',
                table: {
                    heights: 20,
                    widths: [], 
                    headerRows: 1,
                    body: []
                },
                layout: {
                    fillColor: function (rowIndex, node, columnIndex) {
                        return ((rowIndex % 2 !== 0) && (columnIndex !== 0)) ? '#E9E9E9' : null;
                    }
                }
            },
            { width: '*', text: '' },
        ]
    };

    for (let i = 0; i < jsTable[0].length; i++) {
        if (i === 0){
            tabela.columns[1].table.widths.push(200); 
        }else{
            tabela.columns[1].table.widths.push(130);
        }  
    }

    for (let rowIndex = 0; rowIndex < jsTable.length; rowIndex++) {
        let row = jsTable[rowIndex];
        let pdfRow = [];
        for (let cellIndex = 0; cellIndex < row.length; cellIndex++) {
            let cell = row[cellIndex];
            let borderValue;
            let alignmentValue = 'center'; // centralize o texto para a primeira linha
            let italicsValue = cellIndex === 0 ? true : false; // texto em itálico para a primeira coluna
            if (rowIndex === 0) {
                borderValue = [false, false, false, true]; // nenhuma borda para a primeira linha
            } else {
                borderValue = [false, false, cellIndex < row.length - 1, false]; // apenas borda à direita, exceto na última célula
            }
            pdfRow.push({text: cell, border: borderValue, margin: [2, 2, 0, 0], lineHeight: 1, alignment: alignmentValue, italics: italicsValue});
        }
        tabela.columns[1].table.body.push(pdfRow);
    }

    for (let rowIndex = 0; rowIndex < paymentJsTable.length; rowIndex++) {
        let row = paymentJsTable[rowIndex];
        let pdfRow = [];

        for (let cellIndex = 0; cellIndex < row.length; cellIndex++) {
            let cell = row[cellIndex];
            let alignmentValue = 'center'; // centralize o texto para a primeira linha
            let borderValue = rowIndex === 0 ? [false, true, false, true] : [false, false, cellIndex < row.length - 1, false]; // todas as bordas para a linha de fundo amarelo
            let italicsValue = cellIndex === 0 ? true : false; // texto em itálico para a primeira coluna
            pdfRow.push({text: cell, border: borderValue, margin: [2, 2, 0, 0], lineHeight: 1, alignment: alignmentValue, italics: italicsValue});
        }

        if (rowIndex === 0) {
            pdfRow.forEach(cell => {
                cell.fillColor = '#177FA1';
                cell.color = '#FFF';
                cell.bold = true;
            });
        }

        tabela.columns[1].table.body.push(pdfRow);
    }

    return tabela;
}

function gerarPDF() {
    
    // Recuperar valor dos campos necessários para a geração do contrato
    var nomeCorretor    = obterValorCampo("nomeCorretor");
    var contatoCorretor = obterValorCampo("contatoCorretor");
    var tipoCotacao     = obterValorCampo("tipoCotacao");
    var tipoContrato    = obterValorCampo("contratoNovo");
    var nomeSegurado    = obterValorCampo("nomeSegurado");
    var estadoCivil     = obterValorCampo("estadoCivil");
    var cepPernoite     = obterValorCampo("cepPernoite");
    var utilizacao      = obterValorCampo("utilizacao");
    var tempoCondutor   = obterValorCampo("tempoCondutor");
    var nomeCondutor    = obterValorCampo("nomeCondutor");
    var placaCarro      = obterValorCampo("placaCarro");
    var modeloVeiculo   = obterValorCampo("modeloVeiculo");
    var anoModelo       = obterValorCampo("anoModelo");
    

    var docDefinition = {
        info: {
            title: 'Contrato'
          },

        pageSize: 'A4',
        pageOrientation: 'landscape',

        content: [
            // Logo e Dados do corretor
            {
                columns: [
                    // Coluna 1 - Logo
                    {
                        image: 'logo',
                        width: 180,
                        margin: [0, -10, 0, 0]
                    },

                    // Coluna 2 - Textos
                    [
                        // Linha 1
                        {
                            text: [
                                {text: 'Corretor: ', bold: true},
                                {text: nomeCorretor}
                            ], alignment: 'center', fontSize: 13,
                            margin: [0, -10, 0, 0]
                        },

                        // Linha 2
                        {
                            text: [
                                {text: 'Contato: ', bold: true},
                                {text: contatoCorretor}
                            ], alignment: 'center', fontSize: 13
                        }
                    ]
                ]
            },

            '\n\n',

            {
                table: {
                    widths: [500],
                    body: [
                        [
                        {
                            text: 'Cotação - Seguro ' + tipoCotacao,
                            alignment: 'left', 
                            fontSize: 15, 
                            color: '#167FA1',
                            characterSpacing: 1.2,
                            lineHeight: 1,
                            margin: [2, 2, 2, 2],
                            bold: true
                        }
                        ]
                    ]
                },
                layout: {
                    hLineWidth: function(i, node) {
                        return 1;
                    },
                    vLineWidth: function(i, node) {
                        if (i === 0){
                            return 4;
                        }
                        else if (i === node.table.widths.length){
                            return 1;
                        }
                    },
                    hLineColor: function(i, node) {
                        return '#167FA1';
                    },
                    vLineColor: function(i, node) {
                        return '#167FA1';
                    },
                }
            },

            '\n',

            {
                columns: [
                    {
                        image: 'checkIcon', 
                        width: 15
                    },
                    {
                        text: tipoContrato, 
                        fontSize: 13,
                        margin: [10, 0, 0, 0]  // Adiciona uma margem à esquerda para dar algum espaço entre a imagem e o texto
                    }
                ]
            },

            '\n',
                       
            {
                columns: [
                    { width: '*', text: '' },
                    {
                        width: 'auto',
                        table: {
                            heights: 20,
                            widths: [140, 380],  // Coloque aqui a quantidade de colunas da sua tabela
                            headerRows: 1,
                            body: [
                                [
                                    {text: 'Dados do segurado', colSpan: 2, fontSize: 13, border: [false, false, false, true], alignment: 'center'}, 
                                    {}
                                ],
                                [
                                    {text: 'Nome', border: [false, false, true, false], margin: [2, 2, 0, 0], lineHeight: 1}, 
                                    {text: nomeSegurado, border: [false, false, false, false], margin: [2, 2, 0, 0], lineHeight: 1},
                                ],
                                [
                                    {text: 'Estado civil', border: [false, false, true, false], margin: [2, 2, 0, 0], lineHeight: 1}, 
                                    {text: estadoCivil, border: [false, false, false, false], margin: [2, 2, 0, 0], lineHeight: 1},
                                ],
                                [
                                    {text: 'CEP pernoite', border: [false, false, true, false], margin: [2, 2, 0, 0], lineHeight: 1}, 
                                    {text: cepPernoite, border: [false, false, false, false], margin: [2, 2, 0, 0], lineHeight: 1},
                                ],
                                [
                                    {text: 'Utilização', border: [false, false, true, false], margin: [2, 2, 0, 0], lineHeight: 1}, 
                                    {text: utilizacao, border: [false, false, false, false], margin: [2, 2, 0, 0], lineHeight: 1},
                                ]
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex) {
                                return ((rowIndex % 2 !== 0) && (columnIndex !== 0)) ? '#E9E9E9' : null;
                            }
                        }
                    },
                    { width: '*', text: '' },
                ]
            },

            '\n\n',

            {
                columns: [
                    { width: '*', text: '' },
                    {
                        width: 'auto',
                        table: {
                            heights: 20,
                            widths: [140, 380],  // Coloque aqui a quantidade de colunas da sua tabela
                            headerRows: 1,
                            body: [
                                [
                                    {text: 'Dados do condutor/veículo', colSpan: 2, fontSize: 13, border: [false, false, false, true], alignment: 'center'}, 
                                    {}
                                ],
                                [
                                    {text: 'Condutor', border: [false, false, true, false], margin: [2, 2, 0, 0], lineHeight: 1}, 
                                    {text: nomeCondutor, border: [false, false, false, false], margin: [2, 2, 0, 0], lineHeight: 1},
                                ],
                                [
                                    {text: 'Tempo de condução', border: [false, false, true, false], margin: [2, 2, 0, 0], lineHeight: 1}, 
                                    {text: tempoCondutor, border: [false, false, false, false], margin: [2, 2, 0, 0], lineHeight: 1},
                                ],
                                [
                                    {text: 'Placa', border: [false, false, true, false], margin: [2, 2, 0, 0], lineHeight: 1}, 
                                    {text: placaCarro, border: [false, false, false, false], margin: [2, 2, 0, 0], lineHeight: 1},
                                ],
                                [
                                    {text: 'Modelo do veículo', border: [false, false, true, false], margin: [2, 2, 0, 0], lineHeight: 1}, 
                                    {text: modeloVeiculo, border: [false, false, false, false], margin: [2, 2, 0, 0], lineHeight: 1},
                                ],
                                [
                                    {text: 'Ano/Modelo', border: [false, false, true, false], margin: [2, 2, 0, 0], lineHeight: 1}, 
                                    {text: anoModelo, border: [false, false, false, false], margin: [2, 2, 0, 0], lineHeight: 1},
                                ]
                            ]
                        },
                        layout: {
                            fillColor: function (rowIndex, node, columnIndex) {
                                return ((rowIndex % 2 !== 0 ) && (columnIndex !== 0)) ? '#E9E9E9' : null;
                            }
                        }
                    },
                    { width: '*', text: '' },
                ], pageBreak: 'after'
            },

            // ========================== PÁGINA 2 =======================================

            {
                table: {
                    widths: [500],
                    body: [
                        [
                        {
                            text: 'Coberturas',
                            alignment: 'left', 
                            fontSize: 15, 
                            color: '#167FA1',
                            characterSpacing: 1.2,
                            lineHeight: 1,
                            margin: [2, 2, 2, 2],
                            bold: true
                        }
                        ]
                    ]
                },
                layout: {
                    hLineWidth: function(i, node) {
                        return 1;
                    },
                    vLineWidth: function(i, node) {
                        if (i === 0){
                            return 4;
                        }
                        else if (i === node.table.widths.length){
                            return 1;
                        }
                    },
                    hLineColor: function(i, node) {
                        return '#167FA1';
                    },
                    vLineColor: function(i, node) {
                        return '#167FA1';
                    },
                }
            },

            '\n\n',
            
            createPdfmakeTable('coverageInsuranceTable', 'paymentTable')
            

        ],
        images: {
            logo: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQQAAAA9CAYAAABLChfbAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTM4IDc5LjE1OTgyNCwgMjAxNi8wOS8xNC0wMTowOTowMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTcgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjI5QTk5NDhBNDM1QzExRTc5MUNBODQwNEFEMTE0MTI2IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjI5QTk5NDhCNDM1QzExRTc5MUNBODQwNEFEMTE0MTI2Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MjlBOTk0ODg0MzVDMTFFNzkxQ0E4NDA0QUQxMTQxMjYiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MjlBOTk0ODk0MzVDMTFFNzkxQ0E4NDA0QUQxMTQxMjYiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7qlpK7AABQWUlEQVR42ux9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',
            checkIcon: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAAlQAAAJUBq/jvCwAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAHnSURBVEiJ1ZOxaxNRHMc/v3e5ay6VJLRommpSrogWRLBmqOiii6IuIjgJTuJQ1Fahrc4utoW2gxz+B4I6aJ1EKoKTqwiKYEFQYhVC2ysarXfPwV4JIY1pcg79wlve430+Xx6/B1s9RsQ8BzgNlAAvYjbHQb4DGmQJOBwtXKSsEim/89yEFivhI2oFOBQhPO3vvPlSO66ns9efajHt38BbFQF8VtkpMzv0RFm5AwCYO3ZjtHcEQKe0AD+ByONquL/8leL0Sb268F6AS600/6HiSb977IV2XE87rqfz4/PazPYFgAbGqi+dB+4BFxtoXlaJ1PqbO66n87c/aDOzJ4QPV18aXTsI11Sd5mVl12jetXHzIUBbu/YHuVtvdLx3IJRM1m6e3lRzgFKsI/+rZ/KjdlxP90wVKyUTrcIV8Nlf/mL8/PT670Z8G5nLj4j3DgCMAPcbmJZrwEwtgQAHEfVcYmYyM/hQ7L1HAQjKKyzcOUN5/hUqkQ6agYcCgAIic2JYyczgA7H7jq1Llp5N0144i9W9b9PwSgFAP6LmxDDTlZLK+N43ijOn9GrxnQA3gPF68GpBXUkz8I3Sj6iSxNqCrquzDf3QZlJAZFFibcH2C3f/OYotSNTiGjiIGh4mB1wBjvwP+NbIHzo9+ggMMaQZAAAAAElFTkSuQmCC'
        },
        defaultStyle: {
            fontSize: 12,
            bold: false,
            lineHeight: 1.5,
          },
          fonts: {
            Roboto: {
                normal: 'Roboto-Regulas.ttf'
            }
          }
    };

    // Gerar o PDF e retornar os bytes
    var pdfMake = window.pdfMake;
    var pdfBytes = pdfMake.createPdf(docDefinition);

    return pdfBytes;
}