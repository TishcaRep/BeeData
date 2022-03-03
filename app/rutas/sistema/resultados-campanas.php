<?php

//Pages
$rutas->get('dashboard/campanas/resultados-campanas', function($View){
    global $titulo;
    $titulo = _('Resultados de las campañas');
    $View->display('sistema/resultados-campanas/index');
});
