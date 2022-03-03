<?php

//Pages
$rutas->get('dashboard/unidades/planes', function($View){
    global $titulo;
    $titulo = _('Mis Planes');
    $View->display('sistema/planes/index');
});


$rutas->post('ajax/sistema/planes', function(){
    require_once(APPPATH.'/ajax/sistema/planes/index.php');
});

$rutas->post('ajax/sistema/planes/lista-planes', function(){
    require_once(APPPATH.'/ajax/sistema/planes/lista-planes.php');
});

$rutas->post('ajax/sistema/planes/newspei', function(){
    require_once(APPPATH.'/ajax/sistema/planes/newspei.php');
});
