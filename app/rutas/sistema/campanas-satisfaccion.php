<?php

//Pages
$rutas->get('dashboard/campanas/campanas-satisfaccion', function($View){
    global $titulo;
    $titulo = _('Campañas de Satisfacción');
    $View->display('sistema/campanas-satisfaccion/index');
});

$rutas->post('ajax/sistema/campanas-satisfaccion/',function(){
     require_once(APPPATH.'/ajax/sistema/campanas-satisfaccion/index.php');
});
