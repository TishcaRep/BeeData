<?php
global $tekodb;
if(empty($_POST['datos']))
{
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar el momento verdad')]);
}
$temp = $_POST['datos'];
$data = [
'NombreGrupo' => $temp['Grupo']
];
$res = $tekodb->update('grupo_respuestas',$data,['idGrupoRespuestas' => $temp['idGrupo']]);
print_r($tekodb->last_error);
$respuestas=$temp['respuestas'];
$valores=$temp['valores'];
foreach ($respuestas as $key => $respuesta) {
	$dato=[
		'Respuesta' => $respuesta,
		'Valor'=> $valores[$key]
	];
$res = $tekodb->update('respuestas',$dato,['idRespuestas' => $key]);
}
if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al editar el grupo de respuestas'),'sql'=>$tekodb->last_error]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('El grupo de respuestas se ha editado correctamente')]);
}
