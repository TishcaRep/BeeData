<?php
global $tekodb;
if(empty($_POST['id'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$giro = $tekodb->get_row("SELECT idGirosEmpresariales as id,Nombre FROM giros_empresariales WHERE idGirosEmpresariales= {$_POST['id']}");
if(empty($giro)){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al consultar el giro')]);
} else {
	teko_json(['error' => FALSE, 'data' => $giro]);
}
