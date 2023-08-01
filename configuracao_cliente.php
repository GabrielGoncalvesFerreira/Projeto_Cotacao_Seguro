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
$idCliente = "";
$nomeCliente = "";
$tipo = "";
$identificacao = "";
$origem = "";
$cidade = "";
$cep = "";

// Usar filter_input() para obter dados POST de maneira segura
$tipoTransacao = filter_input(INPUT_POST, 'tipoTransacao', FILTER_SANITIZE_SPECIAL_CHARS);

if ($tipoTransacao == "consultarClientes") //Consulta
{
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
    $limite = filter_input(INPUT_POST, 'limite', FILTER_SANITIZE_NUMBER_INT);
    $limite = empty($limite) ? 10 : $limite;
    carregarDadosClientes($id, $nome, $status, $limite);
}
else if ($tipoTransacao == "cadastrarCliente"){
    $idCliente     = filter_input(INPUT_POST, 'codigoCliente', FILTER_SANITIZE_NUMBER_INT);
    $nomeCliente   = filter_input(INPUT_POST, 'nomeCliente', FILTER_SANITIZE_SPECIAL_CHARS);
    $tipo          = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
    $identificacao = filter_input(INPUT_POST, 'identificacao', FILTER_SANITIZE_SPECIAL_CHARS);
    $origem        = filter_input(INPUT_POST, 'origem', FILTER_SANITIZE_SPECIAL_CHARS);
    $cidade        = filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_SPECIAL_CHARS);
    $cep           = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($idCliente))
    {
        inserirNovoCliente($nomeCliente, $tipo, $identificacao, $origem, $cidade, $cep);
    }
    else
    {
        atualizarRegistroCliente($idCliente, $nomeCliente, $tipo, $identificacao, $origem, $cidade, $cep);
    }
} 
else if ($tipoTransacao == "buscarRegistroCliente")
{
    $codigoCliente = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    buscarRegistroCliente($codigoCliente);
}
else if ($tipoTransacao == "carregarDadosHistoricoAgendamentosClientes")
{
    $codigoCliente = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    buscarHistoricoAgendamentosCliente($codigoCliente);
}


/**
 * Insere um novo cliente no banco de dados
 * 
 * @param string $vnomeCliente Nome do cliente
 * @param string $vtipo Tipo de cadastro (PF ou PJ)
 * @param string $videntificacao Número do documento de identificacao
 * @param string $vorigem Origem do cliente, onde ele ficou sabendo da empresa
 * @param string $vcidade Cidade do cliente
 * @param string $vcep CEP do cliente
 */
function inserirNovoCliente($vnomeCliente, $vtipo, $videntificacao, $vorigem, $vcidade, $vcep){
    try
    {
        global $conectar;

        // Usar bindParam para prevenir ataques de injeção SQL
        $scriptInserirNovoCliente = " INSERT INTO clientes (idcliente, nomeCliente, tipo, identificacao, origem, cidade, cep)
                                      SELECT (MAX(idcliente) + 1), :nomeCliente, :tipo, :identificacao, :origem, :cidade, :cep
                                      FROM clientes;
        ";

        $executarInsert = $conectar->prepare($scriptInserirNovoCliente);
        $executarInsert->bindParam(':nomeCliente', $vnomeCliente, PDO::PARAM_STR);
        $executarInsert->bindParam(':tipo', $vtipo, PDO::PARAM_STR);
        $executarInsert->bindParam(':identificacao', $videntificacao, PDO::PARAM_STR);
        $executarInsert->bindParam(':origem', $vorigem, PDO::PARAM_STR);
        $executarInsert->bindParam(':cidade', $vcidade, PDO::PARAM_STR);
        $executarInsert->bindParam(':cep', $vcep, PDO::PARAM_STR);
        $executarInsert->execute();

        echo "Novo cliente cadastrado com sucesso.";
    }
    catch (Exception $ex) 
    {
        error_log("Erro ao carregar os dados do cliente: " . $ex);
        echo $ex;    
    }
}


/**
 * Atualiza os dados de um cliente no banco de dados
 * 
 * @param int $codigoCliente Código do cliente a ser atualizado
 * @param string $vnomeCliente Nome do cliente
 * @param string $vtipo Tipo de cadastro (PF ou PJ)
 * @param string $videntificacao Número do documento de identificacao
 * @param string $vorigem Origem do cliente, onde ele ficou sabendo da empresa
 * @param string $vcidade Cidade do cliente
 * @param string $vcep CEP do cliente
 */
function atualizarRegistroCliente($codigoCliente, $vnomeCliente, $vtipo, $videntificacao, $vorigem, $vcidade, $vcep){
    try
    {
        global $conectar;

        // Usar bindParam para prevenir ataques de injeção SQL
        $scriptAtualizarRegistroCliente = " UPDATE clientes 
                                            SET nomeCliente = :nomeCliente, tipo = :tipo, identificacao = :identificacao, 
                                                origem = :origem, cidade = :cidade, cep = :cep
                                            WHERE idcliente = :codigoCliente
        ";

        $executarUpdate = $conectar->prepare($scriptAtualizarRegistroCliente);
        $executarUpdate->bindParam(':codigoCliente', $codigoCliente, PDO::PARAM_INT);
        $executarUpdate->bindParam(':nomeCliente', $vnomeCliente, PDO::PARAM_STR);
        $executarUpdate->bindParam(':tipo', $vtipo, PDO::PARAM_STR);
        $executarUpdate->bindParam(':identificacao', $videntificacao, PDO::PARAM_STR);
        $executarUpdate->bindParam(':origem', $vorigem, PDO::PARAM_STR);
        $executarUpdate->bindParam(':cidade', $vcidade, PDO::PARAM_STR);
        $executarUpdate->bindParam(':cep', $vcep, PDO::PARAM_STR);
        $executarUpdate->execute();

        echo "Registro de cliente atualizado com sucesso.";
    }
    catch (Exception $ex) 
    {
        error_log("Erro ao carregar os dados do cliente: " . $ex);
        echo $ex;    
    }
}


/**
 * Carrega os dados dos clientes e exibe na tela.
 * 
 * @param int $id ID do cliente
 * @param string $nome Nome do cliente
 * @param string $status Status do cliente
 * @param int $limite Limite de registros a serem retornados
 */
function carregarDadosClientes($id, $nome, $status, $limite)
{
    try
    {
        global $conectar;

        // Usar bindParam para prevenir ataques de injeção SQL
        $script = "SELECT idCliente, nomeCliente, tipo, identificacao, origem, cidade, cep 
                   FROM clientes 
                   WHERE status = :status";

        if (!empty($id)) {
            $script .= " AND idCliente = :id";
        }

        if (!empty($nome)) {
            $script .= " AND nomeCliente LIKE :nome";
            $nome = "%$nome%";
        }

        if (!empty($limite)) {
            $script .= " LIMIT :limite";
        }

        $executarSelect = $conectar->prepare($script);
        $executarSelect->bindParam(':status', $status, PDO::PARAM_STR);

        if (!empty($id)) {
            $executarSelect->bindParam(':id', $id, PDO::PARAM_INT);
        }

        if (!empty($nome)) {
            $executarSelect->bindParam(':nome', $nome, PDO::PARAM_STR);
        }

        if (!empty($limite)) {
            $executarSelect->bindParam(':limite', $limite, PDO::PARAM_INT);
        }

        $executarSelect->execute();
        $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
       
        foreach ($resultado as $dados)
        {
            echo "
            <tr>
                <td>{$dados["idCliente"]}</td>
                <td>{$dados["nomeCliente"]}</td>
                <td>{$dados["tipo"]}</td>
                <td>{$dados["identificacao"]}</td>
                <td>{$dados["origem"]}</td>
                <td>{$dados["cidade"]}</td>
                <td>{$dados["cep"]}</td>
                <td>
                <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modalCliente' data-titulo='Editar Cliente' data-id='{$dados["idCliente"]}' onclick='buscarRegistroCliente();carregarDadosHistoricoAgendamentosClientes();'>
                        <i class='bi bi-pencil-square'></i> 
                    </button>
                </td>
                <td></td>
            </tr>
            ";
        }        
    }
    catch (Exception $ex) 
    {
        error_log("Erro ao carregar os dados do cliente: " . $ex);
        echo "Erro - Não foi possível carregar os clientes cadastrados.";
    }
}


/**
 * Buscar dados de um cliente de acordo com o código
 * 
 * @param int $codigoCliente Código do cliente.
 */
function buscarRegistroCliente($codigoCliente)
{
    $dadosCliente = array();

    try
    {
        global $conectar;

        // Usar bindParam para prevenir ataques de injeção SQL
        $script = "SELECT nomeCliente, tipo, identificacao, origem, cidade, cep 
                   FROM clientes 
                   WHERE idcliente = :codigoCliente";

        $executarSelect = $conectar->prepare($script);
        $executarSelect->bindParam(':codigoCliente', $codigoCliente, PDO::PARAM_INT);
        $executarSelect->execute();

        $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultado as $dados)
        {
            $dadosCliente["nomeCliente"]   = $dados["nomeCliente"];
            $dadosCliente["tipo"]          = $dados["tipo"];
            $dadosCliente["identificacao"] = $dados["identificacao"];
            $dadosCliente["origem"]        = $dados["origem"];
            $dadosCliente["cidade"]        = $dados["cidade"];
            $dadosCliente["cep"]           = $dados["cep"];
        }

        echo json_encode($dadosCliente);
    }
    catch (Exception $ex)
    {
        error_log("Erro ao carregar os dados do cliente: " . $ex);
        echo "Erro - Não foi possível carregar os dados do cliente $codigoCliente.\nErro: $ex";
    }
}


/**
 * Buscar dados do histórico de agendamentos de um cliente.
 * 
 * @param int $codigoCliente Código do cliente.
 */
function buscarHistoricoAgendamentosCliente($codigoCliente)
{
    try
    {
        global $conectar;

        // Usar bindParam para prevenir ataques de injeção SQL
        $script = "SELECT codigoagendamento, status, codigotipo, DATE_FORMAT(data, '%d/%m/%Y') as data, observacao, codigousuario 
                   FROM agendamentos 
                   WHERE codigocliente = :codigoCliente 
                   ORDER BY data desc";

        $executarSelect = $conectar->prepare($script);
        $executarSelect->bindParam(':codigoCliente', $codigoCliente, PDO::PARAM_INT);
        $executarSelect->execute();

        $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
       
        foreach ($resultado as $dados)
        {
            echo "
            <tr>
                <td>{$dados["codigoagendamento"]}</td>
                <td>{$dados["status"]}</td>
                <td>{$dados["data"]}</td>
                <td>{$dados["codigotipo"]}</td>
                <td>{$dados["observacao"]}</td>
                <td>{$dados["codigousuario"]}</td>
            </tr>
            ";
        }        
    }
    catch (Exception $ex) 
    {
        error_log("Erro ao carregar os dados do cliente: " . $ex);
        echo "Erro - Não foi possível carregar o histórico de agendamentos.";
    }
}


?>