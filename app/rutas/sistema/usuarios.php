<?php
//-----------------------(Usuario)-------------------------------------------------
$rutas->get('dashboard/usuario/',function($View, $Params,$Scope){
		global $titulo,$tekodb;
		$titulo = _('Datos de usuario');
		$View->config(array('layout' => 'plantillas/sistema'));
		$View->display('sistema/usuario/index');
});

$rutas->get('dashboard/usuario/editar/',function($View){
		global $titulo,$tekodb;
		$titulo = _('Editar contraseña');
		$View->config(array('layout' => 'plantillas/sistema'));
		$View->display('sistema/usuario');
});

/* AJAX */
//-----------------------(Usuarios)-----------------------------------------------------------
$rutas->post('ajax/sistema/usuarios/ingresar',function(){
     require_once(APPPATH.'/ajax/sistema/usuarios/ingresar.php');
});
$rutas->post('ajax/sistema/usuarios/consulta',function(){
     require_once(APPPATH.'/ajax/sistema/usuarios/consulta.php');
});
$rutas->post('ajax/sistema/usuario/updatepwd',function(){
     require_once(APPPATH.'/ajax/sistema/usuarios/updatepwd.php');
});
