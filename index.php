<?php
    require_once 'header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0-rc/dist/chartjs-plugin-datalabels.min.js"></script>

<script type="text/javascript" src="js/graficos.js"></script>
<link rel="stylesheet" type="text/css" href="css/estilo_index.css" media="screen" />

</br>

<div class="card">
    <div class="card-body">
        <h5><i class="bi bi-bar-chart"></i> Dashboard</h5>
    </div>
</div>

<div class="container mt-5">
  <div class="row g-4 d-flex">
    <div class="col-sm">
      <div class="panel d-flex flex-column align-items-start justify-content-center p-4 h-100 totalClientes">
        <h1 class="number mb-2" id="totalClientes">0</h1>
        <p class="description mb-2">Total de Clientes</p>
        <i class="bi bi-people-fill icon fs-1 position-absolute bottom-0 end-0 mb-3 me-3"></i>
      </div>
    </div>
    <div class="col-sm">
      <div class="panel d-flex flex-column align-items-start justify-content-center p-4 h-100 agendamentosPendentes">
        <h1 class="number mb-2" id="totalAgendamento">0</h1>
        <p class="description mb-2">Agendamentos Pendentes</p>
        <i class="bi bi-calendar3-week-fill icon fs-1 position-absolute bottom-0 end-0 mb-3 me-3"></i>
      </div>
    </div>
    <div class="col-sm">
      <div class="panel d-flex flex-column align-items-start justify-content-center p-4 h-100 totalUsuarios">
        <h1 class="number mb-2" id="totalUsuario">0</h1>
        <p class="description mb-2">Total de Usuários</p>
        <i class="bi bi-person-check-fill icon fs-1 position-absolute bottom-0 end-0 mb-3 me-3"></i>
      </div>
    </div>
  </div>
</div>

</br>

<div class="row">
  <div class="col-md-8">
    <div class="card" id="cardAgendamento" style="height: 100%;">
      <div class="card-body" style="height: 100%;">
        <h5 class="card-title">Agendamentos</h5>
        <h6 class="card-subtitle mb-2 text-muted">Carrega os agendamentos dos últimos 30 dias</h6>
        <div style="height: 80%;"> <!-- Adicione esta div para controlar a altura do gráfico -->
          <canvas id="myChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card" id="cardAgendamento">
      <div class="card-body">
        <h5 class="card-title">Agendamentos</h5>
        <h6 class="card-subtitle mb-2 text-muted">% Agendamentos por status</h6>
        <div class="graph-container">
          <canvas id="myChartRosca"></canvas>
        </div>    
      </div>
    </div>
  </div>
</div>



<?php
    require_once 'footer.php';
?>