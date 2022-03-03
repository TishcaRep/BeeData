<?php

//Pages
$rutas->get('dashboard/unidades/vinculacion-unidad', function($View){
    global $titulo;
    $titulo = _('Vinculación de Unidades');
    $View->display('sistema/vinculacion-unidad/index');
});


$rutas->post('ajax/sistema/vinculacion-unidad', function(){
    require_once(APPPATH.'/ajax/sistema/vinculacion-unidad/index.php');
});

$rutas->post('ajax/sistema/vinculacion-unidad/nuevo', function(){
    require_once(APPPATH.'/ajax/sistema/vinculacion-unidad/nuevo.php');
});

$rutas->post('ajax/sistema/vinculacion-unidad/eliminar', function(){
    require_once(APPPATH.'/ajax/sistema/vinculacion-unidad/eliminar.php');
});
