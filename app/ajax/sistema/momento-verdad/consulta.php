<?php
global $tekodb;
if(empty($_POST['id'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$pregunta = $tekodb->get_row("SELECT * FROM vmomento_verdad WHERE id = {$_POST['id']}");
if(empty($pregunta)){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al consultar el momento verdad')]);
} else {
	teko_json(['error' => FALSE, 'data' => $pregunta]);
}
