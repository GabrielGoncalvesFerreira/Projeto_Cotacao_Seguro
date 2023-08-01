<?php
    require_once 'header.php';
?>

<script type="text/javascript" src="js/configuracao_opcoes.js"></script>
<link rel="stylesheet" type="text/css" href="css/estilo_configuracoes.css" media="screen" />
</br>
<div class="card">
    <div class="card-body">
        <h5><i class="bi bi-gear"></i> Configurações</h5>
    </div>
</div>

</br>
<div class="card">
    <div class="card-body">
        <div class="row">
            <h4>Logo</h4>
            <div class="col-md-1">
                <label for="idLogo" class="col-form-label">ID:</label>
                <input type="number" class="form-control" id="idLogo" readonly>
            </div>

            <div class="col-md-3">
                <label for="nomeLogo" class="col-form-label">Nome:</label>
                <input type="text" class="form-control" id="nomeLogo" readonly>
            </div>

            <div class="col-md-4">
                <label for="descricaoLogo" class="col-form-label">Descrição:</label>
                <input type="text" class="form-control" id="descricaoLogo" readonly>
            </div>

            <div class="col-md-4">
                <label for="inputArquivoLogo" class="col-form-label">Imagem:</label>
                <input class="form-control" id="inputArquivoLogo" type="file">
            </div>

        </div>         
        </br>
        <div class="row">
            <button type="submit" class="btn btn-primary" onclick="enviarImagem()">Salvar</button>
        </div>          
        <br>
    </div>
</div>

<script>preencherDados();</script>

<?php
    require_once 'footer.php';
?>