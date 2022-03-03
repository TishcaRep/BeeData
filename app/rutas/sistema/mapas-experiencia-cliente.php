<?php
//-----------------------(Mapa de Experiencia de Usuario)-----------------------------------------------------------
$rutas->get('dashboard/catalogos/mapas-experiencia-cliente', function($View){
		global $titulo;
		$titulo = _('Mapas de Experencia de cliente');
		$View->config(array('layout' => 'plantillas/sistema'));
		$View->display('sistema/mapas-experiencia-cliente/index');
});


/* AJAX */
//-----------------------(Mapa de Experiencia de Usuario)------------------------------------------------------------
$rutas->post('ajax/sistema/mapas-experiencia-cliente',function(){
     require_once(APPPATH.'/ajax/sistema/mapas-experiencia-cliente/index.php');
});

$rutas->post('ajax/sistema/mapas-experiencia-cliente/eliminar',function(){
     require_once(APPPATH.'/ajax/sistema/mapas-experiencia-cliente/eliminar.php');
});

$rutas->post('ajax/sistema/mapas-experiencia-cliente/consulta',function(){
     require_once(APPPATH.'/ajax/sistema/mapas-experiencia-cliente/consulta.php');
});

$rutas->post('ajax/sistema/mapas-experiencia-cliente/editar',function(){
     require_once(APPPATH.'/ajax/sistema/mapas-experiencia-cliente/editar.php');
});

$rutas->post('ajax/sistema/mapas-experiencia-cliente/nuevo',function(){
     require_once(APPPATH.'/ajax/sistema/mapas-experiencia-cliente/nuevo.php');
});

$rutas->post('ajax/sistema/mapas-experiencia-cliente/ver',function(){
     require_once(APPPATH.'/ajax/sistema/mapas-experiencia-cliente/ver.php');
});
