<?php

/**
 * Conexão com o banco de dados
 */
require_once "Connection.php";

$conexao = new Connection();
$conectar = $conexao->conectar();

/**
 * Inicia a sessão
 */
session_start();

/**
 * Variáveis globais
 */
$tipoTransacao = filter_input(INPUT_POST, 'tipoTransacao', FILTER_SANITIZE_SPECIAL_CHARS);


if ($tipoTransacao == "carregarSelectModelo")
{
    carregarSelectModelo();
}
else if ($tipoTransacao == "inserirModelo")
{
    $idModelo = $_POST['idModelo'];
    $nomeModelo = $_POST['nomeModelo'];
    $arrayIdSeguros = $_POST['idSeguros'];
    $arrayIdCoberturas = $_POST['idCoberturas'];
    $contato = $_POST['contato'];
    $idTipoCotacao = $_POST['idTipoCotacao'];
    $tipoContrato = $_POST['tipoContrato'];
    $arrayValoresCobertura = $_POST['valoresCobertura'];
    $idusuario = $_POST['idCorretor'];
    $arrayFormaPagamento = $_POST['formaPagamento'];
    if (isset($_POST['dadosTabela'])) {
        $arrayTabela = $_POST['dadosTabela'];
    } else {
        $arrayTabela = array(); // ou qualquer valor padrão que você queira usar quando 'dadosTabela' não estiver definido
    }
    
    

    if (empty($nomeModelo))
    {
        echo "Defina um nome para o modelo antes de salvar.";
    }
    else if (empty($contato) || empty($idTipoCotacao) || empty($tipoContrato) || empty($idusuario))
    {
        echo "Preencha os dados do contrato para salvar o modelo.";
    }
    else if (empty($arrayIdSeguros) || empty($arrayIdCoberturas))
    {
        echo "É necessário selecionar Coberturas e Seguradoras para salvar os modelos.";
    }
    else
    {
        inserirDadosModelos($idModelo, $nomeModelo, $idusuario, $contato, $idTipoCotacao, $tipoContrato, $arrayIdSeguros, 
        $arrayIdCoberturas, $arrayValoresCobertura, $arrayFormaPagamento, $arrayTabela); 
    }
}
else if ($tipoTransacao == "deletarModelo")
{
    $idModelo = isset($_POST['idModelo']) && is_numeric($_POST['idModelo']) ? filter_input(INPUT_POST, 'idModelo', FILTER_SANITIZE_NUMBER_INT) : NULL;
    deletarModelos($idModelo, true);
}
else if ($tipoTransacao == "consultarModelo")
{
    //$idModelo = filter_input(INPUT_POST, 'idModelo', FILTER_SANITIZE_NUMBER_INT);
    $idModelo = isset($_POST['idModelo']) && is_numeric($_POST['idModelo']) ? filter_input(INPUT_POST, 'idModelo', FILTER_SANITIZE_NUMBER_INT) : NULL;

    buscarModelos($idModelo);
}
else
{
    echo 'Erro Critico';
}

/**
 * Função para inserir um cabeçalho de modelo no banco de dados.
 *
 * @param string $vnome Nome do modelo.
 * @param string $vidusuario ID do usuário.
 * @param string $vcontato Contato.
 * @param string $vidTipoCotacao ID do tipo de cotação.
 * @param string $tipoContrato Tipo de contrato.
 * @return string ID do modelo inserido ou existente.
 */
function inserirCabecalhoModelo($vnome, $vidusuario, $vcontato, $vidTipoCotacao, $tipoContrato)
{
    // Acessar a conexão do banco de dados global
    global $conectar;

    // Preparar a consulta SQL para verificar se o registro já existe
    $verificar = $conectar->prepare("SELECT id FROM modelo WHERE nome = :nome AND idusuario = :idusuario");

    $vnomeEscaped = htmlspecialchars($vnome);
    $vidusuarioEscaped = htmlspecialchars($vidusuario);
    $verificar->bindParam(':nome', $vnomeEscaped, PDO::PARAM_STR);
    $verificar->bindParam(':idusuario', $vidusuarioEscaped, PDO::PARAM_STR);

    // Executar a consulta SQL
    $verificar->execute();

    // Se o registro não existir, inserir um novo
    if ($verificar->rowCount() == 0) 
    {
        // Preparar a consulta SQL para inserir o novo modelo
        $scriptModelo = "INSERT INTO modelo(nome, idusuario, contato, idTipoCotacao, tipoContrato)
                        VALUES (:nome, :idusuario, :contato, :idTipoCotacao, :tipoContrato)";

        $executarInsert = $conectar->prepare($scriptModelo);

        // Vincular os parâmetros à consulta SQL e escapar de caracteres especiais para prevenir injeção SQL
        $vcontatoEscaped = htmlspecialchars($vcontato);
        $vidTipoCotacaoEscaped = htmlspecialchars($vidTipoCotacao);
        $tipoContratoEscaped = htmlspecialchars($tipoContrato);
        $executarInsert->bindParam(':nome', $vnomeEscaped, PDO::PARAM_STR);
        $executarInsert->bindParam(':idusuario', $vidusuarioEscaped, PDO::PARAM_STR);
        $executarInsert->bindParam(':contato', $vcontatoEscaped, PDO::PARAM_STR);
        $executarInsert->bindParam(':idTipoCotacao', $vidTipoCotacaoEscaped, PDO::PARAM_STR);
        $executarInsert->bindParam(':tipoContrato', $tipoContratoEscaped, PDO::PARAM_STR);

        // Executar a consulta SQL
        $executarInsert->execute();

        // Pegar o ID do modelo inserido
        $idModelo = $conectar->lastInsertId();

        if ($idModelo === false) {
            throw new Exception('Erro ao inserir novo modelo');
        }
    }
    else
    {
        // Se o modelo já existir, pegar o ID existente
        $resultado = $verificar->fetch(PDO::FETCH_ASSOC);
        $idModelo = $resultado['id'];

        if ($idModelo === false) {
            throw new Exception('Erro ao obter ID do modelo existente');
        }
    }

    // Retornar o ID do modelo
    return $idModelo;
}



/**
 * Função para atualizar um cabeçalho de modelo no banco de dados.
 *
 * @param string $vid ID do modelo.
 * @param string $vnome Nome do modelo.
 * @param string $vidusuario ID do usuário.
 * @param string $vcontato Contato.
 * @param string $vidTipoCotacao ID do tipo de cotação.
 * @param string $tipoContrato Tipo de contrato.
 *
 * @throws Exception Se o modelo não for encontrado.
 */
function atualizarCabecalhoModelo($vid, $vnome, $vidusuario, $vcontato, $vidTipoCotacao, $tipoContrato)
{
    // Acessar a conexão do banco de dados global
    global $conectar;

    // Aplicar htmlspecialchars a todas as variáveis
    $vid = htmlspecialchars($vid);
    $vidusuario = htmlspecialchars($vidusuario);
    $vcontato = htmlspecialchars($vcontato);
    $vidTipoCotacao = htmlspecialchars($vidTipoCotacao);
    $tipoContrato = htmlspecialchars($tipoContrato);

    // Preparar a consulta SQL para verificar se o registro já existe
    $verificar = $conectar->prepare("SELECT id FROM modelo WHERE id = :id");

    // Vincular o parâmetro à consulta SQL
    $verificar->bindParam(':id', $vid, PDO::PARAM_STR);

    // Executar a consulta SQL
    $verificar->execute();

    // Se o registro existir, atualizar o modelo
    if ($verificar->rowCount() > 0) 
    {
        // Preparar a consulta SQL para atualizar o modelo
        $scriptModelo = "UPDATE modelo
                        SET idusuario = :idusuario,
                            contato = :contato,
                            idTipoCotacao = :idTipoCotacao,
                            tipoContrato = :tipoContrato
                        WHERE id = :id";

        $executarUpdate = $conectar->prepare($scriptModelo);

        // Vincular os parâmetros à consulta SQL
        $executarUpdate->bindParam(':id', $vid, PDO::PARAM_STR);
        $executarUpdate->bindParam(':idusuario', $vidusuario, PDO::PARAM_STR);
        $executarUpdate->bindParam(':contato', $vcontato, PDO::PARAM_STR);
        $executarUpdate->bindParam(':idTipoCotacao', $vidTipoCotacao, PDO::PARAM_STR);
        $executarUpdate->bindParam(':tipoContrato', $tipoContrato, PDO::PARAM_STR);

        // Executar a consulta SQL
        $executarUpdate->execute();
    }
    else
    {
        // Se o modelo não existir, lançar uma exceção
        throw new Exception("Modelo com id $vid não encontrado.");
    }
}


/**
 * Esta função insere dados de modelos no banco de dados.
 *
 * @param string $vidModelo ID do modelo.
 * @param string $vnome Nome do modelo.
 * @param string $vidusuario ID do usuário.
 * @param string $vcontato Contato.
 * @param string $vidTipoCotacao ID do tipo de cotação.
 * @param string $vtipoContrato Tipo de contrato.
 * @param array $vArrayIdSeguro Array de IDs de seguro.
 * @param array $vArrayIdCobertura Array de IDs de cobertura.
 * @param array $vArrayDescricaoCobertura Array de descrições de cobertura.
 * @param array $vArrayFormaPagamento Array de formas de pagamento.
 * @return void
 */
function inserirDadosModelos($vidModelo, $vnome, $vidusuario, $vcontato, $vidTipoCotacao, $vtipoContrato, $vArrayIdSeguro, 
$vArrayIdCobertura, $vArrayDescricaoCobertura, $vArrayFormaPagamento, $arrayTabela) 
{
    try
    {
        global $conectar;

        // Iniciar a transação
        $conectar->beginTransaction();
        $idModelo = 0;

        if (empty($vidModelo))
        {
            $idModelo = inserirCabecalhoModelo($vnome, $vidusuario, $vcontato, $vidTipoCotacao, $vtipoContrato);

            if (empty($idModelo))
            {
                $conectar->rollback();
            }
        }
        else
        {
            atualizarCabecalhoModelo($vidModelo, $vnome, $vidusuario, $vcontato, $vidTipoCotacao, $vtipoContrato);
            $idModelo = $vidModelo;

            if(deletarModelos($idModelo, false) == false)
            {
                $conectar->rollback();
            }
        }

        // Preparar os scripts SQL para inserção de dados
        $scriptCobertura = "INSERT INTO modelocobertura(idModelo, idSeguro, idCobertura, descricao)
                            VALUES (:idModelo, :idSeguro, :idCobertura, :descricaoCobertura)";

        $scriptSeguro = "INSERT INTO modeloseguradora(idModelo, idSeguro)
                        VALUES (:idModelo, :idSeguro)";

        // Preparar as consultas
        $executarInsertSeguro = $conectar->prepare($scriptSeguro);
        $executarInsertCobertura = $conectar->prepare($scriptCobertura);

        $linha = 0;
        $coluna = 0;
        $linhaPagamento = 0;
        $colunaPagamento = 1;

        foreach($vArrayIdSeguro as $seguro)
        {
            $executarInsertSeguro->bindParam(':idModelo', $idModelo);
            $executarInsertSeguro->bindParam(':idSeguro', $seguro);
            $executarInsertSeguro->execute();

            foreach($vArrayIdCobertura as $cobertura)
            {
                $executarInsertCobertura->bindParam(':idModelo', $idModelo);
                $executarInsertCobertura->bindParam(':idSeguro', $seguro); // Supondo que para cada cobertura tem um seguro correspondente no mesmo índice.
                $executarInsertCobertura->bindParam(':idCobertura', $cobertura);
                $executarInsertCobertura->bindParam(':descricaoCobertura', $vArrayDescricaoCobertura[$linha][$coluna]); // Supondo que descrição da cobertura também é um array
                $executarInsertCobertura->execute();
                
                $linha++;
            }

            $linha = 0;
            $coluna++; 

            foreach($vArrayFormaPagamento as $pagamento)
            {
                $scriptPagamento = "INSERT INTO modelopagamento(idModelo, idSeguro, formaPagamento, descricao)
                            VALUES (:idModelo, :idSeguro, :formaPagamento, :descricaoPagamento)";

                $executarInsert = $conectar->prepare($scriptPagamento);
                $executarInsert->bindParam(':idModelo', $idModelo);
                $executarInsert->bindParam(':idSeguro', $seguro); // Supondo que o primeiro seguro é o utilizado para o pagamento.
                $executarInsert->bindParam(':formaPagamento', $pagamento[0]);
                $executarInsert->bindParam(':descricaoPagamento', $pagamento[$colunaPagamento]);
                $executarInsert->execute();

                $linhaPagamento++;
            }

            $linhaPagamento = 0;
            $colunaPagamento++;

        }

        // ...

        // Preparar os scripts SQL para inserção de dados nas tabelas modelotabela e modelotabelacampos
        $scriptModeloTabela = "INSERT INTO modelotabela(nome, idModelo) VALUES (:nome, :idModelo)";
        $scriptModeloTabelaCampos = "INSERT INTO modelotabelacampos(codigoTabela, campo, valor) VALUES (:codigoTabela, :campo, :valor)";

        // Preparar as consultas
        $executarInsertModeloTabela = $conectar->prepare($scriptModeloTabela);
        $executarInsertModeloTabelaCampos = $conectar->prepare($scriptModeloTabelaCampos);

        
        foreach ($arrayTabela as $tabela) {
            // Inserir dados na tabela modelotabela
            $executarInsertModeloTabela->bindParam(':nome', $tabela['tableName']);
            $executarInsertModeloTabela->bindParam(':idModelo', $idModelo);
            $executarInsertModeloTabela->execute();
        
            // Obter o ID da última tabela inserida
            $codigoTabela = $conectar->lastInsertId();
        
            // Percorrer as colunas da tabela
            foreach ($tabela['rows'] as $row) {
                // Inserir dados na tabela modelotabelacampos para cada coluna
                $executarInsertModeloTabelaCampos->bindParam(':codigoTabela', $codigoTabela);
                $executarInsertModeloTabelaCampos->bindParam(':campo', $row['Campo']);
                $executarInsertModeloTabelaCampos->bindParam(':valor', $row['Valor']);
                $executarInsertModeloTabelaCampos->execute();
            }
        }


        // ...
        


        // Finalizar a transação
        $conectar->commit();

        echo "Modelo salvo.\n";
    }
    catch (Exception $ex)
    {
        // Se algo deu errado, cancelar a transação
        $conectar->rollback();
        echo $ex;    
    }
}


/**
 * Esta função deleta modelos no banco de dados.
 *
 * @param string $idModelo ID do modelo a ser deletado.
 * @param bool $excluirCabecalho Se verdadeiro, deleta o registro principal na tabela modelo.
 * @return bool Retorna verdadeiro se a operação foi bem sucedida, falso caso contrário.
 */
function deletarModelos($idModelo, $excluirCabecalho)
{
    try
    {
        global $conectar;

        // Filtrar as entradas
        $excluirCabecalho = filter_var($excluirCabecalho, FILTER_VALIDATE_BOOLEAN);

        if ($excluirCabecalho == true)
        {
            // Deletar o registro principal na tabela modelo
            $scriptDeletarModelo = "DELETE FROM modelo WHERE id = :idModelo";
            $executarDelete = $conectar->prepare($scriptDeletarModelo);
            $executarDelete->bindParam(':idModelo', $idModelo);
            $executarDelete->execute();
        }

        // Deletar registros relacionados em tabelas dependentes
        $scriptDeletarCobertura = "DELETE FROM modelocobertura WHERE idModelo = :idModelo";
        $executarDelete = $conectar->prepare($scriptDeletarCobertura);
        $executarDelete->bindParam(':idModelo', $idModelo);
        $executarDelete->execute();

        $scriptDeletarSeguro = "DELETE FROM modeloseguradora WHERE idModelo = :idModelo";
        $executarDelete = $conectar->prepare($scriptDeletarSeguro);
        $executarDelete->bindParam(':idModelo', $idModelo);
        $executarDelete->execute();

        $scriptDeletarPagamento = "DELETE FROM modelopagamento WHERE idModelo = :idModelo";
        $executarDelete = $conectar->prepare($scriptDeletarPagamento);
        $executarDelete->bindParam(':idModelo', $idModelo);
        $executarDelete->execute();

        // Deletar registros relacionados nas tabelas modelotabela e modelotabelacampos
        $scriptDeletarTabelaCampos = "DELETE FROM modelotabelacampos WHERE codigoTabela IN (SELECT id FROM modelotabela WHERE idModelo = :idModelo)";
        $executarDelete = $conectar->prepare($scriptDeletarTabelaCampos);
        $executarDelete->bindParam(':idModelo', $idModelo);
        $executarDelete->execute();

        $scriptDeletarTabela = "DELETE FROM modelotabela WHERE idModelo = :idModelo";
        $executarDelete = $conectar->prepare($scriptDeletarTabela);
        $executarDelete->bindParam(':idModelo', $idModelo);
        $executarDelete->execute();

        return true;
    }
    catch (Exception $ex)
    {
        // Se algo deu errado, cancelar a transação
        $conectar->rollback();
        return false;    
    }
}


/**
 * Esta função busca modelos no banco de dados.
 *
 * @param string $idModelo ID do modelo a ser buscado.
 * @return void Retorna os dados do modelo em formato JSON.
 */
function buscarModelos($idModelo)
{
    try
    {
        global $conectar;

        $dados = array();
        // Buscar dados do cabeçalho do modelo
        $scriptBuscarModelo = "SELECT * FROM modelo WHERE id = :id ORDER BY id";
        $executarBuscar = $conectar->prepare($scriptBuscarModelo);
        $executarBuscar->bindParam(':id', $idModelo);
        $executarBuscar->execute();
        $dados['modelo'] = $executarBuscar->fetchAll(PDO::FETCH_ASSOC);

        // Buscar dados da cobertura
        $scriptBuscarCobertura = "SELECT * FROM modelocobertura WHERE idModelo = :idModelo ORDER BY id";
        $executarBuscar = $conectar->prepare($scriptBuscarCobertura);
        $executarBuscar->bindParam(':idModelo', $idModelo);
        $executarBuscar->execute();
        $dados['cobertura'] = $executarBuscar->fetchAll(PDO::FETCH_ASSOC);

        // Buscar dados da seguradora
        $scriptBuscarSeguro = "SELECT * FROM modeloseguradora WHERE idModelo = :idModelo ORDER BY id";
        $executarBuscar = $conectar->prepare($scriptBuscarSeguro);
        $executarBuscar->bindParam(':idModelo', $idModelo);
        $executarBuscar->execute();
        $dados['seguro'] = $executarBuscar->fetchAll(PDO::FETCH_ASSOC);

        // Buscar dados do pagamento
        $scriptBuscarPagamento = "SELECT * FROM modelopagamento WHERE idModelo = :idModelo ORDER BY id";
        $executarBuscar = $conectar->prepare($scriptBuscarPagamento);
        $executarBuscar->bindParam(':idModelo', $idModelo);
        $executarBuscar->execute();
        $dados['pagamento'] = $executarBuscar->fetchAll(PDO::FETCH_ASSOC);

        // Buscar dados da tabela modelotabela
        $scriptBuscarTabela = "SELECT * FROM modelotabela WHERE idModelo = :idModelo ORDER BY codigoTabela";
        $executarBuscar = $conectar->prepare($scriptBuscarTabela);
        $executarBuscar->bindParam(':idModelo', $idModelo);
        $executarBuscar->execute();
        $dados['tabela'] = $executarBuscar->fetchAll(PDO::FETCH_ASSOC);

        // Buscar dados da tabela modelotabelacampos
        $scriptBuscarTabelaCampos = "SELECT * FROM modelotabelacampos WHERE codigoTabela IN (SELECT codigoTabela FROM modelotabela WHERE idModelo = :idModelo) ORDER BY codigoCampo";
        $executarBuscar = $conectar->prepare($scriptBuscarTabelaCampos);
        $executarBuscar->bindParam(':idModelo', $idModelo);
        $executarBuscar->execute();
        $dados['tabelaCampos'] = $executarBuscar->fetchAll(PDO::FETCH_ASSOC);

        $dados['id'] = $idModelo;

        // Retornar os dados como JSON
        echo json_encode($dados);
    }
    catch (PDOException $pdoEx)
    {
        // Aqui você pode lidar especificamente com erros de PDO
        echo json_encode(["error" => "Erro no PDO: " . $pdoEx->getMessage()]);
    }
    catch (Exception $ex)
    {
        // Se algo deu errado, exibir a exceção
        echo json_encode(["error" => $ex->getMessage()]);
    }
}


/**
 * Esta função carrega um select com os modelos do banco de dados.
 *
 * @return void Retorna as opções do select em formato HTML.
 */
function carregarSelectModelo()
{
    try
    {
        global $conectar;
        $script = "SELECT id, nome FROM modelo";

        $executarSelect = $conectar->prepare($script);
        $executarSelect->execute();
        $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
        $total = count($resultado);

        if($total > 0)
        {
            foreach ($resultado as $dados)
            {
                // Usar htmlspecialchars para prevenir ataques XSS
                $id = htmlspecialchars($dados["id"]);
                $nome = htmlspecialchars($dados["nome"]);

                echo "
                    <option value='{$id}'>{$nome}</option>
                ";
            }       
        } 
    }
    catch (Exception $ex) 
    {
        // Se algo deu errado, exibir a exceção
        echo "Erro - Não foi possível carregar os dados do modelo. " . $ex;
    }
}


?>