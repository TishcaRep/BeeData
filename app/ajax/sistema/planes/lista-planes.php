<?php
global $tekodb;
$data = $tekodb->get_results("SELECT `idPlanesMembresia` AS id , `Nombre`, `Monto`, `PagoDiferido`, `LimiteClientes`, `LimiteEncuestas`, `Duracion` FROM planes_membresia");
if (empty($data)) {
	teko_json(['error' => FALSE, 'mensaje' => _('Error al realizar la consulta')]);
} else {
 teko_json(['error' => FALSE, 'mensaje' => _('La consulta correcatamente'),'data' => $data]);
}
