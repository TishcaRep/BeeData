<?php
//-----------------------(membresia)-----------------------------------------------------------
$rutas->get('dashboard/catalogos/membresia', function($View){
		global $titulo;
		$titulo = _('Planes Membresia');
		$View->config(array('layout' => 'plantillas/sistema'));
		$View->display('sistema/membresia/index');
});

/* AJAX */
//-----------------------(membresia)-----------------------------------------------------------
$rutas->post('ajax/sistema/membresia',function(){
     require_once(APPPATH.'/ajax/sistema/membresia/index.php');
});

$rutas->post('ajax/sistema/membresia/eliminar',function(){
     require_once(APPPATH.'/ajax/sistema/membresia/eliminar.php');
});

$rutas->post('ajax/sistema/membresia/consulta',function(){
     require_once(APPPATH.'/ajax/sistema/membresia/consulta.php');
});

$rutas->post('ajax/sistema/membresia/editar',function(){
     require_once(APPPATH.'/ajax/sistema/membresia/editar.php');
});

$rutas->post('ajax/sistema/membresia/nuevo',function(){
     require_once(APPPATH.'/ajax/sistema/membresia/nuevo.php');
});
