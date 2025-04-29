(function() {
	'use strict';
	window.addEventListener('load', function() {
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.getElementsByClassName('needs-validation');

		// Loop over them and prevent submission
		var validation = Array.prototype.filter.call(forms, function(form) {
			form.addEventListener('submit', function(event) {
				if (form.checkValidity() === false) {
					var formulario = $(this);
					formulario.find(".tab-pane").each(function( index ) {
  						if($(this).find('.form-control:invalid').length){
  							var aba_erro = formulario.find(".nav-link[aria-controls="+($(this).attr('id'))+"]");
  							aba_erro.addClass('invalid');
  						}else{
  							var aba_erro = formulario.find(".nav-link[aria-controls="+($(this).attr('id'))+"]");
  							aba_erro.addClass('valid');
  						}
					});

					//console.log($('.needs-validation').html());
					event.preventDefault();
					event.stopPropagation();
				}

				form.classList.add('was-validated');
			}, false);
		});
	}, false);
})();