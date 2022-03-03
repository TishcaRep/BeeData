$(document).ready(function(){
	$('.form-login').submit(function(e){
		e.preventDefault();
		var $this = $(this);
		var data = $this.serialize();
		var url = $this.data('url');
		loadingApp.activar();
		$.post(ajaxurl + '/' + url, data).then(function(res){
			loadingApp.desactivar();
			if(res.error){
				swal('Error', res.mensaje, 'error');
			} else {
				$.MainApp.loader(true);
				location.href = res.url;
			}
		}).fail(function(){
			loadingApp.desactivar();
			swal('Error', 'Error al conectarse con el servidor', 'error');
		});
	});
});