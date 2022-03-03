<?php

/*
 En este archivo se definirán las rutas de la aplicación.
*/
$rutas->home(function($View, $Params, $Scope){
    global $titulo;
    $titulo = _('Inicio');
    $View->config(['layout' => 'plantillas/sitio']);
    $View->display('index');

});

$rutas->otherwise(function($View){
    global $titulo;
    $titulo = '404';
    http_response_code(404);
    $View->config(array('layout' => 'plantillas/404'));
    $View->display('404');
});

$rutas->get('contratacion',function($View){
  global $titulo;
  $titulo = _('Contratación');
  $View->config(array('layout' => 'plantillas/instalador'));
  $View->display('sistema/instalador/index');
});

$rutas->post('api/contratacion', function(){
    require_once(APPPATH.'/api/contratacion.php');
});


/* SISTEMA */
require_once('rutas/sistema/index.php');
require_once('rutas/sistema/usuarios.php');
require_once('rutas/sistema/mapas-experiencia-cliente.php');
require_once('rutas/sistema/giros-empresariales.php');
require_once('rutas/sistema/catalogo-ciudades.php');
require_once('rutas/sistema/momento-verdad.php');
require_once('rutas/sistema/respuestas.php');
require_once('rutas/sistema/generales.php');
require_once('rutas/sistema/membresia.php');
require_once('rutas/sistema/semaforizacion.php');
require_once('rutas/sistema/nueva-unidad.php');
require_once('rutas/sistema/lista-unidad.php');
require_once('rutas/sistema/vinculacion-unidad.php');
require_once('rutas/sistema/planes.php');
require_once('rutas/sistema/campanas-satisfaccion.php');
require_once('rutas/sistema/avances-campanas.php');
require_once('rutas/sistema/resultados-campanas.php');
require_once('rutas/sistema/planes-accion.php');
require_once('rutas/sistema/analisis-datos.php');
require_once('rutas/sistema/nueva-campana.php');
require_once('rutas/sistema/nueva-campana.php');
require_once('rutas/sistema/admon-campana.php');
require_once('rutas/sistema/resultados-campanas-refile.php');
require_once('rutas/sistema/admon-clientes.php');
