$(document).ready(function(){

	var options =  {
	  onKeyPress: function(valor, e, field, options) {
	    var masks = ['00.000.000/0000-00', '000.000.000-000'];
	    var mask = (valor.length>14) ? masks[0] : masks[1];
	    $('.mask-cpf-cnpj').mask(mask, options);
	}};
	$('.mask-cpf-cnpj').mask('000.000.000-000', options);

	$('.mask-date').mask('00/00/0000');
	$('.mask-time').mask('00:00:00');
	$('.mask-date-time').mask('00/00/0000 00:00:00');
	$('.mask-cep').mask('00000-000');
	$('.mask-phone').mask('0000-0000');
	$('.mask-phone-with-ddd').mask('(00) 0000-0000');
	$('.mask-celular').mask('(00) 00000-0000');
	$('.mask-phone-us').mask('(000) 000-0000');
	$('.mask-mixed').mask('AAA 000-S0S');
	$('.mask-cpf').mask('000.000.000-00', {reverse: true});
	$('.mask-cnpj').mask('00.000.000/0000-00', {reverse: true});
	$('.mask-money').mask('000.000.000.000.000,00', {reverse: true});
	$('.mask-money2').mask("#.##0,00", {reverse: true});
	$('.mask-ip-address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
		translation: {
			'Z': {
				pattern: /[0-9]/, optional: true
			}
		}
	});
	$('.mask-ip_address').mask('099.099.099.099');
	$('.mask-percent').mask('##0,00%', {reverse: true});
	$('.mask-clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
	$('.mask-placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
	$('.mask-fallback').mask("00r00r0000", {
		translation: {
			'r': {
				pattern: /[\/]/,
				fallback: '/'
			},
			placeholder: "__/__/____"
		}
	});
	$('.mask-selectonfocus').mask("00/00/0000", {selectOnFocus: true});
	$('.mask-integer').mask('0000000000');
	$('.mask-cartao').mask('0000000000000000000', {reverse: true});
	$('.mask-cartao-cvv').mask('0000', {reverse: true});
});

