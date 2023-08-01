<?php
    // Conexão com o banco de dados
    require_once "Connection.php";

    $conexao = new Connection();
    $conectar = $conexao->conectar();

    // Recupera o login
    $idusuario = $_SESSION["id_usuario"];
    // Recupera a senha
    $senha = $_POST["novaSenha"];
    $novaSenha = $_POST["repitaSenha"];

    // Usuário não forneceu a senha ou o login
    if(!$senha || !$novaSenha)
    {
        $erro =  "Você deve digitar as senhas para continuar!";
    }
    elseif($senha != $novaSenha)
    {
        $erro =  "As senhas devem ser identicas!";
    }
    elseif(strlen($senha) < 8)
    {
        $erro =  "A senha deve possuir no mínimo 8 caracteres!";
    }

    if (empty($erro))
    {
        try
        {
            $script = "UPDATE usuarios SET 
                        senha = :senha,
                        redefinir = 0     
                        WHERE id = :idusuario
                        AND ativo = 'A'       
                        AND redefinir = 1               
                        ";
            
            $executar= $conectar->prepare($script);
            $executar->bindParam(':senha', password_hash($senha, PASSWORD_BCRYPT));
            $executar->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
            $executar->execute();

            if(($_SESSION["id_usuario"]) || empty($_SESSION["id_usuario"])) {
                session_destroy();
                header("Location: login.php");
                exit();
            } 
            else
            {
                header("Location: login.php");
                exit();
            }

        }
        catch (Exception $ex) 
        {
            error_log($ex->getMessage());
            $erro = "Ocorreu um erro. Por favor, tente novamente mais tarde.";    
        }
    }

?>
