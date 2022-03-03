<?php
//-----------------------(Giros Empresariales)-------------------------------------------------
$rutas->get('dashboard/catalogos/giroempresarial', function($View){
		global $titulo;
		$titulo = _('Giros Empresariales');
		$View->config(array('layout' => 'plantillas/sistema'));
		$View->display('sistema/girosEmpresariales/giros');
});

/* AJAX */

//-----------------------(Giros Empresariales)-----------------------------------------------------------
$rutas->post('ajax/sistema/girosEmpresariales',function(){
     require_once(APPPATH.'/ajax/sistema/girosEmpresariales/index.php');
});

$rutas->post('ajax/sistema/girosEmpresariales/eliminar',function(){
     require_once(APPPATH.'/ajax/sistema/girosEmpresariales/eliminar.php');
});

$rutas->post('ajax/sistema/girosEmpresariales/consulta',function(){
     require_once(APPPATH.'/ajax/sistema/girosEmpresariales/consulta.php');
});

$rutas->post('ajax/sistema/girosEmpresariales/editar',function(){
     require_once(APPPATH.'/ajax/sistema/girosEmpresariales/editar.php');
});

$rutas->post('ajax/sistema/girosEmpresariales/nuevo',function(){
     require_once(APPPATH.'/ajax/sistema/girosEmpresariales/nuevo.php');
});
