<?php
global $tekodb;
if(empty($_POST['datos'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$data = $_POST['datos'];
$res = $tekodb->insert(
	'grupo_respuestas',
	array(
		'NombreGrupo' => $data['Grupo'],
	)
);
$idg=$tekodb->insert_id;
$res = $tekodb->insert(	'respuestas',	array('Respuesta' => $data['r1r'],'Valor' => $data['r1v'],'fkidGrupoRespuestas'=>$idg));
$res = $tekodb->insert(	'respuestas',	array('Respuesta' => $data['r2r'],'Valor' => $data['r2v'],'fkidGrupoRespuestas'=>$idg));
$res = $tekodb->insert(	'respuestas',	array('Respuesta' => $data['r3r'],'Valor' => $data['r3v'],'fkidGrupoRespuestas'=>$idg));
$res = $tekodb->insert(	'respuestas',	array('Respuesta' => $data['r4r'],'Valor' => $data['r4v'],'fkidGrupoRespuestas'=>$idg));
$res = $tekodb->insert(	'respuestas',	array('Respuesta' => $data['r5r'],'Valor' => $data['r5v'],'fkidGrupoRespuestas'=>$idg));
if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al crear el grupo des respuestas')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('El grupo de respuestas se ha creado correctamente')]);
}
