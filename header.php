<?php
    // Inicia a sessão
    session_start();

    // Verifica se o usuário está logado
    if (!isset($_SESSION["id_usuario"]) || empty($_SESSION["id_usuario"])) {
        // Redireciona para a página de login ou exibe uma mensagem de erro
        header("Location: login.php");
        exit;
    }
    else if (time() - $_SESSION["tempo"] > 7200) { // sessão iniciada há mais de 30 minutos
        session_regenerate_id(true); // muda o ID da sessão para o ID corrente e invalidar o ID antigo
        //$_SESSION["tempo"] = time();  // atualiza o tempo de criação da sessão
        header("Location: login.php");
    }

    // Recupera os parâmetros da sessão
    $idUsuario = $_SESSION["id_usuario"];
    $nomeUsuario = $_SESSION["nome_usuario"];
    $emailUsuario = $_SESSION["email"];
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Adicionando a logo na aba do site -->
        <link rel="icon" href="/imagem/simbolo-logo.png" type="image/png">

        <!-- Font Awesome CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" type="text/css" href="css/estilo_header.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="css/estilo_agendamento.css" media="screen" />
        <script type="text/javascript" src="extensao/jquery-3.7.0.min.js "></script> 
        <script type="text/javascript" src="js/configuracaoHeader.js"></script>
        <script type="text/javascript" src="js/configuracaoAcesso.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="sweetalert2.all.min.js"></script>
        <script>carregarDadosAcesso();</script>
    </head>

    <body>
        <nav class="navbar navbar-fixed-top border-bottom border-bottom-dark">
            <div class="container-fluid d-flex justify-content-between" style="padding: 10px 80px">
                <a class="navbar-brand" href="#">
                    <img src="/imagem/logo.png" alt="Logo da Empresa" height="40" class="logo">
                </a>

                <div class="dropdown d-flex align-items-center">
                    <div class="user-info">    
                        <a class="nav-link dropdown-toggle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo "<span id='usuarioLogado' style='font-weight: bold; color: #167FA1'>$nomeUsuario</span>"; ?>&nbsp; <i class="bi bi-person-circle" style="color: #167FA1"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>


        <div id="loadingSpinner" class="spinner-container">
            <div class="spinner"></div>
            <p class="loading-text">Aguarde...</p>
        </div>

        <div class="row" style="height: 100%">
            <div class="col-2">
                <div class="sidebar menu">
                    <a class="nav-link" href="index.php">
                        <i class="bi bi-bar-chart"></i>
                        <span class="d-none d-lg-block">Home</span>
                    </a>
                </div>
            </div>
            <div class="col-10">
            <div id="agendamentos">

            </div>

            <div class="container">
