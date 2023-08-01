/*function buscarDadosConfiguracoes()
{
    fetch('configuracao/conf.config')
  .then(response => response.text())
  .then(text => {
    const array = text.split(";");
    console.log(array);
  })

}*/

function preencherDados()
{
    buscarDadosConfiguracoes().then((response) => {
        const array = response;
        $('#idLogo').val(array[0]);
        $('#nomeLogo').val(array[1]);
        $('#descricaoLogo').val(array[2]);
    });
}

function carregarLogo()
{
    buscarDadosConfiguracoes().then((response) => {
        const array = response;
        $('#descricaoLogo').val(array[2]);
    });
}

async function buscarDadosConfiguracoes() {
    /** @type {string} */
    var vId = 0;
    var array = [];
    
    startSpinner();
    
    /**
     * Realiza uma requisição assíncrona para buscar o registro de agendamentos.
     * @type {$.ajax}
     */
    await $.ajax({
        url: 'configuracao_opcoes.php',
        cache: false,
        type: "POST",
        dataType: "JSON", 
        data: {
            id: vId,
        },
        success: function(dados){
            array = dados[0].split(";"); 
            stopSpinner();
        },
        error: function(xhr, status, error){
            /**
             * Exibe um alerta de erro com as informações do erro.
             * @type {Swal.fire}
             */
            Swal.fire({
                title: "Aviso",
                text: JSON.stringify(error.message),
                icon: "error",
            });

            console.log(error.message);
            stopSpinner();
        }
    });

    return await Promise.resolve(array);
}


function enviarImagem() {
    const inputImagem = document.getElementById('inputArquivoLogo');
    const arquivo = inputImagem.files[0];

    const formData = new FormData();
    formData.append('imagem', arquivo);

    fetch('upload.php', {
      method: 'POST',
      body: formData,
    })
      .then((response) => response.text())
      .then((result) => {
        console.log(result); // Exibe a resposta do servidor
      })
      .catch((error) => {
        console.error('Erro:', error);
      });
  }
  
  
  
  
  
  
  