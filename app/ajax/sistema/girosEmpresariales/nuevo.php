<?php
global $tekodb;
if(empty($_POST['datos'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$data = $_POST['datos'];
$res = $tekodb->insert(
	'giros_empresariales',
	array(
		'Nombre' => $data['Nombregiro']
	),
	array(
		'%s'
	)
);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al crear el giro empresarial')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('El giro empresarial se ha creado correctamente'), 'url' => site_url('dashboard/catalogos/giroempresarial')]);
}
