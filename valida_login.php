<?php
    // Conexão com o banco de dados
    require_once "Connection.php";

    // Inicia a sessão
    session_start();

    $conexao = new Connection();
    $conn = $conexao->conectar();

    // Recupera o login
    $usuario = isset($_POST["usuario"]) ? addslashes(trim($_POST["usuario"])) : FALSE;
    // Recupera a senha, a criptografando em MD5
    $senha = isset($_POST["senha"]) ? md5(trim($_POST["senha"])) : FALSE;

    // Usuário não forneceu a senha ou o login
    if(!$usuario || !$senha)
    {
        $erro =  "Você deve digitar seu usuário e senha!";
    }

    /**
    * Executa a consulta no banco de dados.
    * Caso o número de linhas retornadas seja 1 o login é válido,
    * caso 0, inválido.
    */
    $SQL = "SELECT id, nome, usuario, senha, email, acessoCliente, acessoSeguro, acessoUsuario, acessoServico, acessoAgendamento, acessoConfiguracao, acessoCotacao, redefinir
    FROM usuarios
    WHERE usuario = :usuario AND ativo = 'A'";
    $stmt = $conn->prepare($SQL);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $total = count($result);

    // Caso o usuário tenha digitado um login válido o número de linhas será 1..
    if($total)
    {
        // Obtém os dados do usuário, para poder verificar a senha e passar os demais dados para a sessão
        $dados = $result[0];

        // Agora verifica a senha
        $senha_correta = $senha === $dados["senha"];
        if($senha_correta)
        {
            if ($dados["redefinir"] == 0)
            {
                // TUDO OK! Agora, passa os dados para a sessão e redireciona o usuário
                $_SESSION["id_usuario"] = $dados["id"];
                $_SESSION["nome_usuario"] = stripslashes($dados["nome"]);
                $_SESSION["tempo"] = time();
                $_SESSION["email"]= $dados["email"];
                $_SESSION["acessoCliente"] = $dados["acessoCliente"];
                $_SESSION["acessoSeguro"] = $dados["acessoSeguro"];
                $_SESSION["acessoUsuario"] = $dados["acessoUsuario"];
                $_SESSION["acessoServico"] = $dados["acessoServico"];
                $_SESSION["acessoCotacao"]  = $dados["acessoCotacao"];
                $_SESSION["acessoAgendamento"] = $dados["acessoAgendamento"];
                $_SESSION["acessoConfiguracao"] = $dados["acessoConfiguracao"];
                header("Location: index.php");
                exit;
            }
            else if ($dados["redefinir"] == 1) {
                $_SESSION["id_usuario"] = $dados["id"];
                $_SESSION["nome_usuario"] = stripslashes($dados["nome"]);
                $_SESSION["tempo"] = time();
                header("Location: redefinir.php");
            }
            else
            {
                $erro = "Não possível realizar o acesso!";
            }
        }
        // Senha inválida
        else
        {
            $erro = "Senha inválida!";
        }
    }
    // Login inválido
    else
    {
        $erro =  "Usuário não encontrado!";
    }

?>