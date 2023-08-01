<?php
    session_start();

    $token = md5(session_id());
    if(($_SESSION["id_usuario"]) || empty($_SESSION["id_usuario"])) {
    // limpe tudo que for necessário na saída.
    // Eu geralmente não destruo a seção, mas invalido os dados da mesma
    // para evitar algum "necromancer" recuperar dados. Mas simplifiquemos:
    session_destroy();
    header("Location: login.php");
    exit();
    } 
    else
    {
        header("Location: login.php");
        exit();
    }
?>