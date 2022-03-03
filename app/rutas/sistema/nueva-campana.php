<?php

//Pages
$rutas->get('dashboard/refile/nueva-campana', function($View){
    global $titulo;
    $titulo = _('Nueva Campaña');
    $View->display('sistema/nueva-campana/index');
});
