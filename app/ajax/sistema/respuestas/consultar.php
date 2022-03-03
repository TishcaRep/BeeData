<?php
global $tekodb;
$i=0;$caritas=['far fa-grin-beam','far fa-smile','far fa-meh','far fa-meh-rolling-eyes','far fa-angry'];
if(empty($_POST['id'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$respuestas = $tekodb->get_results("SELECT * FROM vgrupos_respuestas WHERE id = {$_POST['id']}");
if(empty($respuestas)){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al consultar los grupos de respuestas')]);
} else {
	foreach ($respuestas as $respuesta) {
		$respuesta->carita =  $caritas[$i];
		$i++;
	}
	teko_json(['error' => FALSE, 'data' => $respuestas]);
}
