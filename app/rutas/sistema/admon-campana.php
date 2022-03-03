<?php

//Pages
$rutas->get('dashboard/refile/admon-campana', function($View){
    global $titulo;
    $titulo = _('Admnistración de Campaña');
    $View->display('sistema/admon-campana/index');
});
