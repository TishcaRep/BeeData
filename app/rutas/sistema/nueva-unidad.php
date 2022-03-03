<?php

//Pages
$rutas->get('dashboard/unidades/nueva-unidad', function($View){
    global $titulo;
    $titulo = _('Nueva Unidad');
    $View->display('sistema/nueva-unidad/index');
});



