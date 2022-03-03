<?php
global $tekodb;
if(empty($_POST['datos'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$datos = $_POST['datos'];
$res = tk_register($datos['email'],$datos['password'],$role = 'usuario',$datos['usuario'],false,[]);
if($res['error'])
{
	teko_json(['error' => TRUE, 'mensaje' => _('Este registro ya existe')]);
}
$idAdmin = $res['id'];
$tekodb->insert('administrador',[
	'fkusers_id'=>$idAdmin,
	'Nombre'=>$datos['Nombre'],
	'ApPaterno'=>$datos['ApPaterno'],
	'ApMaterno'=>$datos['ApMaterno'],
	'Cargo'=>$datos['cargo'],
	'Telefono'=>$datos['telefono']
]);
$tekodb->insert('usuarios_dependientes',[
	'fkiduser_parent'=>tk_id(),
	'fkiduser_son'=>$idAdmin
]);

if($res === FALSE){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al añadir la unida de negocio')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('La semaforización se ha registrado correctamente'),'res'=>$res]);
}
