$(document).ready(function(){

	if($.fn.dropify){
		$('.dropify').dropify({
			messages : {
				'default' : 'Arrastra un archivo o da click aquí',
				'replace' : 'Arrastra un archivo o da click aquí para reemplazar',
				'remove' : 'Quitar',
				'error' : 'Ooops, ocurrió un error, intente más tarde, por favor.'
			}
		});
	}
	$("#openmodal").on("click",function(e){
			// $(this).attr(".popmenu").toggle();
			$(".popmenu").toggle();
		});

	$(document).on("click",".mechead",function (e){
		var $cont = $(this).parent();
		$cont.find(".mecbody").toggle();
		$cont.find(".mecfoot").toggle();
	});

	$(".btn-openmail").click(function (e){
		$(".mecmail").toggle();
	});

	$(".tabshow").click(function (e){
		var tab = $(this).data("tab");
		$(".tab-info").slideUp();
		$("." + tab).slideDown();
	});


// 	$('#select_all').click(function(event) {
//   if(this.checked) {
//       // Iterate each checkbox
//       $(':checkbox').each(function() {
//           this.checked = true;
//       });
//   }
//   else {
//     $(':checkbox').each(function() {
//           this.checked = false;
//       });
//   }
// });
//
// 	$('#checkall').click(function(e) {
//     if(.containercheck input.checked) {
//         // Iterate each checkbox
//         $(':checkbox').each(function() {
//             this.checked = true;
//         });
//     } else {
//         $(':checkbox').each(function() {
//             this.checked = false;
//         });
//     }
// });

	$('[data-toggle="tooltip"]').tooltip();

	$('#carouselcontrato').on('slide.bs.carousel', function (e) {
	    var $e = $(e.relatedTarget);
	    var idx = $e.index();
	    var itemsPerSlide = 3;
	    var totalItems = $('#carouselcontrato .carousel-item').length;

	    if (idx >= totalItems-(itemsPerSlide-1)) {
	        var it = itemsPerSlide - (totalItems - idx);
	        for (var i=0; i<it; i++) {
	            // append slides to end
	            if (e.direction=="left") {
	                $('#carouselcontrato .carousel-item').eq(i).appendTo('#carouselcontrato .carousel-inner');
	            }
	            else {
	                $('#carouselcontrato .carousel-item').eq(0).appendTo('#carouselcontrato .carousel-inner');
	            }
	        }
	    }
	});

	$('#carouselcontrato_2').on('slide.bs.carousel', function (e) {
			var $e = $(e.relatedTarget);
			var idx = $e.index();
			var itemsPerSlide = 2;
			var totalItems = $('#carouselcontrato_2 .carousel-item').length;

			if (idx >= totalItems-(itemsPerSlide-1)) {
					var it = itemsPerSlide - (totalItems - idx);
					for (var i=0; i<it; i++) {
							// append slides to end
							if (e.direction=="left") {
									$('#carouselcontrato_2 .carousel-item').eq(i).appendTo('#carouselcontrato_2 .carousel-inner');
							}
							else {
									$('#carouselcontrato_2 .carousel-item').eq(0).appendTo('#carouselcontrato_2 .carousel-inner');
							}
					}
			}
	});

	$('#carouselcontrato_3').on('slide.bs.carousel', function (e) {
			var $e = $(e.relatedTarget);
			var idx = $e.index();
			var itemsPerSlide = 2;
			var totalItems = $('#carouselcontrato_3 .carousel-item').length;

			if (idx >= totalItems-(itemsPerSlide-1)) {
					var it = itemsPerSlide - (totalItems - idx);
					for (var i=0; i<it; i++) {
							// append slides to end
							if (e.direction=="left") {
									$('#carouselcontrato_3 .carousel-item').eq(i).appendTo('#carouselcontrato_3 .carousel-inner');
							}
							else {
									$('#carouselcontrato_3 .carousel-item').eq(0).appendTo('#carouselcontrato_3 .carousel-inner');
							}
					}
			}
	});




	$(document).on('submit','.form-ajax',function(e){
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
				swal('Éxito', res.mensaje, 'success').then(function(){
					$('.modal').modal('hide');
					$('.tabla-ajax').bootgrid('reload');
					//location.href = res.url;
				});
			}
		}).fail(function(){
			loadingApp.desactivar();
			swal('Error', 'Error al conectarse con el servidor', 'error');
		});
		$(".form-ajax")[0].reset();
	});

	$(document).on('submit','.form-ajax-file',function(e){
		e.preventDefault();
		var $this = $(this);
		var data = new FormData(this);
		var url = $this.data('url');
		loadingApp.activar();
		$.ajax({
			type: 'POST',
			url: ajaxurl + '/' + url,
			data: data,
			processData: false,
			contentType: false
		}).then(function(res){
			loadingApp.desactivar();
			if(res.error){
				swal('Error', res.mensaje, 'error');
			} else {
				swal('Éxito', res.mensaje, 'success').then(function(){
					location.href = res.url;
				});
			}
		}).fail(function(){
			loadingApp.desactivar();
			swal('Error', 'Error al conectarse con el servidor', 'error');
		});
	});

	$(document).on('tk.change', '.chain-select', function(){
		var $this = $(this);
		var url = $this.data('url');
		var $parent = $($this.data('parent'));
		var selected = $this.data('selected');
		var data = {
			selected: selected
		};
		if($parent.length){
			data['valor'] = $parent.val();
		}
		$('.chain-select[data-parent="#'+$this.attr('id')+'"]').html('<option value="" selected disabled>Seleccionar...</option>');
		$.post(url, data).then(function(res){
			if(res.error){
				swal('Error', res.mensaje, 'error');
			} else {
				$this.html(res.html);
				//Llamada en cadena
				$('.chain-select[data-parent="#'+$this.attr('id')+'"]').trigger('tk.change');
			}
		}.bind(this)).fail(function(){
			swal('Error', 'Error al conectarse con el servidor', 'error');
		});
	}).on('change', ".chain-select",function(){
		var $this = $(this);
		var $parent = $($this.data('parent'));
		$this.removeData('selected');
		$('.chain-select[data-parent="#'+$this.attr('id')+'"]').trigger('tk.change');
	});
	$('.chain-select:not([data-parent])').trigger('tk.change');

	//Wysiwyg
	if($('textarea.editor').length){
		tinymce.init({
            selector: "textarea.editor",
            theme: "modern",
            height: 300,
            plugins: [
                "advlist autolink link image lists preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality paste textcolor"
            ],
            toolbar: "insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview media fullpage | forecolor backcolor ",
            style_formats: [],
            language: 'es',
            language_url: siteurl+'/sistema/assets/plugins/tinymce/es.js',
            setup: function (editor) {
		        editor.on('change', function () {
		            editor.save();
		        });
		    }
        });
	}





});
