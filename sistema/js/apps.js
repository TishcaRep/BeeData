var AddItems = existe('AddItems') ? new Vue({
  el: '#AddItems',
  data: {
    hidden: false
  },
  methods: {
    Show: function() {
      this.hidden = !this.hidden;
    }
  }
}) : false;

var CargaMasiva = existe('CargaMasiva') ? new Vue({
  el: '#CargaMasiva',
  methods: {
    SendData: function(url, e) {
      loadingApp.activar("Procesando");
      var data = new FormData(e.target);
      $.ajax({
        type: 'POST',
        url: ajaxurl + '/' + url,
        data: data,
        processData: false,
        contentType: false
      }).then(function(res) {
        loadingApp.desactivar();
        if (res.error) {
          swal('Error', res.mensaje, 'error');
        } else {
          swal('Éxito', res.mensaje, 'success').then(function() {
            location.href = res.url;
          });
        }
      }).fail(function() {
        loadingApp.desactivar();
        swal('Error', 'Error al conectarse con el servidor', 'error');
      });
    }
  }
}) : false;

var DeleteAll = existe('DeleteAll') ? new Vue({
  el: '#DeleteAll',
  methods: {
    Run: function(e) {
      var $this = $(e.target);
      swal({
          title: "Borrar registro",
          text: "¿Está seguro que desea borrar todos los registros?",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then(function(borrar) {
          if (borrar) {
            var data = {
              'Delete': true
            };
            $.post($this.data('url-borrar'), data).then(function(res) {
              if (res.error) {
                swal("¡Error!", "Ocurrió un error al borrar los registros", "error");
              } else {
                swal("¡Éxito!", "Los registros se borraron correctamente", "success");
                $('.tabla-ajax').bootgrid('reload');
              }
            }).fail(function() {
              swal('¡Error!', 'Ocurrió un error al conectarse con el servidor', 'error');
            });
          }
        });
    }
  }
}) : false;

var Editar = existe('Editar') ? new Vue({
  el: '#Editar',
  data: {
    datos: [{
      nombre: 'Cargando...'
    }],
    id: 0,
    selected: 0
  },
  methods: {
    Show: function(url, id, p) {
      this.id = id;
      $.post(url, {
        'id': id
      }).then(function(res) {
        if (res.error) {
          swal("¡Error!", res.mensaje, "error");
        } else {
          this.datos = res.data;
          //console.log(this.datos);
          $('#Editar').modal('show');
        }
      }.bind(this)).fail(function() {
        swal('Error', 'Ocurrió un error al conectarse con el servidor', 'error');
      });
      if (existe('padre')) {
        $('#padre').trigger("change");
      }
    }
  }
}) : false;

var EditarS = existe('EditarS') ? new Vue({
  el: '#EditarS',
  data: {
    giros: [],
    cjms: [],
    datos: [],
    id: 0,
    idG: 0,
    idCjm: 0,
    selected: 0
  },
  methods: {
    Show: function(url, id, p) {

      this.id = id;
      $.post(ajaxurl + '/sistema/girosEmpresariales', {
        'current': 1,
        'rowCount': -1,
        'searchPhrase': '',
        'filtros': ''
      }).then(function(res) {
        if (res.error) {
          swal("¡Error!", res.mensaje, "error");
        } else {
          this.giros = res.rows;
        }
      }.bind(this)).fail(function() {
        swal('Error', 'Ocurrió un error al conectarse con el servidor', 'error');
      });

      $.post(url, {
        'id': id
      }).then(function(res) {
        if (res.error) {
          swal("¡Error!", res.mensaje, "error");
        } else {
          this.datos = res.data;
          this.idG = res.data.idG;
          this.idCjm = res.data.idCjm;
          this.fillHijo(this.idG);
          $('#EditarS').modal('show');
        }
      }.bind(this)).fail(function() {
        swal('Error', 'Ocurrió un error al conectarse con el servidor', 'error');
      });
      if (existe('padre')) {
        $('#padre').trigger("change");
      }
    },
    fillHijo: function(id) {
      $.post(ajaxurl + '/sistema/mapas-experiencia-cliente', {
        'current': 1,
        'rowCount': -1,
        'searchPhrase': '',
        'filtros': 'idG=' + id
      }).then(function(res) {
        if (res.error) {
          swal("¡Error!", res.mensaje, "error");
        } else {
          this.cjms = res.rows;
        }
      }.bind(this)).fail(() => {
        swal('Error', 'Ocurrió un error al conectarse con el servidor', 'error');
      });
    }
  }
}) : false;

var UserPage = existe('UserPage') ? new Vue({
  el: '#UserPage',
  data: {
    datos: [],
    id: 0
  },
  mounted: function() {
    $.post(ajaxurl + '/sistema/usuarios/consulta', {
      'id': this.id
    }).then(function(res) {
      if (res.error) {
        swal("¡Error!", res.mensaje, "error");
      } else {
        this.datos = res.data;
      }
    }.bind(this)).fail(() => {
      swal('Error', 'Ocurrió un error al conectarse con el servidor', 'error');
    });
  },
  methods: {
    Show: function() {},
    Show: function() {}
  }
}) : false;

var NuevaUnidad = existe('NuevaUnidad') ? new Vue({
  el: '#NuevaUnidad',
  data: {
    ifsucursal:true,
    sucursales: [],
    Estado: {
      NameE: "Selecciona codigo postal",
      NameM: "Selecciona codigo postal"
    },
    Colonias: [],
    Denominacion: "",
    disabled: false,
    Estadof: {
      NameE: "Selecciona codigo postal",
      NameM: "Selecciona codigo postal"
    },
    Coloniasf: [],
  },
  methods: {

    Show: function(url, id, p) {
      this.id = id;
      $.post(ajaxurl + '/sistema/generales/sucursales', {}).then(function(res) {
        if (res.error) {
          swal("¡Error!", res.mensaje, "error");
        } else {
          this.sucursales = res.datos;
        }
      }.bind(this)).fail(function() {
        swal('Error', 'Ocurrió un error al conectarse con el servidor', 'error');
      });
      $('#NuevaUnidad').modal('show');
    },
    ActValues: function(event) {
      if (event.target.value.length >= 5) {
        $.post(ajaxurl + '/sistema/generales/codigos-postales', {
          'CodigoPostal': event.target.value
        }).then(function(res) {
            this.Estado.NameE = res.data[0].Estado;
            this.Estado.NameM = res.data[0].Municipio;
            this.Colonias = res.Colonias;
        }.bind(this)).fail(() => {
          swal('Error', 'Ocurrió un error al conectarse con el servidor', 'error');
        });
      } else {
        this.Estado.NameE = "Selecciona codigo postal";
        this.Estado.NameM = "Selecciona codigo postal";
      }
    },
    ActValuesf: function(event) {
      if (event.target.value.length >= 5) {
        $.post(ajaxurl + '/sistema/generales/codigos-postales', {
          'CodigoPostal': event.target.value
        }).then(function(res) {
            this.Estadof.NameE = res.data[0].Estado;
            this.Estadof.NameM = res.data[0].Municipio;
            this.Coloniasf = res.Colonias;
        }.bind(this)).fail(() => {
          swal('Error', 'Ocurrió un error al conectarse con el servidor', 'error');
        });


      } else {
        this.Estadof.NameE = "Selecciona codigo postal";
        this.Estadof.NameM = "Selecciona codigo postal";
      }
    },

  }
}) : false;

var Contrato = existe('Contrato') ? new Vue({
  el: '#Contrato',
  data: {
    idPlan:0,
    datos: [{
      id:0,
      Nombre: 'Tacos',
      Monto: 'Monto',
      PagoDiferido: '',
      LimiteClientes:'100',
      LimiteEncuestas:'100',
      Duracion:'12 Meses'
    },
  ],
    id: 0,
    selected: 0
  },
  methods: {
    Show: function(id) {
      this.FillPlanes();
      $("#Contrato").modal('show');
    },
    FillPlanes:function() {
      $.post(ajaxurl + '/sistema/planes/lista-planes').then(function(res) {
          this.id = res.data[0].id;
          this.datos = res.data;
      }.bind(this)).fail(() => {
        swal('Error', 'Ocurrió un error al conectarse con el servidor', 'error');
      });
    },
    GetSpei:function() {
      $.post(ajaxurl + '/sistema/planes/newspei',{
        id: 1
      }).then(function(res) {
        
      }.bind(this)).fail(() => {
        swal('Error', 'Ocurrió un error al conectarse con el servidor', 'error');
      });
    }

  }
}) : false;

var EscalarContrato = existe('EscalarContrato') ? new Vue({
  el: '#EscalarContrato',
  data: {
    datos: [{
      nombre: 'Cargando...'
    }],
    id: 0,
    selected: 0
  },
  methods: {
    Show: function(modal, id) {
      $('#EscalarContrato').modal('show');

    }
  }
}) : false;

var RenovarContrato = existe('RenovarContrato') ? new Vue({
  el: '#RenovarContrato',
  data: {
    datos: [{
      nombre: 'Cargando...'
    }],
    id: 0,
    selected: 0
  },
  methods: {
    Show: function(modal, id) {
      $('#RenovarContrato').modal('show');

    }
  }
}) : false;

var HistoralPagos = existe('HistoralPagos') ? new Vue({
  el: '#HistoralPagos',
  data: {
    datos: [{
      nombre: 'Cargando...'
    }],
    id: 0,
    selected: 0
  },
  methods: {
    Show: function(modal, id) {
      $('#HistoralPagos').modal('show');

    }
  }
}) : false;
