<?php

/* PAGINAS */

$rutas->get('dashboard', function($View){
		global $titulo;
    $titulo = _('Inicio');
		$View->config(array('layout' => 'plantillas/sistema'));
    $View->display('sistema/index');
});

$rutas->get('dashboard/ingresar', function($View){
		global $titulo;
    $titulo = _('Ingresar');
		$View->config(array('layout' => 'plantillas/ingresar'));
    $View->display('sistema/ingresar');
});

$rutas->get('dashboard/cerrar-sesion', function(){
		tk_logout();
		teko_redirect(site_url('dashboard/ingresar'));
});
