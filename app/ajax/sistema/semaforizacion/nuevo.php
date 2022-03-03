<?php
global $tekodb;
if(empty($_POST['datos'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$data = $_POST['datos'];
$res = $tekodb->insert(
	'semaforizacion',
	array(
		'Inicial'=> $data['Inicial'],
		'Final'=> $data['Final'],
		'Color'=> $data['Color']
	)
);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al añadir la semaforización')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('La semaforización se ha registrado correctamente')]);
}
