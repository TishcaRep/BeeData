<?php
//-----------------------(semaforizacion)-----------------------------------------------------------
$rutas->get('dashboard/catalogos/semaforizacion', function($View){
		global $titulo;
		$titulo = _('Semaforizacion');
		$View->config(array('layout' => 'plantillas/sistema'));
		$View->display('sistema/semaforizacion/index');
});

/* AJAX */
//-----------------------(semaforizacion)-----------------------------------------------------------
$rutas->post('ajax/sistema/semaforizacion',function(){
     require_once(APPPATH.'/ajax/sistema/semaforizacion/index.php');
});

$rutas->post('ajax/sistema/semaforizacion/eliminar',function(){
     require_once(APPPATH.'/ajax/sistema/semaforizacion/eliminar.php');
});

$rutas->post('ajax/sistema/semaforizacion/consulta',function(){
     require_once(APPPATH.'/ajax/sistema/semaforizacion/consulta.php');
});

$rutas->post('ajax/sistema/semaforizacion/editar',function(){
     require_once(APPPATH.'/ajax/sistema/semaforizacion/editar.php');
});

$rutas->post('ajax/sistema/semaforizacion/nuevo',function(){
     require_once(APPPATH.'/ajax/sistema/semaforizacion/nuevo.php');
});
