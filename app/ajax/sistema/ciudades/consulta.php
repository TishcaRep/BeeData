<?php
global $tekodb;
if(empty($_POST['id'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$ciudad = $tekodb->get_row("SELECT * FROM catalogo_ciudad WHERE idCatalogoCiudad = {$_POST['id']}");

if(empty($ciudad)){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al consultar la ciudad')]);
} else {
	teko_json(['error' => FALSE, 'data' => $ciudad]);
}
