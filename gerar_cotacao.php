<?php
    require_once 'header.php';
?>
<script src="extensao/pdfmake/build/pdfmake.min.js"></script>
<script src="extensao/pdfmake/build/vfs_fonts.js"></script>
<script src="js/gerarPdf.js"></script>
<script type="text/javascript" src="js/configuracaoModelo.js"></script>

<br>

<div class="card">
    <div class="card-body">
        <h5><i class="bi bi-envelope"></i> Gerar cotação</h5>
    </div>   
</div>

<br>

<div class="card">
    <div class="card-body">
        <h5> Modelo</h5>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="btn-group" role="group" aria-label="Tipo Modelo">
                    <input type="radio" class="btn-check" name="tipoModelo" id="novoModelo" autocomplete="off" onclick="tipoModelo('novo');" value="N" checked>
                    <label class="btn btn-outline-primary" for="novoModelo"><i class="bi bi-folder-plus"></i> Novo Modelo</label>

                    <input type="radio" class="btn-check" name="tipoModelo" id="carregarModelo" onclick="tipoModelo('existente');" value="E" autocomplete="off">
                    <label class="btn btn-outline-primary" for="carregarModelo"><i class="bi bi-folder"></i> Carregar Modelo</label>
                </div>
            </div>
        </div>
        <br>
        <div class="row" id="dadoExistente">
            <div class="col-md-4">
                <select class="form-select" id="modelo" onchange="carregarModelo();">
                    <option selected>--- Novo Modelo ---</option>
                </select>
            </div>
        </div>

        <div class="row" id="dadoNovo">
            <div class="col-md-4">
                <label for="inputModelo" class="form-label">Nome Modelo</label>
                <input type="text" class="form-control" id="inputModelo">
            </div>
        </div>
    </div>
</div>

<br>

<!-- Início do card para preenchimento dos campos -->
<div class="card">
    <div class="card-body">
        <div class="row">
            <!-- Título do card -->
            <h4 class="mb-3">Dados do contrato</h4>

            <!-- Seletor de Corretor -->
            <div class="col-md-6">
                <label for="nomeCorretor" class="form-label"><h5>Corretor</h5></label>
                <select class="form-select" aria-label="Select corretor" id="nomeCorretor">
                    <option selected>--- Selecione o corretor ---</option>
                </select>
            </div>
            <!-- Fim do seletor de Corretor -->

            <!-- Contato do corretor -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="contatoCorretor" class="form-label"><h5>Contato</h5></label>
                    <input type="tel" class="form-control" id="contatoCorretor" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" value=''>
                </div>
            </div>
            <!-- Fim do contato do corretor -->

            <div class="w-100 my-3"></div>

            <!-- Seletor de Tipo de cotação -->
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="tipoCotacao" class="form-label"><h5>Tipo de cotação</h5></label>
                    <select class="form-select" aria-label="Select tipo de cotação" id="tipoCotacao">
                        <option selected>--- Selecione o tipo de cotação ---</option>
                    </select>
                </div>
            </div>
            <!-- Fim do seletor de Tipo de cotação -->

            <!-- Seleção do tipo de contrato -->
            <div class="col-md-6">
                <div class="mb-3">
                    <h5 class="form-label">Tipo de contrato</h5>
                    <div class="btn-group" role="group" aria-label="Tipo de contrato">
                        <input type="radio" class="btn-check" name="tipoContrato" id="contratoNovo" autocomplete="off" checked>
                        <label class="btn btn-outline-primary" for="contratoNovo">Novo contrato</label>

                        <input type="radio" class="btn-check" name="tipoContrato" id="contratoRenovacao" autocomplete="off">
                        <label class="btn btn-outline-primary" for="contratoRenovacao">Renovação</label>
                    </div>
                </div>
            </div>
            <!-- Fim da seleção do tipo de contrato -->

            <div class="w-100 my-3"></div>

            <!-- Selecionar Coberturas e Seguradoras -->
            <div class="row">
                <!-- Coberturas -->
                <div class="col-md-6 mb-3">
                    <label class="form-label"><h5>Coberturas</h5></label>
                    <select class="form-select" id="selectCoberturas" multiple>
                    </select>
                </div>

                <!-- Seguradoras -->
                <div class="col-md-6 mb-3">
                    <label class="form-label"><h5>Seguradoras</h5></label>
                    <select class="form-select" id="selectSeguradoras" multiple onchange="">
                    </select>
                </div>
            </div>
            

            <!-- Tabela de Coberturas e Seguradoras -->
            <div class="row justify-content-center">
                <div class="table-responsive">
                    <div class="col-12 mx-auto">
                        <table id="coverageInsuranceTable" class="table table-striped table-hover" style="min-width: 800px; overflow-x: auto; width: 100%; display: block;">

                        </table>
                    </div>
                </div>
            </div>

            <div class="w-100 my-3"></div>

            <h4 class="mb-3">Opções de pagamento</h4>
            <div class="row justify-content-center">
                <div class="table-responsive">
                    <div class="col-12 mx-auto">
                        <table id="paymentTable" class="table table-striped table-hover" style="min-width: 800px; overflow-x: auto; width: 100%; display: block;">

                        </table>
                    </div>
                </div>
            </div>


            <div class="col-md-4"><button id="addPaymentBtn" class="btn btn-primary mt-3">Adicionar forma de pagamento</button></div>

            <div class="row mb-5"></div>

            

            <!-- Modal -->
            <div class="row justify-content-center">
                <div class="modal fade" id="addTableModal" tabindex="-1" aria-labelledby="addTableModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTableModalLabel">Adicionar nova tabela</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="tableName" class="form-control" placeholder="Nome da tabela">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" id="adicionarTabela">Adicionar tabela</button>
                    </div>
                    </div>
                </div>
                </div>

                <!-- Aqui serão adicionados os cards com as tabelas -->
                <div class="row justify-content-center">
                    <div class="table-responsive">
                        <div class="col-12 mx-auto">
                            <table id="tablesContainer" class="table table-striped" style="min-width: 800px; overflow-x: auto; width: 100%; display: block;">

                            </table>
                        </div>
                    </div>
                </div>

                <br>
                <!-- Botão para abrir o modal -->
                <button type="button" class="btn btn-primary col-6" data-bs-toggle="modal" data-bs-target="#addTableModal">
                Adicionar tabela
                </button>
            </div>
            

        </div>
    </div>
</div>
<!-- Fim do card para preenchimento dos campos -->

<!-- Botão para abrir PDF -->
<div class="row mb-3 mt-3">
    <div class="col">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary" id="gerarModelo" onclick="abrirPDF()">
                    <i class="bi bi-filetype-pdf"></i> Gerar PDF
                </button>
                <button type="button" class="btn btn-success" id="salvarModelo" onclick="salvarModelo()">
                    <i class="bi bi-folder-plus"></i> Salvar Modelo
                </button>
                <button type="button" class="btn btn-danger" id="deletarModelo" onclick="deletarModelo();">
                    <i class="bi bi-folder-x"></i> Deletar Modelo
                </button>
            </div>    
        </div>
    </div>
</div>

<script type="text/javascript" src="js/configuracaoGerarCotacao.js"></script>
<script>
    $(document).ready(function () {
        carregarCorretores();
        carregarTiposCotacao();
        consultarSeguradoras();
        
        
        consultarServicos().then(descricaoCoberturas => {
            // Função para gerar a tabela com base nas seleções atuais
            function generateTable() {
                const selectCoberturas = $("#selectCoberturas");
                const selectSeguradoras = $("#selectSeguradoras");
                const tableContainer = $("#coverageInsuranceTable");

                let table = "<table class='table'><thead><tr><th>Cobertura</th>";

                // Adiciona uma coluna para cada seguradora selecionada
                selectSeguradoras.find("option").each(function () {
                    if ($(this).is(":selected")) {
                        table += `<th>${$(this).text()}</th>`;
                    }
                });

                table += "</tr></thead><tbody>";

                // Adiciona uma linha para cada cobertura selecionada
                selectCoberturas.find("option").each(function () {
                    if ($(this).is(":selected")) {
                        const coverageText = $(this).text();
                        table += "<tr><td>" + coverageText + "</td>";

                        // Adiciona uma célula para cada seguradora selecionada
                        selectSeguradoras.find("option").each(function () {
                            if ($(this).is(":selected")) {
                                // Use a descrição padrão da cobertura se ela existir, caso contrário, use uma string vazia
                                const defaultValue = descricaoCoberturas[coverageText] || "";
                                table += `<td><input type="text" class="form-control" value="${defaultValue}"></td>`;
                            }
                        });

                        table += "</tr>";
                    }
                });

                table += "</tbody></table>";

                // Insere a tabela no elemento com ID "coverageInsuranceTable"
                tableContainer.html(table);

                updatePaymentTable();
            }

            // Função para atualizar a tabela de forma de pagamento
            function updatePaymentTable() {
                const selectSeguradoras = $("#selectSeguradoras");
                const table = $("#paymentTable");
                const headerRow = table.find("thead tr");
                const bodyRows = table.find("tbody tr");

                // Armazena os valores atuais dos campos de entrada
                let values = [];
                bodyRows.each(function(rowIndex) {
                    values[rowIndex] = {};
                    $(this).find("td").each(function(colIndex) {
                        if (colIndex > 0) { // Ignora a primeira coluna
                            const seguradora = headerRow.find("th").eq(colIndex).text();
                            values[rowIndex][seguradora] = $(this).find("input").val();
                        }
                    });
                });

                // Remove todas as colunas, exceto a primeira
                headerRow.find("th:gt(0)").remove();
                bodyRows.each(function() {
                    $(this).find("td:gt(0)").remove();
                });

                // Adiciona uma coluna para cada seguradora selecionada
                selectSeguradoras.find("option").each(function () {
                    if ($(this).is(":selected")) {
                        const seguradora = $(this).text();

                        // Adiciona a coluna no cabeçalho
                        headerRow.append(`<th>${seguradora}</th>`);

                        // Adiciona a célula na linha do corpo
                        bodyRows.each(function(rowIndex) {
                            // Se a célula é a primeira, adicione um select
                            if ($(this).children().first().is("select")) {
                                $(this).append('<td><select class="form-select">' + getPaymentOptions() + '</select></td>');
                            } else {
                                $(this).append('<td><input type="text" class="form-control"></td>');
                            }

                            // Restaura o valor do campo de entrada, se houver
                            if (values[rowIndex] && values[rowIndex][seguradora]) {
                                $(this).find("input").last().val(values[rowIndex][seguradora]);
                            }
                        });
                    }
                });

                // Adiciona o botão de remoção ao final de todas as linhas que contêm um campo select
                bodyRows.each(function() {
                    if ($(this).find("select").length > 0) {
                        $(this).append('<td><button class="btn btn-danger deleteRowBtn"><i class="bi bi-trash"></i></button></td>');
                    }
                });
            }

            // Atualiza a tabela inicialmente
            generatePaymentTable();

            // Atualiza a tabela sempre que a seleção de seguradoras muda
            $("#selectSeguradoras").change(updatePaymentTable);

            // Função para gerar a tabela de forma de pagamento
            function generatePaymentTable() {
                const selectSeguradoras = $("#selectSeguradoras");
                const tableContainer = $("#paymentTable");

                let table = "<table class='table'><thead><tr><th>Forma pagamento</th>";

                // Adiciona uma coluna para cada seguradora selecionada
                selectSeguradoras.find("option").each(function () {
                    if ($(this).is(":selected")) {
                        table += `<th>${$(this).text()}</th>`;
                    }
                });

                table += "</tr></thead><tbody>";

                // Adiciona uma linha com "Preço total" e campos de input para cada seguradora
                table += "<tr><td>Preço total</td>";
                selectSeguradoras.find("option").each(function () {
                    if ($(this).is(":selected")) {
                        table += '<td><input type="text" class="form-control"></td>';
                    }
                });

                table += "</tr>";

                table += "</tbody></table>";

                // Insere a tabela no elemento com ID "paymentTable"
                tableContainer.html(table);
            }

            // Gera a tabela inicialmente
            generateTable();

            $("#selectSeguradoras").on("change", function() {
            var selectedOption = $(this).val();

            if (selectedOption) {
                generateTable();
            }
            });

            // Habilita a seleção/deseleção de opções de cobertura com um único clique
            $("#selectCoberturas").on("mousedown", "option", function (e) {
                e.preventDefault();
                $(this).prop('selected', !$(this).prop('selected'));
                generateTable();
                return false;
            });

            // Habilita a seleção/deseleção de opções de seguradora com um único clique
            $("#selectSeguradoras").on("mousedown", "option", function (e) {
                e.preventDefault();
                $(this).prop('selected', !$(this).prop('selected'));
                generateTable();
                updatePaymentTable();
                return false;
            });
        }).catch(error => {
            console.error("Erro ao consultar serviços: ", error);
        });

        // Função para criar a tabela de dados do pdf
        let tableCounter = 0;
        $("#adicionarTabela").click(function() {
            const tableName = $("#tableName").val();
            if (tableName) {
                tableCounter++;
                const tableId = "table" + tableCounter;
                const card = $(`
                    <div class="card mt-3">
                        <div class="card-header">${tableName}</div>
                        <div class="card-body">
                            <table class="table" id="${tableId}">
                                <thead>
                                    <tr>
                                        <th>Campo</th>
                                        <th>Valor</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <button type="button" class="btn btn-outline-primary addRowBtn">Adicionar linha</button>
                        </div>
                    </div>
                `);
                card.find(".card-header").click(function() {
                    const header = $(this);
                    const currentName = header.text();
                    header.html(`<input type="text" class="form-control" value="${currentName}">`);
                    header.find("input").focus().blur(function() {
                        const newName = $(this).val();
                        header.text(newName);
                    });
                });
                card.find(".addRowBtn").click(function() {
                    const row = $(`
                        <tr>
                            <td><input type="text" class="form-control" value=""></td>
                            <td><input type="text" class="form-control" value=""></td>
                            <td><button class="btn btn-danger deleteRowBtn"><i class="bi bi-trash"></i></button></td>
                        </tr>
                    `);
                    row.find("input").on("input", function() {
                        $(this).attr("value", $(this).val());
                    });
                    row.find(".deleteRowBtn").click(function() {
                        row.remove();
                    });
                    card.find("tbody").append(row);
                });
                $("#tablesContainer").append(card);
                $("#tableName").val("");
                $("#addTableModal").modal("hide");
            } else {
                alert("Por favor, insira um nome para a tabela.");
            }
        });




    });



    // Função para adicionar uma nova linha na tabela de pagamento
    function addPaymentRow() {
        const selectSeguradoras = $("#selectSeguradoras");
        const tableBody = $("#paymentTable tbody");

        let newRow = $("<tr></tr>");

        // Adiciona a primeira célula com o seletor de forma de pagamento
        newRow.append(`<td><select class="form-select">${getPaymentOptions()}</select></td>`);

        // Adiciona uma célula para cada seguradora selecionada
        selectSeguradoras.find("option").each(function () {
            if ($(this).is(":selected")) {
                newRow.append('<td><input type="text" class="form-control"></td>');
            }
        });

        // Adiciona a célula com o botão de exclusão
        newRow.append('<td><button class="btn btn-danger deleteRowBtn"><i class="bi bi-trash"></i></button></td>');

        // Adiciona a nova linha ao corpo da tabela
        tableBody.append(newRow);
    }

    // Adiciona o evento de clique ao botão "Adicionar forma de pagamento"
    $("#addPaymentBtn").click(addPaymentRow);

    // Adiciona o evento de clique aos botões de exclusão de linha
    $("#paymentTable").on("click", ".deleteRowBtn", function() {
        $(this).closest("tr").remove();
    });

    // Função para obter as opções de forma de pagamento
    function getPaymentOptions() {
        const paymentMethods = ["Débito", "Crédito", "Pix", "Débito em conta", "Boleto"];
        let options = "";

        paymentMethods.forEach(method => {
            options += `<option value="${method}">${method}</option>`;
        });

        return options;
    }

</script>

<?php
    require_once 'footer.php';
?>
