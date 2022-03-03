<?php

//Pages
$rutas->get('dashboard/catalogos/respuestas', function($View){
    global $titulo;
    $titulo = _('Respuestas');
    $View->display('sistema/respuestas/index');
});

//AJAX
$rutas->post('ajax/sistema/respuestas', function(){
    require_once(APPPATH.'/ajax/sistema/respuestas/index.php');
});

$rutas->post('ajax/sistema/respuestas/nuevo', function(){
    require_once(APPPATH.'/ajax/sistema/respuestas/nuevo.php');
});

$rutas->post('ajax/sistema/respuestas/consultar', function(){
    require_once(APPPATH.'/ajax/sistema/respuestas/consultar.php');
});

$rutas->post('ajax/sistema/respuestas/editar', function(){
    require_once(APPPATH.'/ajax/sistema/respuestas/editar.php');
});

$rutas->post('ajax/sistema/respuestas/eliminar', function(){
    require_once(APPPATH.'/ajax/sistema/respuestas/eliminar.php');
});
