<?php

//Pages
$rutas->get('dashboard/refile/resultados-campanas-refile', function($View){
    global $titulo;
    $titulo = _('Resultados de campañas');
    $View->display('sistema/resultados-campanas-refile/index');
});
