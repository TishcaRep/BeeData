<?php
global $tekodb;
if(empty($_POST['id'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$giro = $tekodb->get_row("SELECT `idSemaforo` AS id,`Inicial`,`Final`,`Color` FROM `semaforizacion` WHERE `idSemaforo` = {$_POST['id']}");
if(empty($giro)){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al consultar el semaforo')]);
} else {
	teko_json(['error' => FALSE, 'data' => $giro]);
}
