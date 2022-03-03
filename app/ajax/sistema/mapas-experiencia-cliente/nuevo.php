<?php
global $tekodb;
if(empty($_POST['datos'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
if(empty($_POST['idGiro'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$data = $_POST['datos'];
$res = $tekodb->insert(
	'customer_journey_map',
	array(
		'Nombre' => $data['Nombre'],
		'fkidGirosEmpresariales' => $_POST['idGiro']
	),
	array(
	)
);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al crear el mapa de experiencia de usuario'),'error' => $res]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('El momento verdad se ha creado correctamente')]);
}
