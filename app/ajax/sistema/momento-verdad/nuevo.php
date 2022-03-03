<?php
global $tekodb;
if(empty($_POST['datos'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$data = $_POST['datos'];
$res = $tekodb->insert(
	'momento_verdad',
	array(
		'fkidGirosEmpresariales' => $data['idG'],
		'fkidCoustomer_journey_map' => $data['idCjm'],
		'Momento_verdad' => $data['Momento']
	)
);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al crear la pregunta')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('la pregunta se ha creado correctamente')]);
}
