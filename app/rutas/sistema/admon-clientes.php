<?php

//Pages
$rutas->get('dashboard/clientes/admon-clientes', function($View){
    global $titulo;
    $titulo = _('Admnistración de Clientes');
    $View->display('sistema/admon-clientes/index');
});
