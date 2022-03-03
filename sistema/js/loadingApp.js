function existe(id){
	return document.getElementById(id) != null;
}

var loadingApp = existe('loading-app') ? new Vue({
    el: '#loading-app',
    data: {
        activo: false,
        mensaje: ''
    },
    methods: {
        activar: function(mensaje = '') {
            this.activo = true;
            this.mensaje = mensaje;
        },
        desactivar: function() {
            this.activo = false;
        }
    }
}) : false;

function toggleModal(modal)
{
	$(modal).toggle();
}
