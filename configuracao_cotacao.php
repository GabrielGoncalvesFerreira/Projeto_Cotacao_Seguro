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

    $tipoTransacao = filter_input(INPUT_POST, 'tipoTransacao', FILTER_SANITIZE_SPECIAL_CHARS);

    if ($tipoTransacao == "consultarCorretores"){
        consultarCorretores();
    }
    elseif ($tipoTransacao == "consultarTiposCotacao"){
        consultarTiposCotacao();
    }
    elseif($tipoTransacao == "consultarSeguradoras"){
        consultarSeguradoras();
    }
    elseif($tipoTransacao == "consultarServicos"){
        $idCategoria = filter_input(INPUT_POST, 'idCategoria', FILTER_SANITIZE_NUMBER_INT);
        consultarServicos($idCategoria);
    }


/**
 * Carrega os dados dos corretores e adicionar ao campo select.
 */
function consultarCorretores()
{
    try
    {
        global $conectar;
        $script = "SELECT id, nome FROM usuarios";

        // Preparar a consulta SQL
        $stmt = $conectar->prepare($script);

        // Executar a consulta
        $stmt->execute();

        // Obter os resultados
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $corretores = [];

        foreach ($resultado as $dados)
        {
            // Adiciona cada corretor ao array $corretores
            $corretores[] = $dados;
        } 

        // Retorna os corretores como um JSON
        echo json_encode($corretores);
    }
    catch (Exception $ex) 
    {
        // Log the error message
        error_log($ex->getMessage());

        echo "Erro - Não foi possível carregar os dados dos corretores.";
    }
}


/**
 * Carrega os dados de tipo de cotacao
 */
function consultarTiposCotacao()
{
    try
    {
        global $conectar;
        $script = "SELECT idcategoria, nome FROM categoria WHERE status = 'A'";

        // Preparar a consulta SQL
        $stmt = $conectar->prepare($script);

        // Executar a consulta
        $stmt->execute();

        // Obter os resultados
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tiposCotacao = [];

        foreach ($resultado as $dados)
        {
            // Adiciona cada corretor ao array $tiposCotacao
            $tiposCotacao[] = $dados;
        } 

        // Retorna os tiposCotacao como um JSON
        echo json_encode($tiposCotacao);
    }
    catch (Exception $ex) 
    {
        // Log the error message
        error_log($ex->getMessage());

        echo "Erro - Não foi possível carregar os dados de tipos de cotação.";
    }
}


/**
 * Carrega os dados de seguradoras
 */
function consultarSeguradoras()
{
    try
    {
        global $conectar;
        $script = "SELECT id, nome FROM seguro WHERE status = 'A'";

        // Preparar a consulta SQL
        $stmt = $conectar->prepare($script);

        // Executar a consulta
        $stmt->execute();

        // Obter os resultados
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $seguradoras = [];

        foreach ($resultado as $dados)
        {
            // Adiciona cada corretor ao array $seguradoras
            $seguradoras[] = $dados;
        } 

        // Retorna as seguradoras como um JSON
        echo json_encode($seguradoras);
    }
    catch (Exception $ex) 
    {
        // Log the error message
        error_log($ex->getMessage());

        echo "Erro - Não foi possível carregar as seguradoras.";
    }
}


/**
 * Carrega os dados de serviços
 */
function consultarServicos($idCategoria = null)
{
    try
    {
        global $conectar;
        $script = "SELECT idservico, nome, descricao FROM servico WHERE status = 'A'";

        if ($idCategoria !== null) {
            $script .= " AND idcategoria = :idCategoria";
        }

        // Preparar a consulta SQL
        $stmt = $conectar->prepare($script);

        if ($idCategoria !== null) {
            $stmt->bindParam(':idCategoria', $idCategoria, PDO::PARAM_INT);
        }

        // Executar a consulta
        $stmt->execute();

        // Obter os resultados
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $servicos = [];

        foreach ($resultado as $dados)
        {
            // Adiciona cada serviço ao array $servicos
            $servicos[] = $dados;
        } 

        // Retorna os servicos como um JSON
        echo json_encode($servicos);
    }
    catch (Exception $ex) 
    {
        // Log the error message
        error_log($ex->getMessage());

        echo "Erro - Não foi possível carregar os serviços.";
    }
}



?>