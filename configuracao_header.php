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

    $vcodigousuario = $_SESSION["id_usuario"];;

    carregarDadosAgendamentosTopo();

    function carregarDadosAgendamentosTopo()
    {
        try
        {
            global $conectar;
            global $vcodigousuario;

            $script = "SELECT a.*, 
            (SELECT COUNT(*) FROM agendamentosParticipante p WHERE a.codigoagendamento = p.idAgendamento AND p.idUsuario = '$vcodigousuario') AS participante 
            FROM agendamentos a 
            WHERE a.status IN ('A','P') AND (a.codigousuario = '$vcodigousuario' OR 
            a.codigoagendamento IN (SELECT idAgendamento FROM agendamentosParticipante WHERE idUsuario = '$vcodigousuario')) 
            ORDER BY a.data, a.hora LIMIT 10";

            $executarSelect = $conectar->query($script);
            $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
            $total = count($resultado);

            if($total)
            {
                foreach ($resultado as $dados)
                {
                    $agendamento = new DateTime($dados["data"]);
                    $agendamento->setTime(0, 0, 0);
                    $hoje = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
                    $hoje->setTime(0, 0, 0);
                    $agendamentoHora = DateTime::createFromFormat('H:i:s', $dados["hora"]);
                    $horaAtual = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
                    $agendamentoHoraStr = $agendamentoHora->format('H:i:s');
                    $horaAtualStr = $horaAtual->format('H:i:s');
                    $classe = '';
                    $icone = '';

                    if ($agendamento->format('Y-m-d') < $hoje->format('Y-m-d')) {
                        $classe = "atrasado";
                        $icone = '<i class="bi bi-exclamation-diamond-fill"></i>&nbsp;';
                    }
                    else if ($agendamento->format('Y-m-d') == $hoje->format('Y-m-d') && $agendamentoHoraStr < $horaAtualStr) {
                        $classe = "atrasado";
                        $icone = '<i class="bi bi-exclamation-diamond-fill"></i>&nbsp;';
                    }
                    else if ($agendamento->format('Y-m-d') == $hoje->format('Y-m-d')) {
                        $classe = 'pendente';
                        $icone = '<i class="bi bi-clock"></i>&nbsp;';
                    }
                    else if ($dados["status"] == 'P') {
                        $classe = 'prorrogado';
                        $icone = '<i class="bi bi-clock"></i>&nbsp;';
                    }
                    else
                    {
                        $classe = 'proximo';
                        $icone = '<i class="bi bi-clock"></i>&nbsp;';
                    }

                    if ($dados["participante"]) {
                        $icone = '<i class="bi bi-people-fill"></i>&nbsp;'; // Ícone de grupo
                    }

                    echo "<div class='agendamento {$classe}' data-bs-toggle='modal' data-bs-target='#modalAgendamento' data-id='{$dados["codigoagendamento"]}'>
                            $icone
                            <span class='hora'>{$dados["hora"]}</span>
                            <span class='dia'>{$dados["data"]}</span>              
                          </div>";
                } 
            }       
        }
        catch (Exception $ex) 
        {
            echo "Erro - Não foi possível carregar os dados de seguro.";
        }
    }

?>