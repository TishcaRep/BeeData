<?php
global $tekodb;
if(empty($_POST['datos'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$data = $_POST['datos'];
$res = $tekodb->insert(
	'planes_membresia',
	array(
		'Nombre' => $data['nombre'],
		'Monto'	=> $data['monto'],
		'PagoDiferido' => $data['diferido'],
		'LimiteClientes' => $data['clientes'],
		'LimiteEncuestas' => $data['encuestas'],
		'Duracion' => $data['duracion']
	)
);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al añadir la membresia')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('La membresia se ha registrado correctamente')]);
}
