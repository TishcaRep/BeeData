<?php

//Pages
$rutas->get('dashboard/unidades/lista-unidad', function($View){
    global $titulo;
    $titulo = _('Lista de Unidades');
    $View->display('sistema/lista-unidad/index');

});

$rutas->post('ajax/sistema/lista-unidad', function(){
    require_once(APPPATH.'/ajax/sistema/lista-unidad/index.php');
});

$rutas->post('ajax/sistema/lista-unidad/nuevo', function(){
    require_once(APPPATH.'/ajax/sistema/lista-unidad/nuevo.php');
});

$rutas->post('ajax/sistema/lista-unidad/consultar', function(){
    require_once(APPPATH.'/ajax/sistema/lista-unidad/consultar.php');
});

$rutas->post('ajax/sistema/lista-unidad/editar', function(){
    require_once(APPPATH.'/ajax/sistema/lista-unidad/editar.php');
});

$rutas->post('ajax/sistema/lista-unidad/eliminar', function(){
    require_once(APPPATH.'/ajax/sistema/lista-unidad/eliminar.php');
});
