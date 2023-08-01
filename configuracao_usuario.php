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
    $tipoTransacao = $_POST['tipoTransacao'];

    if ($tipoTransacao == "carregarDadosUsuario")
    {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $status = $_POST['status']; 
        $limite = $_POST['limite'];
        carregarDadosUsuario($id, $nome, $status, $limite);
    }
    else if ($tipoTransacao == "carregarDadosUsuarioSelect")
    {
        $idusuario = isset($_SESSION["id_usuario"]) ? filter_var($_SESSION["id_usuario"], FILTER_SANITIZE_NUMBER_INT) : null;
        carregarDadosUsuarioSelect($idusuario);
    }
    else if ($tipoTransacao == "buscarUsuario")
    {
        $idUsuario = $_POST['id'];

        carregarUsuario($idUsuario);
    }
    else if ($tipoTransacao == "cadastroUsuario")
    {
        $vid = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);
        $vnome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
        $vusuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
        $vemail = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $vacessoCliente = filter_input(INPUT_POST, 'acessoCliente', FILTER_SANITIZE_STRING);
        $vacessoSeguro = filter_input(INPUT_POST, 'acessoSeguro', FILTER_SANITIZE_STRING);
        $vacessoUsuario = filter_input(INPUT_POST, 'acessoUsuario', FILTER_SANITIZE_STRING);
        $vacessoServico = filter_input(INPUT_POST, 'acessoServico', FILTER_SANITIZE_STRING);
        $vacessoAgendamento = filter_input(INPUT_POST, 'acessoAgendamento', FILTER_SANITIZE_STRING);
        $vacessoCotacao = filter_input(INPUT_POST, 'acessoCotacao', FILTER_SANITIZE_STRING);
        $vacessoConfiguracao = filter_input(INPUT_POST, 'acessoConfiguracao', FILTER_SANITIZE_STRING);
        $vsenha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
        $vativo = filter_input(INPUT_POST, 'ativo', FILTER_SANITIZE_STRING);

        if (empty($vid))
        {
            if (empty($vnome) == false && empty($vusuario) == false && empty($vsenha) == false)
            {
                inserirUsuario($vnome, $vusuario, $vemail, $vacessoCliente, $vacessoSeguro, $vacessoUsuario, $vacessoServico, $vacessoAgendamento, $vacessoCotacao, $vacessoConfiguracao, $vativo, $vsenha);
            }
            else
            {
                echo "Preencha os campos obrigatórios e o campo senha.";
            }
        }
        else
        {
            if (empty($vnome) == false && empty($vusuario) == false)
            {
                atualizarUsuario($vid, $vnome, $vusuario, $vemail, $vacessoCliente, $vacessoSeguro, $vacessoUsuario, $vacessoServico, $vacessoAgendamento, $vacessoCotacao, $vacessoConfiguracao, $vativo, $vsenha);
            }
            else
            {
                echo "Preencha os campos obrigatórios para atualizar os dados.";
            }
        }
    }

    /**
    * Carrega os dados dos usuários e exibe na tabela.
    */
    function carregarDadosUsuario($id, $nome, $status, $limite)
    {
        try
        {
            global $conectar;
    
            $script = "SELECT id, nome, usuario, ativo, email FROM usuarios WHERE ativo = :status";
            $script .= empty($id) ? '' : " AND id = :id" ;
            $script .= empty($nome) ? '' : " AND nome like :nome" ;
            $script .= empty($limite) ? '' : " LIMIT :limite" ;
    
            $stmt = $conectar->prepare($script);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    
            if (!empty($id)) {
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            }
    
            if (!empty($nome)) {
                $nome = "%$nome%";
                $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            }
    
            if (!empty($limite)) {
                $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            }
    
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if(count($resultado) > 0)
            {
                foreach ($resultado as $dados)
                {
                    echo "
                    <tr>
                        <td>".htmlspecialchars($dados["id"])."</td>
                        <td>".htmlspecialchars($dados["nome"])."</td>
                        <td>".htmlspecialchars($dados["usuario"])."</td>
                        <td>".htmlspecialchars($dados["ativo"])."</td>
                        <td>".htmlspecialchars($dados["email"])."</td>
                        <td>
                            <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modalUsuario' data-id='".htmlspecialchars($dados["id"])."'>
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
            echo "Erro - Não foi possível carregar os usuários. " . $ex->getMessage();
        }
    }

      /**
    * Carrega os dados dos usuários e exibe na tabela.
    */
    function carregarDadosUsuarioSelect($idusuario)
    {
        try
        {
            global $conectar;
    
            $script = "SELECT id, nome FROM usuarios WHERE ativo = 'A' AND id <> $idusuario";
            $stmt = $conectar->prepare($script);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if(count($resultado) > 0)
            {
                foreach ($resultado as $dados)
                {
                    echo " <option value=".htmlspecialchars($dados["id"]).">".htmlspecialchars($dados["nome"])."</option>
                    ";
                }       
            } 
        }
        catch (Exception $ex) 
        {
            echo "Erro - Não foi possível carregar os usuários. " . $ex->getMessage();
        }
    }


    function carregarUsuario($idUsuario)
    {
        try
        {
            $dadosUsuario = [];

            global $conectar;
    
            $script = "SELECT nome, usuario, email, acessoCliente, acessoSeguro, acessoUsuario, acessoServico, acessoAgendamento, acessoCotacao, acessoConfiguracao, ativo
                        FROM usuarios WHERE id = :idUsuario;
                        ";

            $stmt = $conectar->prepare($script);
            $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
    
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            foreach ($resultado as $dados)
            {
                $dadosUsuario["nome"] = htmlspecialchars($dados["nome"]);
                $dadosUsuario["email"] = htmlspecialchars($dados["usuario"]);
                $dadosUsuario["usuario"] = htmlspecialchars($dados["email"]);
                $dadosUsuario["acessoCliente"] = $dados["acessoCliente"] == 1 ? true : false;
                $dadosUsuario["acessoSeguro"] = $dados["acessoSeguro"] == 1 ? true : false;
                $dadosUsuario["acessoUsuario"] = $dados["acessoUsuario"] == 1 ? true : false;
                $dadosUsuario["acessoServico"] = $dados["acessoServico"] == 1 ? true : false;
                $dadosUsuario["acessoAgendamento"] = $dados["acessoAgendamento"] == 1 ? true : false;
                $dadosUsuario["acessoCotacao"] = $dados["acessoCotacao"] == 1 ? true : false;
                $dadosUsuario["acessoConfiguracao"] = $dados["acessoConfiguracao"] == 1 ? true : false;
                $dadosUsuario["ativo"] = $dados["ativo"] == "A" ? true : false;
            }
    
            echo json_encode($dadosUsuario);
        }
        catch (Exception $ex) 
        {
            echo "Erro - Não foi possível carregar os dados do usuário. " . $ex->getMessage();
        }
    }

        /**
     * Valida a duplicidade de usuário.
     *
     * @param string $vusuario Nome do usuário
     * @return bool Retorna true se o categoria já existir, caso contrário retorna false
     */
    function validarDuplicidadeUsuario($vid, $vusuario)
    {
        try
        {
            global $conectar;
            $script = "SELECT id FROM usuarios WHERE UPPER(usuario) = UPPER(:vusuario) AND id <> :vid";

            $stmt = $conectar->prepare($script);
            $stmt->bindParam(':vusuario', $vusuario, PDO::PARAM_STR);
            $stmt->bindParam(':vid', $vid, PDO::PARAM_INT);
            $stmt->execute();

            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    function inserirUsuario($vnome,$vusuario, $vemail, $vacessoCliente, $vacessoSeguro, $vacessoUsuario, $vacessoServico, $vacessoAgendamento, $vacessoCotacao, $vacessoConfiguracao, $vativo, $vsenha)
    {
        try
        {
            global $conectar;

            if (validarDuplicidadeUsuario(0, $vusuario) == false)
            {
                $script = "INSERT INTO usuarios(nome, usuario, email, acessoCliente, acessoSeguro, acessoUsuario, acessoServico, acessoAgendamento, acessoCotacao, acessoConfiguracao, ativo, senha) 
                    VALUES (:vnome, :vusuario, :vemail, :vacessoCliente, :vacessoSeguro, :vacessoUsuario, :vacessoServico, :vacessoAgendamento, :vacessoCotacao, :vacessoConfiguracao, :vativo, MD5(:vsenha))";

                $executarInsert = $conectar->prepare($script);
                $executarInsert->bindParam(':vnome', $vnome, PDO::PARAM_STR);
                $executarInsert->bindParam(':vusuario', $vusuario, PDO::PARAM_STR);
                $executarInsert->bindParam(':vemail', $vemail, PDO::PARAM_STR);
                $executarInsert->bindParam(':vacessoCliente', $vacessoCliente, PDO::PARAM_STR);
                $executarInsert->bindParam(':vacessoSeguro', $vacessoSeguro, PDO::PARAM_STR);
                $executarInsert->bindParam(':vacessoUsuario', $vacessoUsuario, PDO::PARAM_STR);
                $executarInsert->bindParam(':vacessoServico', $vacessoServico, PDO::PARAM_STR);
                $executarInsert->bindParam(':vacessoAgendamento', $vacessoAgendamento, PDO::PARAM_STR);
                $executarInsert->bindParam(':vacessoCotacao', $vacessoCotacao, PDO::PARAM_STR);
                $executarInsert->bindParam(':vacessoConfiguracao', $vacessoConfiguracao, PDO::PARAM_STR);
                $executarInsert->bindParam(':vativo', $vativo, PDO::PARAM_STR);
                $executarInsert->bindParam(':vsenha', $vsenha, PDO::PARAM_STR);
                $executarInsert->execute();

                echo "Usuário cadastrado com sucesso.";
            }
            else
            {
                echo "Usuário Já cadastrado.";
            }
        }
        catch (Exception $ex) 
        {
            echo $ex;    
        }
    }

    function atualizarUsuario($vid, $vnome,$vusuario, $vemail, $vacessoCliente, $vacessoSeguro, $vacessoUsuario, $vacessoServico, $vacessoAgendamento, $vacessoCotacao, $vacessoConfiguracao, $vativo, $vsenha)
    {
        try
        {
            global $conectar;

            if (validarDuplicidadeUsuario($vid, $vusuario) == false)
            {
                $script = "UPDATE usuarios SET 
                            nome = :vnome, 
                            usuario = :vusuario, 
                            email = :vemail, 
                            acessoCliente = :vacessoCliente, 
                            acessoSeguro = :vacessoSeguro, 
                            acessoUsuario = :vacessoUsuario, 
                            acessoServico = :vacessoServico, 
                            acessoAgendamento = :vacessoAgendamento,
                            acessoCotacao = :vacessoCotacao,  
                            acessoConfiguracao = :vacessoConfiguracao, 
                            ativo = :vativo";
                
                $script .= empty($vsenha) == false ? ", senha = MD5(:vsenha), redefinir = 1" : "" ;
                $script .= " WHERE id = :vid";

                $stmt = $conectar->prepare($script);
                $stmt->bindParam(':vnome', $vnome, PDO::PARAM_STR);
                $stmt->bindParam(':vusuario', $vusuario, PDO::PARAM_STR);
                $stmt->bindParam(':vemail', $vemail, PDO::PARAM_STR);
                $stmt->bindParam(':vacessoCliente', $vacessoCliente, PDO::PARAM_STR);
                $stmt->bindParam(':vacessoSeguro', $vacessoSeguro, PDO::PARAM_STR);
                $stmt->bindParam(':vacessoUsuario', $vacessoUsuario, PDO::PARAM_STR);
                $stmt->bindParam(':vacessoServico', $vacessoServico, PDO::PARAM_STR);
                $stmt->bindParam(':vacessoAgendamento', $vacessoAgendamento, PDO::PARAM_STR);
                $stmt->bindParam(':vacessoCotacao', $vacessoCotacao, PDO::PARAM_STR);
                $stmt->bindParam(':vacessoConfiguracao', $vacessoConfiguracao, PDO::PARAM_STR);
                $stmt->bindParam(':vativo', $vativo, PDO::PARAM_STR);
                $stmt->bindParam(':vid', $vid, PDO::PARAM_INT);
                if(!empty($vsenha)) {
                    $stmt->bindParam(':vsenha', $vsenha, PDO::PARAM_STR);
                }
                $stmt->execute();

                echo "Cadastrado atualizado com sucesso.";
            }
            else
            {
                echo "Cadastro já existente";
            }
        }
        catch (Exception $ex) 
        {
            echo $ex;    
        }
    }

?>