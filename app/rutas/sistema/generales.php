<?php
/* AJAX */

$rutas->post('ajax/sistema/generales/giros-empresariales',function(){
     require_once(APPPATH.'/ajax/sistema/generales/giros-empresariales.php');
});

$rutas->post('ajax/sistema/generales/mapa-experiencia-cliente',function(){
     require_once(APPPATH.'/ajax/sistema/generales/mapa-experiencia-cliente.php');
});

$rutas->post('ajax/sistema/generales/sucursales',function(){
     require_once(APPPATH.'/ajax/sistema/generales/sucursales.php');
});

$rutas->post('ajax/sistema/generales/codigos-postales',function(){
     require_once(APPPATH.'/ajax/sistema/generales/codigos-postales.php');
});

$rutas->post('ajax/sistema/generales/administradores',function(){
     require_once(APPPATH.'/ajax/sistema/generales/administradores.php');
});

$rutas->post('ajax/sistema/generales/unidades',function(){
     require_once(APPPATH.'/ajax/sistema/generales/unidades.php');
});
