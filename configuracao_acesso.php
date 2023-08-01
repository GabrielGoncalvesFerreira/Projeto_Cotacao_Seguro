<?php

    // Conexão com o banco de dados
    require_once "Connection.php";

    // Inicia a sessão
    session_start();

    $botoesAcesso = '';

    $acessoCliente = isset($_SESSION["acessoCliente"]) ? filter_var($_SESSION["acessoCliente"], FILTER_SANITIZE_NUMBER_INT) : null;
    $acessoSeguro = isset($_SESSION["acessoSeguro"]) ? filter_var($_SESSION["acessoSeguro"], FILTER_SANITIZE_NUMBER_INT) : null;
    $acessoUsuario = isset($_SESSION["acessoUsuario"]) ? filter_var($_SESSION["acessoUsuario"], FILTER_SANITIZE_NUMBER_INT) : null;
    $acessoServico = isset($_SESSION["acessoServico"]) ? filter_var($_SESSION["acessoServico"], FILTER_SANITIZE_NUMBER_INT) : null;
    $acessoAgendamento = isset($_SESSION["acessoAgendamento"]) ? filter_var($_SESSION["acessoAgendamento"], FILTER_SANITIZE_NUMBER_INT) : null;
    $acessoCotacao = isset($_SESSION["acessoCotacao"]) ? filter_var($_SESSION["acessoCotacao"], FILTER_SANITIZE_NUMBER_INT) : null;
    $acessoConfiguracao = isset($_SESSION["acessoConfiguracao"]) ? filter_var($_SESSION["acessoConfiguracao"], FILTER_SANITIZE_NUMBER_INT) : null;

    if ($acessoCliente == 1)
    {
        $botoesAcesso .= '<a class="nav-link" href="cliente.php">
                            <i class="bi bi-person-lines-fill"></i>
                            <span class="d-none d-lg-block">Cliente</span>
                        </a>';
    }
    if ($acessoSeguro == '1')
    {
        $botoesAcesso .= '<a class="nav-link" href="seguro.php">
                            <i class="bi bi-building"></i>
                            <span class="d-none d-lg-block">Seguro</span>
                        </a>';
    }
    if ($acessoUsuario == 1)
    {
        $botoesAcesso .= '<a class="nav-link" href="usuario.php">
                            <i class="bi bi-person-bounding-box"></i>
                            <span class="d-none d-lg-block">Usuário</span>
                        </a>';
    }
    if ($acessoServico == 1)
    {
        $botoesAcesso .= '<a class="nav-link" href="servico.php">
                            <i class="bi bi-briefcase"></i>
                            <span class="d-none d-lg-block">Serviço</span>
                        </a>';
    }
    if ($acessoAgendamento == 1)
    {
        $botoesAcesso .= '<a class="nav-link" href="agendamento.php">
                            <i class="bi bi-calendar-week"></i>
                            <span class="d-none d-lg-block">Agendamento</span>
                        </a>';
    }
    if ($acessoCotacao == 1)
    {
        $botoesAcesso .= '<a class="nav-link" href="gerar_cotacao.php">
                            <i class="bi bi-envelope"></i>
                            <span class="d-none d-lg-block">Gerar Cotação</span>
                        </a>';
    }
    if ($acessoConfiguracao == 1)
    {
        $botoesAcesso .= '<a class="nav-link" href="configuracoes.php">
                            <i class="bi bi-gear"></i>
                            <span class="d-none d-lg-block">Configuração</span>
                        </a>';
    }

    echo $botoesAcesso;
?>
