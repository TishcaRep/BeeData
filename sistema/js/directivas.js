Vue.directive('dropify', {
  inserted: function (el) {
    Vue.nextTick(function(){
    	$(el).dropify({
			messages : {
				'default' : 'Arrastra un archivo o da click aquí',
				'replace' : 'Arrastra un archivo o da click aquí para reemplazar',
				'remove' : 'Quitar',
				'error' : 'Ooops, ocurrió un error, intente más tarde, por favor.'
			}
		});
    });
  }
});