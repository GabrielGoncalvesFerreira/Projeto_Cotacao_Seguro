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
    $id = "";
    $nome = "";
    $descricao = "";
    $status = "";
    $tipoTransacao = filter_input(INPUT_POST, 'tipoTransacao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
 
    if ($tipoTransacao == "cadastrarSeguro") //Execução
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        validarTipoTransacao($id, $nome, $descricao, $status);
    }
    else if ($tipoTransacao == "consultarSeguro") //Consulta
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
        $limite = filter_input(INPUT_POST, 'limite', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        carregarDadosSeguro($id, $nome, $status, $limite);
    }
    else if ($tipoTransacao == "buscarRegistro")
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        buscarRegistro($id);
    }


    /**
     * Valida o tipo de transação e executa a ação correspondente.
     *
     * @param string $id O ID do seguro
     * @param string $nome O nome do seguro
     * @param string $descricao A descrição do seguro
     * @param string $status O status do seguro
     */
    function validarTipoTransacao($id, $nome, $descricao, $status)
    {
        if (empty($nome) == false && empty($descricao) == false)
        {
            if (empty($id))
            {
                inserirSeguro($nome, $descricao, $status);
            }
            else
            {
                atualizarSeguro($id, $nome, $descricao, $status);
            }
        }
        else
        {
            echo "Preencha os campos para continuar.";
        }
    }

    /**
     * Valida a duplicidade do nome do seguro.
     *
     * @param string $vnome O nome do seguro a ser validado
     * @return bool Retorna true se o seguro já existir, caso contrário retorna false
     */
    function validarDuplicidade($vnome)
    {
        try
        {
            global $conectar;
            $script = "SELECT id FROM seguro WHERE nome = UPPER(:nome)";

            $executarSelect = $conectar->prepare($script);
            $executarSelect->bindParam(':nome', $vnome, PDO::PARAM_STR);
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
     * Atualiza um seguro existente.
     *
     * @param string $vId O ID do seguro a ser atualizado
     * @param string $vnome O nome do seguro
     * @param string $vDescricao A descrição do seguro
     * @param string $vStatus O status do seguro
     */
    function atualizarSeguro($vId, $vnome, $vDescricao, $vStatus)
    {
        try
        {
            global $conectar;
            $script = "UPDATE seguro SET nome = :nome, descricao = :descricao, status = :status WHERE id = :id";

            $executarUpdate = $conectar->prepare($script);
            $executarUpdate->bindParam(':id', $vId, PDO::PARAM_STR);
            $executarUpdate->bindParam(':nome', $vnome, PDO::PARAM_STR);
            $executarUpdate->bindParam(':descricao', $vDescricao, PDO::PARAM_STR);
            $executarUpdate->bindParam(':status', $vStatus, PDO::PARAM_STR);
            $executarUpdate->execute();

            echo "Seguro atualizado com sucesso.";
        }
        catch (Exception $ex) 
        {
            echo $ex;    
        }
    }

    /**
     * Insere um novo seguro no banco de dados.
     *
     * @param string $vnome O nome do seguro
     * @param string $vDescricao A descrição do seguro
     * @param string $vStatus O status do seguro
     */
    function inserirSeguro($vnome, $vDescricao, $vStatus)
    {
        try
        {
            global $conectar;

            if (validarDuplicidade($vnome) == false)
            {
                $script = "INSERT INTO seguro(nome, descricao, status) 
                VALUES (:nome, :descricao, :status)";

                $executarInsert = $conectar->prepare($script);
                $executarInsert->bindParam(':nome', $vnome, PDO::PARAM_STR);
                $executarInsert->bindParam(':descricao', $vDescricao, PDO::PARAM_STR);
                $executarInsert->bindParam(':status', $vStatus, PDO::PARAM_STR);
                $executarInsert->execute();

                echo "Seguro cadastrado com sucesso.";
            }
            else
            {
                echo "Seguro Já cadastrado.";
            }
        }
        catch (Exception $ex) 
        {
            echo $ex;    
        }
    }

    /**
     * Carrega os dados dos seguros e exibe na tabela.
     */
    function carregarDadosSeguro($id, $nome, $status, $limite)
    {
        try
        {
            global $conectar;
            $script = "SELECT id, nome, descricao, status FROM seguro WHERE status = :status";
            $script .= empty($id) ? '' : " AND id = :id" ;
            $script .= empty($nome) ? '' : " AND nome like :nome" ;
            $script .= empty($limite) ? '' : " LIMIT :limite" ;

            $executarSelect = $conectar->prepare($script);
            $executarSelect->bindParam(':status', $status, PDO::PARAM_STR);
            if (!empty($id)) {
                $executarSelect->bindParam(':id', $id, PDO::PARAM_STR);
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

            if($total)
            {
                foreach ($resultado as $dados)
                {
                    
                    echo "
                    <tr>
                        <td>{$dados["id"]}</td>
                        <td>{$dados["nome"]}</td>
                        <td>{$dados["descricao"]}</td>
                        <td>{$dados["status"]}</td>
                        <td>
                            <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modalSeguradora' data-titulo='Editar Seguradora' data-id='{$dados["id"]}' onclick='buscarRegistro();'>
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
            echo "Erro - Não foi possível carregar os dados de seguro.";
        }
    }

    /**
     * Busca um registro de seguro com base no ID.
     *
     * @param string $id O ID do seguro a ser buscado
     */
    function buscarRegistro($id)
    {

        $dadosSeguro = array();

        try
        {
            global $conectar;
            $script = "SELECT id, nome, descricao, status FROM seguro WHERE id = {$id}";

            $executarSelect = $conectar->query($script);
            $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
           
            foreach ($resultado as $dados)
            {
                $dadosSeguro["id"] = $dados["id"];
                $dadosSeguro["nome"] = $dados["nome"];
                $dadosSeguro["descricao"] = $dados["descricao"];
                $dadosSeguro["status"] = $dados["status"];
            }
                       

            echo json_encode($dadosSeguro);

        }
        catch (Exception $ex) 
        {
            echo "Erro - Não foi possível carregar os dados de seguro.";
        }
    }

?>