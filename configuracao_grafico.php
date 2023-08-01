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

if ($tipoTransacao === "carregarDadosAgendamento") // Execução
{
    carregarDadosAgendamento();
}
else if ($tipoTransacao === "carregarDadosIndicadores")
{
    carregarDadosIndicadores();
}


/**
 * Carrega os dados de agendamento para os últimos 30 dias.
 * 
 * @return void
 */
function carregarDadosAgendamento()
{
    $dadosAgendamento = array();
    $linha = 0;

    try
    {
        global $conectar;
        
        // Query para obter os dados de agendamento dos últimos 30 dias, agrupados por data e status
        $script = "
        SELECT data, status, count(*) AS 'total' FROM agendamentos WHERE data BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE() GROUP BY data, status";

        $executarSelect = $conectar->query($script);
        $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultado as $dados)
        {
            $dadosAgendamento[$linha]["data"]    = $dados["data"];
            $dadosAgendamento[$linha]["status"]  = $dados["status"];
            $dadosAgendamento[$linha]["total"]   = $dados["total"];

            $linha++;
        }

        // Retorna os dados de agendamento como um JSON
        echo json_encode($dadosAgendamento);
    }
    catch (Exception $ex)
    {
        // Em caso de erro, exibe uma mensagem de erro
        echo "Erro - Não foi possível carregar os dados do agendamento.\nErro: " . $ex->getMessage();
    }
}

/**
 * Carrega os dados de indicadores, como o total de agendamentos, usuários e clientes.
 * 
 * @return void
 */
function carregarDadosIndicadores()
{
    $dadosIndicadores = array();

    try
    {
        global $conectar;

        // Query para obter o total de agendamentos
        $script = "SELECT count(*) as 'total' FROM agendamentos";
        $executarSelect = $conectar->query($script);
        $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultado as $dados)
        {
            $dadosIndicadores["totalAgendamento"]["total"] = $dados["total"];
        }

        // Query para obter o total de usuários
        $script = "SELECT count(*) as 'total' FROM usuarios";
        $executarSelectUsuario = $conectar->query($script);
        $resultadoUsuario = $executarSelectUsuario->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultadoUsuario as $dados)
        {
            $dadosIndicadores["totalUsuarios"]["total"] = $dados["total"];
        }

        // Query para obter o total de clientes
        $script = "SELECT count(*) as 'total' FROM clientes";
        $executarSelectClientes = $conectar->query($script);
        $resultadoClientes = $executarSelectClientes->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultadoClientes as $dados)
        {
            $dadosIndicadores["totalClientes"]["total"] = $dados["total"];
        }

        // Retorna os dados de indicadores como um JSON
        echo json_encode($dadosIndicadores);
    }
    catch (Exception $ex)
    {
        // Em caso de erro, exibe uma mensagem de erro
        echo "Erro - Não foi possível carregar os dados de indicadores.\nErro: " . $ex->getMessage();
    }
}

  ?>