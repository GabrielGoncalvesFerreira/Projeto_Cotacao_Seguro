<?php

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

if ($tipoTransacao == "consultarCategoria") //Execução
{
    carregarDadosCategoria();
}
else if($tipoTransacao == "cadastrarCategoria")
{
    $idCategoria = filter_input(INPUT_POST, 'idCategoria', FILTER_SANITIZE_NUMBER_INT);
    $nomeCategoria = filter_input(INPUT_POST, 'nomeCategoria', FILTER_SANITIZE_STRING);
    $descricaoCategoria = filter_input(INPUT_POST, 'descricaoCategoria', FILTER_SANITIZE_STRING);

    if (!empty($nomeCategoria) && !empty($descricaoCategoria))
    {
        if (empty($idCategoria))
        {
            inserirCategoria($nomeCategoria,  $descricaoCategoria);
        }
        else
        {
            atualizarCategoria($idCategoria, $nomeCategoria,  $descricaoCategoria);
        }
    }
    else
    {
        echo "Preencha os dados para continuar com o processo";
    }
}
else if ($tipoTransacao == "carregarDadosCategoriaSelect")
{
    carregarDadosCategoriaSelect();
}
else if ($tipoTransacao == "cadastrarServico")
{
    $idServico = filter_input(INPUT_POST, 'idServico', FILTER_SANITIZE_NUMBER_INT);
    $nomeServico = filter_input(INPUT_POST, 'nomeServico', FILTER_SANITIZE_STRING);
    $descricaoServico = filter_input(INPUT_POST, 'descricaoServico', FILTER_SANITIZE_STRING);
    $categoria = filter_input(INPUT_POST, 'categoriaServico', FILTER_SANITIZE_NUMBER_INT);
    $status = filter_input(INPUT_POST, 'statusServico', FILTER_SANITIZE_STRING);

    if (!empty($nomeServico) && !empty($descricaoServico) && !empty($status))
    {
        if (empty($idServico))
        {
            inserirServico($nomeServico,  $descricaoServico, $categoria, $status);
        }
        else
        {
            atualizarServico($idServico, $nomeServico,  $descricaoServico, $categoria, $status);
        }
    }
    else
    {
        echo "Preencha os dados para continuar com o processo";
    }
}
else if($tipoTransacao == "consultarServico")
{
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING); 
    $limite = filter_input(INPUT_POST, 'limite', FILTER_SANITIZE_NUMBER_INT);
    carregarDadosServico($id, $nome, $status, $limite);
}
else if ($tipoTransacao == "buscarServico")
{
    $idServico = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    buscarServico($idServico);
}
else if ($tipoTransacao == "excluirCategoria"){
    $idCategoria = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    excluirCategoria($idCategoria);
}

    /**
     * Inativa a categoria na base
     */
    function excluirCategoria($vId)
    {
        try
        {
            global $conectar;
    
            $script = "UPDATE categoria SET status = 'I' WHERE idCategoria = :vId";

            $executarUpdate = $conectar->prepare($script);
            $executarUpdate->bindParam(':vId', $vId, PDO::PARAM_INT);
            $executarUpdate->execute();
    
    
            // Se a execução for bem-sucedida, retorne um JSON com uma mensagem de sucesso
            echo json_encode(array("status" => "success", "message" => "Categoria excluída com sucesso."));
        }
        catch (Exception $ex) 
        {
            // Se ocorrer um erro, retorne um JSON com uma mensagem de erro
            echo json_encode(array("status" => "error", "message" => "Erro ao tentar excluir categoria. " . $ex->getMessage()));
        }
    }
    

    /**
    * Carrega os dados dos categoria e exibe na tabela.
    */
    function carregarDadosCategoria()
    {
        try
        {
            global $conectar;
            $script = "SELECT idCategoria, nome, descricao FROM categoria WHERE status = 'A'";
    
            $executarSelect = $conectar->prepare($script);
            $executarSelect->execute();
            $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
            $total = count($resultado);    

            if($total > 0)
            {
                foreach ($resultado as $dados)
                {
                    echo "
                    <tr>
                        <td>{$dados["idCategoria"]}</td>
                        <td>{$dados["nome"]}</td>
                        <td>{$dados["descricao"]}</td>
                        <td>
                            <button type='button' class='btn btn-primary' id='{$dados["idCategoria"]}' onclick='preencherCamposDadosCategoria(this);'>
                                <i class='bi bi-pencil-square'></i>
                            </button>
                            <button type='button' class='btn btn-danger' id='{$dados["idCategoria"]}' onclick='excluirCategoria(id);'>
                                <i class='bi bi-trash'></i>
                            </button>
                        </td>
                    </tr>
                    ";
                }       
            } 
        }
        catch (Exception $ex) 
        {
            echo "Erro - Não foi possível carregar os dados de categoria. "+ $ex;
        }
    }

    /**
    * Carrega os dados dos categoria e exibe na tabela.
    */
    function carregarDadosCategoriaSelect()
    {
        try
        {
            global $conectar;
            $script = "SELECT idCategoria, nome FROM categoria WHERE status = 'A'";
    
            $executarSelect = $conectar->prepare($script);
            $executarSelect->execute();
            $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
            $total = count($resultado);

            if($total > 0)
            {
                foreach ($resultado as $dados)
                {
                    echo "
                        <option value='{$dados["idCategoria"]}'>{$dados["nome"]}</option>
                    ";
                }       
            } 
        }
        catch (Exception $ex) 
        {
            echo "Erro - Não foi possível carregar os dados de categoria. "+ $ex;
        }
    }

    /**
     * Valida a duplicidade do nome da Categoria.
     *
     * @param string $vnome O nome do categoria a ser validado
     * @return bool Retorna true se o categoria já existir, caso contrário retorna false
     */
    function validarDuplicidadeCategoria($vnome, $vDescricao)
    {
        try
        {
            global $conectar;
            $script = "SELECT idCategoria FROM categoria WHERE UPPER(nome) = :nome and UPPER(descricao) = :descricao and status = 'A'";
    
            $executarSelect = $conectar->prepare($script);
            $executarSelect->bindParam(':nome', strtoupper($vnome), PDO::PARAM_STR);
            $executarSelect->bindParam(':descricao', strtoupper($vDescricao), PDO::PARAM_STR);
            $executarSelect->execute();
    
            $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
            $total = count($resultado);

            if($total)
            {
                return true;
            }
            
            return false;
        }
        catch (Exception $ex) 
        {
            return false;
        }
    }

    /**
     * Atualiza um categoria existente.
     *
     * @param string $vId O ID do categoria a ser atualizado
     * @param string $vnome O nome do categoria
     * @param string $vDescricao A descrição do categoria
     */
    function atualizarCategoria($vId, $vnome, $vDescricao)
    {
        try
        {
            global $conectar;

            if (validarDuplicidadeCategoria($vnome, $vDescricao) == false)
            {
                $script = "UPDATE categoria SET nome = :nome, descricao = :descricao WHERE idCategoria = :id";

                $executarUpdate = $conectar->prepare($script);
                $executarUpdate->bindParam(':nome', $vnome, PDO::PARAM_STR);
                $executarUpdate->bindParam(':descricao', $vDescricao, PDO::PARAM_STR);
                $executarUpdate->bindParam(':id', $vId, PDO::PARAM_INT);
                $executarUpdate->execute();

                echo "Categoria atualizado com sucesso.";
            }
            else
            {
                echo "Categoria Já cadastrado.";
            }
        }
        catch (Exception $ex) 
        {
            echo $ex;    
        }
    }

    /**
     * Insere um novo categoria no banco de dados.
     *
     * @param string $vnome O nome do categoria
     * @param string $vDescricao A descrição do categoria
     */
    function inserirCategoria($vnome, $vDescricao)
    {
        try
        {
            global $conectar;

            if (validarDuplicidadeCategoria($vnome, $vDescricao) == false)
            {
                $script = "INSERT INTO categoria(nome, descricao) 
                VALUES (:nome, :descricao)";

                $executarInsert = $conectar->prepare($script);
                $executarInsert->bindParam(':nome', $vnome, PDO::PARAM_STR);
                $executarInsert->bindParam(':descricao', $vDescricao, PDO::PARAM_STR);
                $executarInsert->execute();

                echo "Categoria cadastrado com sucesso.";
            }
            else
            {
                echo "Categoria Já cadastrado.";
            }
        }
        catch (Exception $ex) 
        {
            echo $ex;    
        }
    }

    /**
    * Carrega os dados dos serviços e exibe na tabela.
    */
    function carregarDadosServico($id, $nome, $status, $limite)
    {
        try
        {
            global $conectar;
            $script = "SELECT servico.idServico, 
                        servico.nome, 
                        servico.descricao, 
                        (CASE
                            WHEN servico.idCategoria = 0 THEN 'Todos'
                            WHEN servico.idCategoria = '' THEN 'Todos'
                            ELSE categoria.nome
                        END) AS 'categoria', 
                        servico.status
                        FROM servico 
                        LEFT JOIN categoria ON servico.idCategoria = categoria.idCategoria
                        WHERE servico.status = :status";
    
            if (!empty($id)) {
                $script .= " AND servico.idServico = :id";
            }
            if (!empty($nome)) {
                $script .= " AND servico.nome like :nome";
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
                $nome = "%$nome%";
                $executarSelect->bindParam(':nome', $nome, PDO::PARAM_STR);
            }
            if (!empty($limite)) {
                $executarSelect->bindParam(':limite', $limite, PDO::PARAM_INT);
            }
    
            $executarSelect->execute();
            $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
            $total = count($resultado);
    
            if($total > 0)
            {
                foreach ($resultado as $dados)
                {
                    echo "
                    <tr>
                        <td>{$dados["idServico"]}</td>
                        <td>{$dados["nome"]}</td>
                        <td>{$dados["descricao"]}</td>
                        <td>{$dados["categoria"]}</td>
                        <td>{$dados["status"]}</td>
                        <td>
                            <button type='button' class='btn btn-primary' id='servico{$dados["idServico"]}' data-bs-toggle='modal' data-bs-target='#modalServico' data-titulo='Editar serviço' data-id='{$dados["idServico"]}' onclick='buscarServico();'>
                                <i class='bi bi-pencil-square'></i>
                            </button>
                        </td>
                    </tr>
                    ";
                }       
            } 
        }
        catch (Exception $ex) 
        {
            echo "Erro - Não foi possível carregar os dados de categoria. "+ $ex;
        }
    }
    

    /**
     * Valida a duplicidade do nome da Serviço.
     *
     * @param string $vnome O nome do serviço a ser validado
     * @return bool Retorna true se o serviço já existir, caso contrário retorna false
     */
    function validarDuplicidadeServico($vnome, $vId)
    {
        try
        {
            global $conectar;
            $script = "SELECT idServico FROM servico WHERE nome = UPPER(:nome) AND idServico <> :id";
    
            $executarSelect = $conectar->prepare($script);
            $executarSelect->bindParam(':nome', strtoupper($vnome), PDO::PARAM_STR);
            $executarSelect->bindParam(':id', $vId, PDO::PARAM_INT);
            $executarSelect->execute();
    
            $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
            $total = count($resultado);
    
            if($total)
            {
                return true;
            }
            
            return false;
        }
        catch (Exception $ex) 
        {
            return false;
        }
    }
    

    /**
     * Atualiza um serviço existente.
     *
     * @param string $vId O ID do serviço a ser atualizado
     * @param string $vnome O nome do serviço
     * @param string $vDescricao A descrição do serviço
     * @param string $vStatus O status do serviço
     * @param string $vCategoria O ID da categoria
     */
    function atualizarServico($vId, $vnome, $vDescricao, $vCategoria, $vStatus)
    {
        try
        {
            global $conectar;
    
            if (validarDuplicidadeServico($vnome, $vId) == false)
            {
                $script = "UPDATE servico SET nome = :nome, descricao = :descricao, status = :status, idcategoria = :categoria WHERE idServico = :id";
    
                $executarUpdate = $conectar->prepare($script);
                $executarUpdate->bindParam(':nome', $vnome, PDO::PARAM_STR);
                $executarUpdate->bindParam(':descricao', $vDescricao, PDO::PARAM_STR);
                $executarUpdate->bindParam(':status', $vStatus, PDO::PARAM_STR);
                $executarUpdate->bindParam(':categoria', $vCategoria, PDO::PARAM_STR);
                $executarUpdate->bindParam(':id', $vId, PDO::PARAM_INT);
                $executarUpdate->execute();
    
                echo "Serviço atualizado com sucesso.";
            }
            else
            {
                echo "Serviço Já cadastrado.";
            }
        }
        catch (Exception $ex) 
        {
            echo $ex;    
        }
    }
    

    /**
     * Insere um novo serviço no banco de dados.
     *
     * @param string $vnome O nome do categoria
     * @param string $vDescricao A descrição do categoria
     * @param string $vCategoria O ID da categoria
     * @param string $vStatus O status do serviço
     */
    function inserirServico($vnome, $vDescricao, $vCategoria, $vStatus)
    {
        try
        {
            global $conectar;
    
            if (validarDuplicidadeServico($vnome, 0) == false)
            {
                $script = "INSERT INTO servico(nome, descricao, status, idCategoria) 
                            VALUES (:nome, :descricao, :status, :categoria)";
    
                $executarInsert = $conectar->prepare($script);
                $executarInsert->bindParam(':nome', $vnome, PDO::PARAM_STR);
                $executarInsert->bindParam(':descricao', $vDescricao, PDO::PARAM_STR);
                $executarInsert->bindParam(':status', $vStatus, PDO::PARAM_STR);
                $executarInsert->bindParam(':categoria', $vCategoria, PDO::PARAM_STR);
                $executarInsert->execute();
    
                echo "Serviço cadastrado com sucesso.";
            }
            else
            {
                echo "Serviço Já cadastrado.";
            }
        }
        catch (Exception $ex) 
        {
            echo $ex;    
        }
    }

        /**
     * Busca um registro de serviço com base no ID.
     *
     * @param string $id O ID do serviço a ser buscado
     */
    function buscarServico($id)
    {

        $dadosServico = array("id" => "teste");

        try
        {
            global $conectar;
            $script = "SELECT idServico, nome, descricao, idCategoria, status FROM servico WHERE idServico = :id";
    
            $executarSelect = $conectar->prepare($script);
            $executarSelect->bindParam(':id', $id, PDO::PARAM_INT);
            $executarSelect->execute();
            $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
           
            foreach ($resultado as $dados)
            {
                $dadosServico["idServico"] = $dados["idServico"];
                $dadosServico["nome"] = $dados["nome"];
                $dadosServico["descricao"] = $dados["descricao"];
                $dadosServico["idCategoria"] = $dados["idCategoria"];
                $dadosServico["status"] = $dados["status"];
            }
            

            echo json_encode($dadosServico);

        }
        catch (Exception $ex) 
        {
            echo "Erro - Não foi possível carregar os dados do servico. " + $ex;
            console.log($ex);
        }
    }
?>