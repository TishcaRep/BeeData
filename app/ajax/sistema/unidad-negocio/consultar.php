<?php
global $tekodb;
if(empty($_POST['id']){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$data = $tekodb->get_row("SELECT * FROM unidad_negocio WHERE idUnidadNegocio ={$_POST['id']}");
echo $tekodb->last_query;
if (empty($data)) {
	teko_json(['error' => FALSE, 'mensaje' => _('Error al realizar la consulta')]);
} else {
 teko_json(['error' => FALSE, 'mensaje' => _('La unidad se a creado correcatamente')]);
}
