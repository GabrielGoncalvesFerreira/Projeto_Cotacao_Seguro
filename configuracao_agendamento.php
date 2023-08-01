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
  $tipoTransacao = filter_input(INPUT_POST, 'tipoTransacao', FILTER_SANITIZE_SPECIAL_CHARS);

  if ($tipoTransacao == "consultarTipo") //Execução
  {
      carregarDadosTipo();
  }
  else if ($tipoTransacao == "cadastrarTipo")
  {
      $idTipo = filter_input(INPUT_POST, 'idTipo', FILTER_SANITIZE_NUMBER_INT);
      $nomeTipo = filter_input(INPUT_POST, 'nomeTipo', FILTER_SANITIZE_SPECIAL_CHARS);
      $descricaoTipo = filter_input(INPUT_POST, 'descricaoTipo', FILTER_SANITIZE_SPECIAL_CHARS);

      if (!empty($nomeTipo) && !empty($descricaoTipo))
      {
          if (empty($idTipo))
          {
              inserirTipo($nomeTipo,  $descricaoTipo);
          }
          else
          {
              atualizarTipo($idTipo, $nomeTipo,  $descricaoTipo);
          }
      }
      else
      {
          echo "Preencha os dados para continuar com o processo";
      }
  }
  else if ($tipoTransacao == "carregarDadosClientes")
  {
        carregarDadosClientes();
  }
  else if ($tipoTransacao == "carregarAgendamentos")
  {
        $data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_SPECIAL_CHARS);
        $vcodigousuario = isset($_SESSION["id_usuario"]) ? filter_var($_SESSION["id_usuario"], FILTER_SANITIZE_NUMBER_INT) : null;
        carregarDadosAgendamento($data, $vcodigousuario);
  }
  else if ($tipoTransacao == "buscarDadosAgendamento")
  {
        $idAgendamento = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        buscarRegistroAgendamento($idAgendamento);
  }
  else if ($tipoTransacao == "carregarDadosTipos")
  {
        carregarDadosTipos();
  }
  else if ($tipoTransacao == "buscarAgendamentosPendetes")
  {
        $vcodigousuario = isset($_SESSION["id_usuario"]) ? filter_var($_SESSION["id_usuario"], FILTER_SANITIZE_NUMBER_INT) : null;
        buscarAgendamentosPendetes($vcodigousuario);
  }
  else if ($tipoTransacao == "deletarAgendamento")
  {
        $vidAgendamento = filter_input(INPUT_POST, 'idAgendamento', FILTER_SANITIZE_NUMBER_INT);
        deletarAgendamento($vidAgendamento);
  }
  else if ($tipoTransacao == "cadastrarAgendamento")
  {
        $vidAgendamento = filter_input(INPUT_POST, 'idAgendamento', FILTER_SANITIZE_NUMBER_INT);
        $vcodigocliente = filter_input(INPUT_POST, 'codigocliente', FILTER_SANITIZE_NUMBER_INT);
        $vcodigotipo = filter_input(INPUT_POST, 'codigotipo', FILTER_SANITIZE_NUMBER_INT);
        $vcodigousuario = isset($_SESSION["id_usuario"]) ? filter_var($_SESSION["id_usuario"], FILTER_SANITIZE_NUMBER_INT) : null;
        $vdata = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_SPECIAL_CHARS);
        $vhora = filter_input(INPUT_POST, 'hora', FILTER_SANITIZE_SPECIAL_CHARS);
        $vhoraFinal = filter_input(INPUT_POST, 'horaFinal', FILTER_SANITIZE_SPECIAL_CHARS);
        $vobservacao = filter_input(INPUT_POST, 'observacao', FILTER_SANITIZE_SPECIAL_CHARS);
        $vstatus = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
        $vArrayParticipantes = $_POST['participantes'];

        if (!empty($vcodigotipo) && !empty($vcodigousuario) && !empty($vdata) && !empty($vhora) && !empty($vhoraFinal) &&  !empty($vobservacao) &&  !empty($vstatus))
        {
            if (empty($vidAgendamento))
            {
                inserirAgendamento($vcodigocliente, $vcodigotipo, $vcodigousuario, $vdata, $vhora, $vhoraFinal, $vobservacao, $vstatus, $vArrayParticipantes);
            }
            else 
            {
                atualizarAgendamento($vidAgendamento ,$vcodigocliente, $vcodigotipo, $vcodigousuario, $vdata, $vhora, $vhoraFinal, $vobservacao, $vstatus, $vArrayParticipantes);
            }
        }
        else
        {
            echo "Preencha os dados para continuar com o processo";
        }
  }

    /**
     * Carrega os dados dos tipos e exibe na tabela.
    */
    function carregarDadosTipo()
    {
        try
        {
            global $conectar;
            $script = "SELECT idTipo, nome, descricao FROM tipos";

            $executarSelect = $conectar->query($script);
            $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
            $total = count($resultado);

            if($total > 0)
            {
                foreach ($resultado as $dados)
                {
                    echo "
                    <tr>
                        <td>{$dados["idTipo"]}</td>
                        <td>{$dados["nome"]}</td>
                        <td>{$dados["descricao"]}</td>
                        <td>
                            <button type='button' class='btn btn-primary' id='{$dados["idTipo"]}' onclick='pegarIdTipo(id);'>
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
     * Insere um novo tipo no banco de dados.
     *
     * @param string $vnome O nome do tipo
     * @param string $vDescricao A descrição do tipo
     */
    function inserirTipo($vnome, $vDescricao)
    {
        try
        {
            global $conectar;

            if (validarDuplicidadeTipo($vnome) == false)
            {
                $script = "INSERT INTO tipos(nome, descricao) 
                        VALUES ('$vnome','$vDescricao')";

                $executarInsert = $conectar->prepare($script);
                $executarInsert->execute();

                echo "Tipo cadastrado com sucesso.";
            }
            else
            {
                echo "Tipo Já cadastrado.";
            }
        }
        catch (Exception $ex) 
        {
            echo $ex;    
        }
    }

   /**
     * Atualiza um tipo existente.
     *
     * @param string $vId O ID do tipo a ser atualizado
     * @param string $vnome O nome do tipo
     * @param string $vDescricao A descrição do tipo
     */
    function atualizarTipo($vId, $vnome, $vDescricao)
    {
        try
        {
            global $conectar;

            if (validarDuplicidadeTipo($vnome) == false)
            {
                $script = "UPDATE tipos SET nome = '$vnome', descricao = '$vDescricao' WHERE idTipo = $vId";

                $executarUpdate = $conectar->prepare($script);
                $executarUpdate->execute();

                echo "Tipo atualizado com sucesso.";
            }
            else
            {
                echo "Tipo Já cadastrado.";
            }
        }
        catch (Exception $ex) 
        {
            echo $ex;    
        }
    }

    /**
     * Valida a duplicidade do nome da tipo.
     *
     * @param string $vnome O nome do tipo a ser validado
     * @return bool Retorna true se o tipo já existir, caso contrário retorna false
     */
    function validarDuplicidadeTipo($vnome)
    {
        try
        {
            global $conectar;
            $script = "SELECT idTipo FROM tipos WHERE nome = UPPER('$vnome')";

            $executarSelect = $conectar->query($script);
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
     * Carrega os dados dos clientes e exibe no select.
     */
    function carregarDadosClientes()
    {
        try
        {
            global $conectar;
            $script = "SELECT idCliente, nomeCliente, tipo, identificacao, origem, cidade, cep FROM clientes WHERE status = 'A'";

            $executarSelect = $conectar->query($script);
            $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
           
            foreach ($resultado as $dados)
            {
                echo "
                    <option value='{$dados["idCliente"]}'>{$dados["nomeCliente"]}</option>
                ";
            }        
        }
        catch (Exception $ex) 
        {
            echo "Erro - Não foi possível carregar os clientes cadastrados.";
        }
    }

      /**
     * Carrega os dados dos tipos e exibe no select.
     */
    function carregarDadosTipos()
    {
        try
        {
            global $conectar;
            $script = "SELECT idTipo, nome FROM tipos WHERE status = 'A'";

            $executarSelect = $conectar->query($script);
            $resultado = $executarSelect->fetchAll(PDO::FETCH_ASSOC);
           
            foreach ($resultado as $dados)
            {
                echo "
                    <option value='{$dados["idTipo"]}'>{$dados["nome"]}</option>
                ";
            }        
        }
        catch (Exception $ex) 
        {
            echo "Erro - Não foi possível carregar os clientes cadastrados.";
        }
    }


    function validarDuplicidadeAgendamento($idagendamento, $data, $hora, $horaFinal, $idusuario, $arrayParticipantes)
    {
        try
        {
            global $conectar;
    
            // Preparar a consulta SQL
            $stmt = $conectar->prepare("SELECT codigoagendamento FROM agendamentos WHERE data = :data 
            AND ((hora <= :hora AND horafinal > :hora) OR (hora < :horaFinal AND horafinal >= :horaFinal) OR (hora >= :hora AND horafinal <= :horaFinal)) AND codigoagendamento <> :idagendamento AND codigousuario = :idusuario");
    
            // Vincular os parâmetros à consulta preparada
            $stmt->bindParam(':data', $data);
            $stmt->bindParam(':hora', $hora);
            $stmt->bindParam(':horaFinal', $horaFinal);
            $stmt->bindParam(':idagendamento', $idagendamento);
            $stmt->bindParam(':idusuario', $idusuario);
    
            // Executar a consulta
            $stmt->execute();
    
            // Obter os resultados
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total = count($resultado);
    
            if($total)
            {
                return true;
            }
    
            // Verificar disponibilidade de todos os participantes
            if (is_array($arrayParticipantes)) {
                foreach($arrayParticipantes as $participante) {
                    $stmt = $conectar->prepare("SELECT codigoParticipante FROM agendamentosParticipante WHERE idAgendamento IN 
                    (SELECT codigoagendamento FROM agendamentos WHERE data = :data 
                    AND ((hora <= :hora AND horafinal > :hora) OR (hora < :horaFinal AND horafinal >= :horaFinal) OR (hora >= :hora AND horafinal <= :horaFinal)) AND codigoagendamento <> :idagendamento) AND idUsuario = :participante");
    
                    $stmt->bindParam(':data', $data);
                    $stmt->bindParam(':hora', $hora);
                    $stmt->bindParam(':horaFinal', $horaFinal);
                    $stmt->bindParam(':idagendamento', $idagendamento);
                    $stmt->bindParam(':participante', $participante);
    
                    $stmt->execute();
    
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $total = count($resultado);
    
                    if($total)
                    {
                        return true;
                    }
                }
            }
            
            return false;
        }
        catch (Exception $ex) 
        {
            return false;
        }
    }
    

    function inserirAgendamento($vcodigocliente, $vcodigotipo, $vcodigousuario, $vdata, $vhora, $vhoraFinal, $vobservacao, $vstatus, $arrayParticipantes)
    {
        try
        {
            global $conectar;
    
            // Iniciar transação
            $conectar->beginTransaction();
    
            // Verificar disponibilidade do usuário principal
            if (validarDuplicidadeAgendamento(0, $vdata, $vhora, $vhoraFinal, $vcodigousuario, $vcodigousuario) == false)
            {
                // Verificar disponibilidade de todos os participantes
                if (is_array($arrayParticipantes)) {
                    foreach($arrayParticipantes as $participante) {
                        if (validarDuplicidadeAgendamento(0, $vdata, $vhora, $vhoraFinal, $participante, $vcodigousuario) == true) {
                            echo "Um dos participantes está ocupado, verifique a agenda.";
                            // Reverter transação
                            $conectar->rollBack();
                            return;
                        }
                    }
                }
    
                $script = "INSERT INTO agendamentos(codigocliente, codigotipo, codigousuario, data, hora, horafinal, observacao, status) 
                        VALUES ('$vcodigocliente', '$vcodigotipo', '$vcodigousuario', '$vdata', '$vhora', '$vhoraFinal', '$vobservacao', '$vstatus')";
    
                $executarInsert = $conectar->prepare($script);
                $executarInsert->execute();
    
                // Obter o ID do último agendamento inserido
                $lastId = $conectar->lastInsertId();
    
                // Inserir cada participante na tabela codigoParticipante
                if (is_array($arrayParticipantes)) {
                    foreach($arrayParticipantes as $participante) {
                        $scriptParticipante = "INSERT INTO agendamentosParticipante(idagendamento, idusuario) 
                                VALUES ('$lastId', '$participante')";
    
                        $executarInsertParticipante = $conectar->prepare($scriptParticipante);
                        $executarInsertParticipante->execute();
                    }
                }
    
                // Confirmar transação
                $conectar->commit();
    
                echo "Agendamento cadastrado com sucesso.";
            }
            else
            {
                echo "Essa horário você estará ocupado, verifique a agenda.";
                // Reverter transação
                $conectar->rollBack();
            }
        }
        catch (Exception $ex) 
        {
            // Reverter transação em caso de erro
            $conectar->rollBack();
            echo $ex;    
        }
    } 

    function atualizarAgendamento($vidagendamento, $vcodigocliente, $vcodigotipo, $vcodigousuario, $vdata, $vhora, $vhoraFinal, $vobservacao, $vstatus, $arrayParticipantes)
    {
        try
        {
            global $conectar;
    
            // Iniciar transação
            $conectar->beginTransaction();
    
            // Verificar disponibilidade do usuário principal
            if (validarDuplicidadeAgendamento($vidagendamento, $vdata, $vhora, $vhoraFinal, $vcodigousuario, $vcodigousuario) == false)
            {
                // Verificar disponibilidade de todos os participantes
                if (is_array($arrayParticipantes)) {
                    foreach($arrayParticipantes as $participante) {
                        if (validarDuplicidadeAgendamento($vidagendamento, $vdata, $vhora, $vhoraFinal, $participante, $vcodigousuario) == true) {
                            echo "Um dos participantes está ocupado, verifique a agenda.";
                            // Reverter transação
                            $conectar->rollBack();
                            return;
                        }
                    }
                }
    
                $script = "UPDATE agendamentos SET 
                codigocliente='$vcodigocliente',
                codigotipo='$vcodigotipo',
                data=' $vdata',
                observacao='$vobservacao',
                codigousuario='$vcodigousuario',
                status='$vstatus',
                hora='$vhora',
                horafinal='$vhoraFinal' 
                WHERE codigoagendamento = $vidagendamento";
    
                $executarUpdate = $conectar->prepare($script);
                $executarUpdate->execute();
    
                // Excluir participantes existentes
                $scriptDelete = "DELETE FROM agendamentosParticipante WHERE idagendamento='$vidagendamento'";
                $executarDelete = $conectar->prepare($scriptDelete);
                $executarDelete->execute();
    
                // Inserir cada participante na tabela codigoParticipante
                if (is_array($arrayParticipantes)) {
                    foreach($arrayParticipantes as $participante) {
                        $scriptParticipante = "INSERT INTO agendamentosParticipante(idagendamento, idusuario) 
                                VALUES ('$vidagendamento', '$participante')";
    
                        $executarInsertParticipante = $conectar->prepare($scriptParticipante);
                        $executarInsertParticipante->execute();
                    }
                }
    
                // Confirmar transação
                $conectar->commit();
    
                echo "Agendamento atualizado com sucesso.";
            }
            else
            {
                echo "Essa horário você estará ocupado, verifique a agenda.";
                // Reverter transação
                $conectar->rollBack();
            }
        }
        catch (Exception $ex) 
        {
            // Reverter transação em caso de erro
            $conectar->rollBack();
            echo $ex;    
        }
    }
    

function deletarAgendamento($codigoAgenamento)
{
    try
    {
        global $conectar;

        // Iniciar transação
        $conectar->beginTransaction();

        // Deletar participantes associados ao agendamento
        $scriptParticipantes = "DELETE FROM agendamentosParticipante WHERE idagendamento = $codigoAgenamento";
        $executarDeleteParticipantes = $conectar->prepare($scriptParticipantes);
        $executarDeleteParticipantes->execute();

        // Deletar agendamento
        $scriptAgendamento = "DELETE FROM agendamentos WHERE codigoagendamento = $codigoAgenamento";
        $executarDeleteAgendamento = $conectar->prepare($scriptAgendamento);
        $executarDeleteAgendamento->execute();

        // Confirmar transação
        $conectar->commit();

        echo "Agendamento cancelado com sucesso.";
    }
    catch (Exception $ex) 
    {
        // Reverter transação em caso de erro
        $conectar->rollBack();
        echo $ex;    
    }
}


/**
 * Função para carregar dados de agendamento de acordo com a data.
 *
 * @param string $data A data para a qual carregar os dados do agendamento.
 * @return void Esta função não retorna um valor, mas emite uma resposta HTML.
 * @throws Exception Se houver um erro ao executar a consulta SQL.
 */
function carregarDadosAgendamento($data, $vcodigousuario)
{
    try
    {
        global $conectar;

        // Preparar a consulta SQL para evitar injeção SQL.
        $stmt = $conectar->prepare("
          SELECT agendamentos.codigoagendamento, 
          clientes.nomeCliente AS 'cliente', 
          tipos.nome AS 'tipo', 
          agendamentos.data, 
          agendamentos.observacao, 
          usuarios.nome AS 'usuario', 
          agendamentos.status, 
          agendamentos.hora, 
          agendamentos.horafinal 
          FROM agendamentos
          LEFT JOIN clientes ON agendamentos.codigocliente = clientes.idCliente
          LEFT JOIN usuarios ON agendamentos.codigousuario = usuarios.id 
          LEFT JOIN tipos ON agendamentos.codigotipo = tipos.idTipo
          WHERE agendamentos.data = :data
          AND agendamentos.codigousuario = :codigousuario");

        // Vincular o parâmetro à consulta preparada.
        $stmt->bindParam(':data', $data, PDO::PARAM_STR);
        $stmt->bindParam(':codigousuario', $vcodigousuario, PDO::PARAM_STR);

        // Executar a consulta.
        $stmt->execute();

        // Buscar todos os resultados.
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultado as $dados)
        {
            $style = '';
            $status = '';

            if ($dados["status"] == 'A')
            {
                $style = 'stripPendente';
                $status = 'Pendente';
            }
            elseif ($dados["status"] == 'P')
            {
                $style = 'stripProrrogado';
                $status = 'Prorrogado';
            }
            elseif ($dados["status"] == 'C')
            {
                $style = 'stripCancelado';
                $status = 'Cancelado';
            }
            else 
            {
                $style = 'stripSucesso';
                $status = 'Finalizado';
            }

            // Tratar valores nulos antes de passá-los para htmlspecialchars()
            $codigoAgendamento = isset($dados["codigoagendamento"]) ? htmlspecialchars($dados["codigoagendamento"]) : '';
            $data = isset($dados["data"]) ? htmlspecialchars($dados["data"]) : '';
            $hora = isset($dados["hora"]) ? htmlspecialchars($dados["hora"]) : '';
            $horafinal = isset($dados["horafinal"]) ? htmlspecialchars($dados["horafinal"]) : '';
            $tipo = isset($dados["tipo"]) ? htmlspecialchars($dados["tipo"]) : '';
            $cliente = isset($dados["cliente"]) ? htmlspecialchars($dados["cliente"]) : '';
            $usuario = isset($dados["usuario"]) ? htmlspecialchars($dados["usuario"]) : '';
            $observacao = isset($dados["observacao"]) ? htmlspecialchars($dados["observacao"]) : '';

            // Continuação do código para exibir os dados do agendamento
            echo "
                <div class='info-box' data-bs-toggle='modal' data-bs-target='#modalAgendamento' data-id='" . $codigoAgendamento . "'>
                    <div class='" . $style . "'></div>
                    <div class='content'>
                        <div class='row'>
                            <div class='col-sm-6'><h6>&nbsp;<i class='bi bi-calendar3'></i>&nbsp;" . $data . " " . $hora . " - " . $horafinal . "</h6></div>
                            <div class='col-sm-2'><h6>&nbsp;<i class='bi bi-keyboard'></i>&nbsp;" . $codigoAgendamento . " </h6></div>
                            <div class='col-sm-2'><h6>&nbsp;<i class='bi bi-bell'></i>&nbsp;" . $status . " </h6></div>
                        </div>
                        <div class='row'>
                            <div class='col-sm-4'><h6>&nbsp;<i class='bi bi-info-square'></i>&nbsp;" . $tipo . "</h6></div>
                            <div class='col-sm-4'><h6>&nbsp;<i class='bi bi-building'></i>&nbsp;" . $cliente . "</h6></div>
                            <div class='col-sm-4'><h6>&nbsp;<i class='bi bi-person-fill'></i>&nbsp;" . $usuario . "</h6></div>
                        </div>
                        <div class='row'>
                            <p>&nbsp;" . $observacao . "</p>
                        </div>
                    </div>
                </div>
            ";
        }        
    }
    catch (Exception $ex) 
    {
        // Em caso de erro, emitir uma mensagem de erro genérica e registrar o erro real.
        error_log("Erro ao carregar os dados do agendamento: " . $ex);
        echo "<p>Erro - Não foi possível carregar os clientes cadastrados.</p>";
    }   
}




/**
 * Função para buscar dados de um agendamento de acordo com o código.
 *
 * @param int $cogidoAgendamento Código do agendamento.
 * @return void Esta função não retorna um valor, mas emite uma resposta JSON.
 * @throws Exception Se houver um erro ao executar a consulta SQL.
 */
function buscarRegistroAgendamento($cogidoAgendamento)
{
    $dadosAgendamento = array();

    try
    {
        global $conectar;

        // Preparar a consulta SQL para evitar injeção SQL.
        $stmt = $conectar->prepare("
            SELECT agendamentos.codigoagendamento, 
           agendamentos.codigocliente, 
           agendamentos.codigotipo, 
           agendamentos.data, 
           agendamentos.observacao, 
           agendamentos.codigousuario, 
           clientes.nomeCliente,
           agendamentos.status, 
           agendamentos.hora, 
           agendamentos.horafinal 
           FROM agendamentos 
           LEFT JOIN clientes ON agendamentos.codigocliente = clientes.idCliente
           WHERE agendamentos.codigoagendamento = :cogidoAgendamento");

        // Vincular o parâmetro à consulta preparada.
        $stmt->bindParam(':cogidoAgendamento', $cogidoAgendamento, PDO::PARAM_INT);

        // Executar a consulta.
        $stmt->execute();

        // Buscar todos os resultados.
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultado as $dados)
        {
            $dadosAgendamento["codigoagendamento"] = $dados["codigoagendamento"];
            $dadosAgendamento["codigocliente"]     = $dados["codigocliente"];
            $dadosAgendamento["codigotipo"]        = $dados["codigotipo"];
            $dadosAgendamento["data"]              = $dados["data"];
            $dadosAgendamento["observacao"]        = $dados["observacao"];
            $dadosAgendamento["codigousuario"]     = $dados["codigousuario"];
            $dadosAgendamento["status"]            = $dados["status"];
            $dadosAgendamento["hora"]              = $dados["hora"];
            $dadosAgendamento["horafinal"]         = $dados["horafinal"];
            $dadosAgendamento["nomeCliente"]       = $dados["nomeCliente"];
        }

        // Preparar a consulta SQL para buscar os participantes do agendamento.
        $stmtParticipantes = $conectar->prepare("
            SELECT idUsuario 
            FROM agendamentosParticipante 
            WHERE idAgendamento = :cogidoAgendamento");

        // Vincular o parâmetro à consulta preparada.
        $stmtParticipantes->bindParam(':cogidoAgendamento', $cogidoAgendamento, PDO::PARAM_INT);

        // Executar a consulta.
        $stmtParticipantes->execute();

        // Buscar todos os resultados.
        $resultadoParticipantes = $stmtParticipantes->fetchAll(PDO::FETCH_ASSOC);

        // Adicionar os participantes ao array $dadosAgendamento.
        $dadosAgendamento["participantes"] = array_map(function($row) {
            return $row["idUsuario"];
        }, $resultadoParticipantes);

        // Emitir a resposta como JSON.
        echo json_encode($dadosAgendamento);
    }
    catch (Exception $ex)
    {
        // Em caso de erro, emitir uma mensagem de erro genérica e registrar o erro real.
        error_log("Erro ao carregar os dados do agendamento: " . $ex);
        echo "Erro - Não foi possível carregar os dados do agendamento.";
    }
}



/**
 * Função para buscar agendamentos pendentes de um usuário específico.
 *
 * @param int $vcodigousuario O código do usuário para o qual buscar agendamentos pendentes.
 * @return void Esta função não retorna um valor, mas emite uma resposta JSON.
 * @throws Exception Se houver um erro ao executar a consulta SQL.
 */
function buscarAgendamentosPendetes($vcodigousuario)
{
    $dadosAgendamento = array();

    try
    {
        global $conectar;

        // Preparar a consulta SQL para evitar injeção SQL.
        $stmt = $conectar->prepare("SELECT data FROM agendamentos WHERE status in ('A', 'P') AND codigousuario = :vcodigousuario");

        // Vincular o parâmetro à consulta preparada.
        $stmt->bindParam(':vcodigousuario', $vcodigousuario, PDO::PARAM_INT);

        // Executar a consulta.
        $stmt->execute();

        // Buscar todos os resultados.
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultado as $dados)
        {
            array_push($dadosAgendamento, $dados["data"]);
        }

        // Emitir a resposta como JSON.
        echo json_encode($dadosAgendamento);
    }
    catch (Exception $ex)
    {
        // Em caso de erro, emitir uma mensagem de erro genérica e registrar o erro real.
        error_log("Erro ao carregar os dados do agendamento: " . $ex);
        echo "Erro - Não foi possível carregar os dados do agendamento.";
    }
}


?>