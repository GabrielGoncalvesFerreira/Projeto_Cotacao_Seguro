function verificaForcaSenha() 
{
	var numeros = /([0-9])/;
	var alfabeto = /([a-zA-Z])/;
	var chEspeciais = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
    $('#buttonConfirmar').attr("disabled", false);

	if($('#novaSenha').val().length < 7) 
	{
		$('#tamanho').addClass('fraca');
        $('#tamanho').removeClass('forte');
        $('#buttonConfirmar').attr("disabled", true);
	} 
    else
    {
        $('#tamanho').addClass('forte');
        $('#tamanho').removeClass('fraca');
    }

    if($('#novaSenha').val().match(numeros)) 
	{
		$('#numerico').addClass('forte');
        $('#numerico').removeClass('fraca');
	} 
    else
    {
        $('#numerico').addClass('fraca');
        $('#numerico').removeClass('forte');
        $('#buttonConfirmar').attr("disabled", true);
    }

    if($('#novaSenha').val().match(alfabeto)) 
	{
		$('#alfabetico').addClass('forte');
        $('#alfabetico').removeClass('fraca');
	} 
    else
    {
        $('#alfabetico').addClass('fraca');
        $('#alfabetico').removeClass('forte');
        $('#buttonConfirmar').attr("disabled", true);
    }

    if($('#novaSenha').val().match(chEspeciais)) 
	{
		$('#especial').addClass('forte');
        $('#especial').removeClass('fraca');
	} 
    else
    {
        $('#especial').addClass('fraca');
        $('#especial').removeClass('forte');
        $('#buttonConfirmar').attr("disabled", true);
    }

    if($('#novaSenha').val() == $('#repitaSenha').val())
    {
        $('#senhas').addClass('forte');
        $('#senhas').removeClass('fraca');
    }
    else
    {
        $('#senhas').addClass('fraca');
        $('#senhas').removeClass('forte');
        $('#buttonConfirmar').attr("disabled", true);
    }


}