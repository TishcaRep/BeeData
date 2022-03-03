<?php

//Pages
$rutas->get('dashboard/plan/planes-accion', function($View){
    global $titulo;
    $titulo = _('Planes de Acción');
    $View->display('sistema/planes-accion/index');
});
