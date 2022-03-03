<?php 
global $tekoconfig;

$titulo = 'No se pudo conectar con la base de datos';

$mensaje = <<<EOD
<h3>¿Hola? ¿Hay alguien ahí?</h3>
<p>No se pudo establecer una conexión con la base de datos <strong>{$tekoconfig['db']['name']}</strong>.</p> <p>Verifique sus datos de acceso por favor.</p>
EOD;

require_once(APPPATH.'/teko-core/assets/error/index.php');