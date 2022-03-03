<?php
global $tekodb;
if(empty($_POST['datos'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$data = $_POST['datos'];
$res = $tekodb->insert(
	'catalogo_ciudad',
	array(
		'Estado' => $data['Estado'],
		'Municipio' => $data['Municipio'],
		'Colonia' => $data['Colonia'],
		'CodigoPostal' => $data['CodigoPostal']
	)
);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar la ciudad')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('La ciudad se ha creado correctamente'), 'url' => site_url('dashboard/catalogos/ciudades')]);
}
