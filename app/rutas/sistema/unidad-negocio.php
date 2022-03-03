<?php

//Pages
$rutas->get('dashboard/unidades/unidad-negocio', function($View){
    global $titulo;
    $titulo = _('Unidades de negocio');
    $View->display('sistema/unidad-negocio/index');
});

$rutas->post('ajax/sistema/unidad-negocio', function(){
    require_once(APPPATH.'/ajax/sistema/unidad-negocio/index.php');
});

$rutas->post('ajax/sistema/unidad-negocio/nuevo', function(){
    require_once(APPPATH.'/ajax/sistema/unidad-negocio/nuevo.php');
});

$rutas->post('ajax/sistema/unidad-negocio/consultar', function(){
    require_once(APPPATH.'/ajax/sistema/unidad-negocio/consultar.php');
});

$rutas->post('ajax/sistema/unidad-negocio/editar', function(){
    require_once(APPPATH.'/ajax/sistema/unidad-negocio/editar.php');
});

$rutas->post('ajax/sistema/unidad-negocio/eliminar', function(){
    require_once(APPPATH.'/ajax/sistema/unidad-negocio/eliminar.php');
});
