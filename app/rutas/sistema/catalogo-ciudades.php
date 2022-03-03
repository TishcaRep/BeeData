<?php
//-----------------------(Catalogo Ciudades)-------------------------------------------------
$rutas->get('dashboard/catalogos/ciudades', function($View){
		global $titulo;
		$titulo = _('Ciudades');
		$View->config(array('layout' => 'plantillas/sistema'));
		$View->display('sistema/ciudades/ciudades');
});

$rutas->get('dashboard/catalogos/ciudad/editar/:id',function($View, $Params,$Scope){
		global $titulo,$tekodb;
		$titulo = _('Editar ciudad');
		$Scope->ciudad = $tekodb->get_row("SELECT * FROM catalogo_ciudad WHERE idCatalogoCiudad = {$Params->id}");
		$View->config(array('layout' => 'plantillas/sistema'));
		$View->display('sistema/ciudades/editar');
});

/* AJAX */

//-----------------------(Ciudades)-----------------------------------------------------------
$rutas->post('ajax/sistema/ciudades',function(){
     require_once(APPPATH.'/ajax/sistema/ciudades/index.php');
});
$rutas->post('ajax/sistema/ciudades/cargamasiva',function(){
     require_once(APPPATH.'/ajax/sistema/ciudades/cargamasiva.php');
});
$rutas->post('ajax/sistema/ciudades/deleteall',function(){
     require_once(APPPATH.'/ajax/sistema/ciudades/deleteall.php');
});
$rutas->post('ajax/sistema/ciudad/consulta',function(){
     require_once(APPPATH.'/ajax/sistema/ciudades/consulta.php');
});
$rutas->post('ajax/sistema/ciudad/editar',function(){
     require_once(APPPATH.'/ajax/sistema/ciudades/editar.php');
});
$rutas->post('ajax/sistema/ciudad/eliminar',function(){
     require_once(APPPATH.'/ajax/sistema/ciudades/eliminar.php');
});
$rutas->post('ajax/sistema/ciudad/nuevo',function(){
     require_once(APPPATH.'/ajax/sistema/ciudades/nuevo.php');
});
