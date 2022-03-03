<?php
global $tekodb;
if(empty($_POST['id'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$membresia = $tekodb->get_row("SELECT `idPlanesMembresia` AS id , `Nombre`, `Monto`, `PagoDiferido`, `LimiteClientes`, `LimiteEncuestas`, `Duracion` FROM planes_membresia WHERE idPlanesMembresia = {$_POST['id']}");
if(empty($membresia)){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al consultar la membresia')]);
} else {
	teko_json(['error' => FALSE, 'data' => $membresia]);
}
