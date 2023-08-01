<!DOCTYPE html>
<html>
<head>
    <title>Calendário</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    
    <style>
        .container {
            margin-top: 50px;
        }
        .highlight-event {
            background-color: #FFC107 !important;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Calendário</h2>
        <div id="calendar"></div>
        <button type="button" class="btn btn-primary" id="createEventButton">Criar Evento</button>
        <button type="button" class="btn btn-danger" id="removeEventsButton">Remover Eventos</button>
    </div>

    <!-- Modal para adicionar evento -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Adicionar Evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="eventForm">
                        <div class="form-group">
                            <label for="eventTitle">Título do Evento</label>
                            <input type="text" class="form-control" id="eventTitle" placeholder="Digite o título do evento">
                        </div>
                        <div class="form-group">
                            <label for="eventDescription">Descrição do Evento</label>
                            <textarea class="form-control" id="eventDescription" rows="3" placeholder="Digite a descrição do evento"></textarea>
                        </div>
                        <input type="hidden" id="eventDate">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveEventButton">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para exibir eventos de um dia -->
    <div class="modal fade" id="eventListModal" tabindex="-1" role="dialog" aria-labelledby="eventListModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventListModalLabel">Eventos do Dia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul id="eventList" class="list-group"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>

    $(document).ready(function() {
    var events = {};

    function updateEventHighlight() {
        $('.datepicker .day').each(function() {
            var day = $(this).text();
            if (events[day] = 10) {
                $(this).addClass('highlight-event');
            } else {
                $(this).removeClass('highlight-event');
            }
        });
    }

    function initializeCalendar() {
        $('#calendar').datepicker({
            language: 'pt-BR',
        format: "yyyy-mm-dd",
        autoclose: true,
        todayHighlight: true,
        todayBtn: "linked",
        weekStart: 1,
        dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
        dayNamesMin: ['D','S','T','Q','Q','S','S'],
        dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
        monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        }).on('changeDate', function(e) {
            var date = $('#calendar').datepicker('getFormattedDate');
            var eventList = events[date];
            if (eventList) {
                var html = '';
                for (var i = 0; i < eventList.length; i++) {
                    html += '<li class="list-group-item">' +
                        '<span>' + eventList[i].title + '</span>' +
                        '<button type="button" class="btn btn-danger btn-sm delete-event" data-date="' + date + '" data-index="' + i + '">Excluir</button>' +
                        '</li>';
                }
                $('#eventList').html(html);
                $('#eventListModal').modal('show');
            }
        });

        $('#createEventButton').click(function() {
            $('#eventModal').modal('show');
        });

        $('#saveEventButton').click(function() {
            var title = $('#eventTitle').val();
            var description = $('#eventDescription').val();
            var date = $('#calendar').datepicker('getFormattedDate');

            if (title && date) {
                if (!events[date]) {
                    events[date] = [];
                }

                events[date].push({
                    title: title,
                    description: description
                });

                updateEventHighlight();

                $('#eventModal').modal('hide');
                $('#eventTitle').val('');
                $('#eventDescription').val('');
            }
        });

        $('#removeEventsButton').click(function() {
            events = {};
            updateEventHighlight();
        });

        $('#eventList').on('click', '.delete-event', function() {
            var date = $(this).data('date');
            var index = $(this).data('index');
            if (events[date]) {
                events[date].splice(index, 1);
                if (events[date].length === 0) {
                    delete events[date];
                }
                updateEventHighlight();
                $('#eventListModal').modal('hide');
            }
        });

        updateEventHighlight();
    }

    initializeCalendar();
});



    </script>
</body>
</html>