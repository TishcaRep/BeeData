var repeaterApp = existe('repeater-app') ? new Vue({
    el: '#repeater-app',
    data: {
        filas: [{
        	id: uuidv1()
        }],
        filas_db: 0
    },
    mounted: function(){
    	this.filas_db = parseInt(this.$el.dataset.filasDb);
    },
    methods: {
    	buscar: function(id){
    		for(var i = 0; i<this.filas.length; i++){
    			if(this.filas[i].id == id){
    				return i;
    			}
    		}
    		return -1;
    	},
        agregar: function(modelo){
        	this[modelo].push({id: uuidv1()});
        },
        quitar: function(modelo, id){
        	var index = this.buscar(id);
        	if(index != -1){
        		Vue.delete(this[modelo], index);	
        	}
        },
        quitarDOM: function(e){
        	var el = e.target;
        	var fila = el.closest('.fila');
        	fila.remove();
        	this.filas_db = (this.filas_db == 0) ? 0 : (this.filas_db - 1);
        }
    }
}) : false;