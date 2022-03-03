<?php

//Pages
$rutas->get('dashboard/campanas/avances-campanas', function($View){
    global $titulo;
    $titulo = _('Avances de las campañas');
    $View->display('sistema/avances-campanas/index');
});
