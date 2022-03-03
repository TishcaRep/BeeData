<?php
//-----------------------(Momento Verdad)-------------------------------------------------
$rutas->get('dashboard/catalogos/momento-verdad', function($View){
		global $titulo;
		$titulo = _('Momento verdad');
		$View->config(array('layout' => 'plantillas/sistema'));
		$View->display('sistema/momento-verdad/index');
});

/* AJAX */
//-----------------------(Momento Verdad)-----------------------------------------------------------
$rutas->post('ajax/sistema/momento-verdad',function(){
     require_once(APPPATH.'/ajax/sistema/momento-verdad/index.php');
});

$rutas->post('ajax/sistema/momento-verdad/nuevo',function(){
     require_once(APPPATH.'/ajax/sistema/momento-verdad/nuevo.php');
});
$rutas->post('ajax/sistema/momento-verdad/consulta',function(){
     require_once(APPPATH.'/ajax/sistema/momento-verdad/consulta.php');
});
$rutas->post('ajax/sistema/momento-verdad/editar',function(){
     require_once(APPPATH.'/ajax/sistema/momento-verdad/editar.php');
});
$rutas->post('ajax/sistema/momento-verdad/eliminar',function(){
     require_once(APPPATH.'/ajax/sistema/momento-verdad/eliminar.php');
});
