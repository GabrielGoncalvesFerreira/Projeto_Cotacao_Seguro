<?php
    require_once 'header.php';

    if ($_SESSION["acessoAgendamento"] != 1)
    {
        header("Location: index.php");
    }
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/locales/bootstrap-datepicker.pt-BR.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />                           
<script type="text/javascript" src="js/configuracaoAgendamento.js"></script>
<script type="text/javascript" src="js/configuracaoUsuario.js"></script>
<link rel="stylesheet" type="text/css" href="css/estilo_agendamento.css" media="screen" />
<script>configuracaoGrafico();</script>
</br>
<div class="card">
    <div class="card-body">
        <h5><i class="bi bi-calendar-week"></i> Agendamentos</h5>
    </div>
</div>
</br>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">                                                             
                <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTipo" data-bs-whatever="Novo Tipo">
                    <i class="bi bi-plus-square-dotted"></i> Novo Tipo
                </button>     
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAgendamento" data-id="0">
                    <i class="bi bi-calendar-plus"></i> Criar Evento
                </button> 
                </div>                                  
            </div>
        </div>
        </br>
        </br>
        <div class="row grupoAgendamento">
            <div class="col-md-4">
                <div class="calendario" id="divCalendario">
                    <div id="calendar"></div>
                    
                </div>
            </div>
            <div class="col-md-8 divPrincipal" id="divPrincipal">                                
                
            </div>  
        </div> 
    </div>
</div> 

<script>
//Calendário
$(document).ready(function() {
    calendario();
});

</script>  

<?php
require_once 'footer.php';
?>