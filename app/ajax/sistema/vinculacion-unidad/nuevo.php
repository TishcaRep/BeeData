<?php
global $tekodb;
if(empty($_POST['datos'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$data = $_POST['datos'];
$res = $tekodb->insert('administrador_unidad_negocio',['fkusers_id'=>$data['idAdmin'],'fkidUnidadNegocio'=>$data['idUnidad']]);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al añadir la vinculacion')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('La vinculacion se ha registrado correctamente')]);
}
