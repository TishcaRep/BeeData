$(document).ready(function() {
  $('.tabla-ajax').each(function() {
    var $this = $(this);
    var seleccion = $this.data('seleccion');
    var columna_id = ($this.data('columna-id')) ? $this.data('columna-id') : 'id';
    var columna_color = ($this.data('columna-Color')) ? $this.data('columna-Color') : 'Color';
    var columna_Monto = ($this.data('columna-Monto')) ? $this.data('columna-Monto') : 'Monto';
    $this.bootgrid({
      ajax: true,
      responsiveTable: true,
      url: $this.data('url'),
      post: function() {
        return {
          filtros: $('.filtros').serialize(),
        };
      },
      formatters: {
        "acciones": function(c, r) {
          if ($this.data('modal-ver')) {
            return '<div class="actions"><a href="#" data-toggle="modal" data-target="#' + $this.data('modal-ver') + '" data-id="' + r[columna_id] + '" class="btn btn-primary m-r-5 btn-ver-registro" ><i class="mdi mdi-eye" aria-hidden="true"></i></a><a href="' + $this.data('url-editar') + '/' + r[columna_id] + '" class="btn btn-secondary m-r-5"><i class="mdi mdi-pencil" aria-hidden="true"></i></a><a href="#" class="btn btn-danger btn-eliminar-registro" data-id="' + r[columna_id] + '"><i class="mdi mdi-delete-forever" aria-hidden="true"></i></a></p>';
          } else {
            return '<div class="actions"><a href="#" data-toggle="modal" data-target="#Editar" data-id="' + r[columna_id] + '" class="btn btn-secondary m-r-5  btn-editar-registro"><i class="mdi mdi-pencil" aria-hidden="true"></i></a><a href="#" class="btn btn-danger btn-eliminar-registro" data-id="' + r[columna_id] + '"><i class="mdi mdi-delete-forever" aria-hidden="true"></i></a></p>';
          }
        },
        "acciones-eliminar-only": function(c, r) {
          return '<div class="actions"><a href="#" class="btn btn-danger btn-eliminar-registro" data-id="' + r[columna_id] + '"><i class="mdi mdi-delete-forever" aria-hidden="true"></i></a></p>';
        },
        "acciones-planes": function(c, r) {
          return `
          <div class="actions popup">
            <div class="outsidehidden">
              <div class="popmenu">
                <div href="#" data-toggle="modal" data-target="#Contrato" data-id="` + r[columna_id] + `" class="btn-display-modal bob-modal">
                    Contratar
                </div>
                <div data-toggle="modal" data-target="#EscalarContrato" data-id="` + r[columna_id] + `" class="btn-display-modal bob-modal">
                  Escalar
                </div>
                <div data-toggle="modal" data-target="#CancelarContrato" data-id="` + r[columna_id] + `" class="btn-display-modal bob-modal">
                  Cancelar
                </div>
                <div data-toggle="modal" data-target="#RenovarContrato" data-id="` + r[columna_id] + `" class="btn-display-modal bob-modal">
                  Renovar
                </div>
                  <div data-toggle="modal" data-target="#HistoralPagos" data-id="` + r[columna_id] + `" class="btn-display-modal bob-modal">
                  Historial de pagos
                </div>
              </div>
            </div>
            <a href="#" class="btn btn-secondary bob" id="openmodal">
              <i class="fas fa-cogs"></i>
            </a>
          </div>`;
        },
        "color": function(c, r) {
          var str1 = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="80px" height="100px" viewBox="0 0 1024 720" enable-background="new 0 0 1024 720" xml:space="preserve" ><rect id="svgEditorBackground" x="0" y="0" width="1024" height="720" style="fill:none;stroke:none;"/><g transform="matrix(1.85529 0.0194727 -0.0194727 1.85529 -412.316 -101.261)">';
          var str2 = '<path fill="' + r[columna_color] + '" d="M639.277,241.945c-15.602-16.288-35.381-25.201-57.008-30.412c-20.571-4.948-41.299-4.822-62.188-2.277   c-15.583,1.888-21.353,3.772-24.842,1.695l-20.041,35.503l-20.042-35.503c-3.493,2.077-9.262,0.192-24.835-1.695   c-20.9-2.544-41.625-2.671-62.199,2.277c-21.627,5.211-41.4,14.124-57.016,30.412c-13.168,13.747-10.939,27.425,5.443,37.188   c5.29,3.151,11.015,5.232,16.962,6.714c40.52,10.07,73.005-3.051,99.349-40.75c4.393-6.28,7.674-8.559,14.47-3.14   c2.966,2.369,3.217,3.896,2.411,6.954c-1.582,5.963-1.837,11.98,0.637,19.402c8.454-4.777,16.698-9.348,24.819-13.951   c8.128,4.604,16.36,9.174,24.812,13.951c2.488-7.421,2.229-13.438,0.645-19.402c-0.808-3.059-0.557-4.585,2.414-6.954   c6.799-5.418,10.072-3.14,14.461,3.14c26.348,37.7,58.834,50.82,99.348,40.75c5.962-1.481,11.673-3.563,16.976-6.714   C650.229,269.37,652.45,255.691,639.277,241.945"/>';
          var res = str1.concat(str2);
          str1 = '<path fill="' + r[columna_color] + '" d="M675.173,148.619c-30.684999999999945,-9.251000000000005,-61.37800000000004,-6.477000000000004,-91.76700000000005,2.1850000000000023c-28.90700000000004,8.246999999999986,-54.452,22.87899999999999,-78.51999999999998,40.614000000000004c-17.95799999999997,13.209000000000003,-23.786999999999978,19.570999999999998,-29.548000000000002,19.442000000000007c-5.766999999999996,0.12899999999999068,-11.591000000000008,-6.233000000000004,-29.543999999999983,-19.442000000000007c-24.072000000000003,-17.73400000000001,-49.613,-32.36699999999999,-78.524,-40.614000000000004c-30.380999999999972,-8.662000000000006,-61.07400000000001,-11.436000000000007,-91.75799999999998,-2.1850000000000023c-25.887,7.799000000000007,-32.696,26.270999999999987,-19.23599999999999,49.791c4.336999999999989,7.591000000000008,9.956000000000017,14.159999999999997,16.28800000000001,20.147999999999996c43.09800000000001,40.750999999999976,92.44,47.202,151.35899999999998,18.978999999999985c9.817000000000007,-4.713999999999999,15.45999999999998,-5.231999999999999,20.079000000000008,6.217999999999989c2.0190000000000055,5.003000000000014,1.274000000000001,7.064999999999998,-1.870999999999981,10.281000000000006c-6.113999999999976,6.262000000000029,-10.63900000000001,13.53400000000002,-12.75,24.44399999999999c15.701999999999998,0,30.899,0.160000000000025,45.956999999999994,0.08199999999999363c15.057000000000016,0.07799999999997453,30.25600000000003,-0.08199999999999363,45.95400000000001,-0.08199999999999363c-2.106999999999971,-10.910000000000025,-6.625,-18.182000000000016,-12.744000000000028,-24.44399999999999c-3.1469999999999914,-3.216000000000008,-3.887999999999977,-5.276999999999987,-1.8740000000000236,-10.281000000000006c4.622000000000014,-11.450999999999993,10.259000000000015,-10.932999999999993,20.07899999999995,-6.217999999999989c58.91899999999998,28.22199999999998,108.26099999999997,21.771000000000015,151.36,-18.978999999999985c6.331999999999994,-5.9879999999999995,11.946000000000026,-12.556999999999988,16.289999999999964,-20.147999999999996c13.458000000000084,-23.52000000000001,6.655000000000086,-41.992999999999995,-19.229999999999905,-49.791"/>';
          res = res.concat(str1);
          str1 = '<path fill="' + r[columna_color] + '" d="M439.446,115.427c-3.962,5.288-8.091,9.717-10.988,14.842c-11.231,19.875,0.397,40.958,23.098,41.743   c7.917,0.278,15.85,0.278,23.782,0.234c7.933,0.044,15.873,0.044,23.782-0.234c22.698-0.785,34.326-21.868,23.097-41.743   c-2.896-5.125-7.024-9.554-10.991-14.842c2.025-2.5,4.084-5.025,6.091-7.569c9.94-12.591,16.31-26.72,16.604-42.88   c0.068-3.17-3.235-8.633-5.806-9.173c-5.667-1.215-6.106,4.133-6.232,8.499c-0.537,18.335-9.98,32.16-22.072,44.98   c-1.877-0.807-3.44-1.119-4.543-2.007c-6.645-5.33-13.272-8.162-19.931-8.106c-6.654-0.056-13.287,2.777-19.924,8.106   c-1.106,0.888-2.669,1.2-4.551,2.007c-12.091-12.82-21.538-26.645-22.068-44.98c-0.125-4.365-0.562-9.713-6.233-8.499   c-2.572,0.541-5.869,6.003-5.809,9.173c0.303,16.16,6.672,30.289,16.605,42.88C435.369,110.401,437.425,112.927,439.446,115.427"/>';
          res = res.concat(str1);
          str1 = '<path fill="' + r[columna_color] + '" d="M523.414,287.642h-95.97c-0.374,3.056-0.603,6.782-0.421,11.081c0,0.007,0,0.018,0,0.018h96.851   C524.059,295.105,523.933,291.39,523.414,287.642"/>';
          res = res.concat(str1);
          str1 = '<path fill="' + r[columna_color] + '" d="M430.448,318.375c0.066,0.23,0.145,0.423,0.215,0.634c0.658,2.108,1.437,4.274,2.325,6.473h84.667   c1.778-4.388,3.444-9.42,4.626-14.842H428.5C429.003,313.121,429.626,315.687,430.448,318.375"/>';
          res = res.concat(str1);
          str1 = '<path fill="' + r[columna_color] + '" d="M442.128,342.939c8.188,14.265,17.65,24.629,27.822,34.441c1.692,1.633,3.396,3.377,5.388,3.147   c1.984,0.229,3.695-1.515,5.385-3.147c10.183-9.812,19.634-20.176,27.825-34.441h-33.21H442.128z"/></g></svg>';
          res = res.concat(str1);
          return res;


          //document.getElementsByTagName("svg")[0].style.color = r[columna_color];
          // return '<img src="http://beedata.mx/pymes/sistema/images/logo_svg_alone.svg" style="fill:'+ r[columna_color] +'" aria-hidden="true" />';
          //return '<object type="image/svg+xml" data="http://beedata.mx/pymes/sistema/images/logo_svg_alone.svg" style="fill:'+ r[columna_color] +'">Your browser does not support SVG</object>';
        },
        "money": function(c, r) {
          return '$' + r[columna_Monto] + '';
        }

      },
      selection: seleccion,
      multiSelect: seleccion
    }).on("loaded.rs.jquery.bootgrid", function() {
      /* Executes after data is loaded and rendered */
      $this.find(".btn-eliminar-registro").on("click", function(e) {
        var id = $(this).data('id');
        swal({
            title: "Borrar registro",
            text: "??Est?? seguro que desea borrar el registro?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then(function(borrar) {
            if (borrar) {
              var data = {};
              data[columna_id] = id;
              $.post($this.data('url-borrar'), data).then(function(res) {
                if (res.error) {
                  swal("??Error!", res.mensaje, "error");
                } else {
                  swal("????xito!", "El registro se borr?? correctamente", "success");
                  $this.bootgrid('reload');
                }
              }).fail(function() {
                swal('??Error!', 'Ocurri?? un error al conectarse con el servidor', 'error');
              });
            }
          });
      });

      $this.find(".bob").on("click", function(e) {
        e.preventDefault();
        if (!($(e.target).parents(".popup")).find(".popmenu").is(":visible")) {
            ($(e.target).parents(".popup")).find(".popmenu").toggle();
        }
        else {
          ($(e.target).parents(".popup")).find(".popmenu").hide();
        }
      });

      $this.find(".btn-display-modal").on("click", function(e) {
        e.preventDefault();
        $this.data('url-editar')
        var id = $(this).data('id')
        if($(this).data('target')=="#Contrato")
        {
          Contrato.Show(id);
        }
        if($(this).data('target')=="#EscalarContrato")
        {
          EscalarContrato.Show(id);
        }
        if($(this).data('target')=="#CancelarContrato")
        {
          CancelarContrato.Show(id);
        }
        if($(this).data('target')=="#RenovarContrato")
        {
          RenovarContrato.Show(id);
        }
        if($(this).data('target')=="#HistoralPagos")
        {
          HistoralPagos.Show(id);
        }
      });

      $this.find(".btn-ver-registro").on("click", function(e) {
        e.preventDefault();
        var id = $(this).data('id');
      });

      $this.find(".btn-editar-registro").on("click", function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (existe('Editar')) {
          Editar.Show($this.data('url-editar'), id);
        }
        if (existe('EditarS')) {
          EditarS.Show($this.data('url-editar'), id);
        }
      });

    });
    //Filtros
    $('.filtros :input').change(function() {
      $this.bootgrid('reload');
    });
  });



  $(document).click(function(event) {
  if (!$(event.target).closest(".popmenu").length) {
    $("body").find(".popmenu").hide();
  }
});


});
