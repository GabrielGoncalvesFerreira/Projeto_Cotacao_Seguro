<?php

// pega dados do arquivo tarefas.txt e coloca na tela
if (file_exists("configuracao/conf.config")) {
    $lista = file_get_contents("configuracao/conf.config");
    $lista_array = explode("\n", $lista);
    echo json_encode($lista_array);
} else {
    $lista = null;
}

?>