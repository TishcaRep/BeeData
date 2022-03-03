<?php

//Pages
$rutas->get('dashboard/analisis/analisis-datos', function($View){
    global $titulo;
    $titulo = _('Análisis de Datos');
    $View->display('sistema/analisis-datos/index');
});
