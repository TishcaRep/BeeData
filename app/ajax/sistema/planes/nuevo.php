<?php
global $tekodb;
if(empty($_POST['datos'])){
	on(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}

$datos = $_POST['datos'];

$datosEstado = $tekodb->get_row("SELECT * FROM catalogo_ciudad WHERE catalogo_ciudad.CodigoPostal ='{$datos['CodigoPostal']}'");

$data = array(
	'fkidGirosEmpresariales' => $datos['Giro'],
	'Nombre' => $datos['Nombre'],
	'Estado' => $datosEstado->Estado,
	'Municipio' => $datosEstado->Municipio,
	'Colonia' => $datos['Colonia'],
	'CodigoPostal' => $datos['CodigoPostal'],
	'Calle' => $datos['Calle'],
	'NumeroInt' => $datos['NumInt'],
	'NumeroExt' => $datos['NumExt'],
	'Latitud' => 0,
	'Longitud' => 0
);

$res = $tekodb->insert('unidad_negocio',$data);
$idU = $tekodb->insert_id;

$res = $tekodb->insert('administrador_unidad_negocio',['fkusers_id' => tk_id(),'fkidUnidadNegocio' => $idU]);
if(!empty($_POST['tipo'])){
	if ($_POST['tipo']===TRUE) {
		$tekodb->insert('sucursales',['fkidUnidadNegocio' => $datos['Sucursal'],'fkidSucursal'=>$idU]);
	}
}

$datosf = $_POST['datosf'];

if(empty($_POST['facturacion'])){
	$datosEstado = $tekodb->get_row("SELECT * FROM catalogo_ciudad WHERE catalogo_ciudad.CodigoPostal ='{$datosf['CodigoPostal']}'");
	$data = array(
		'fkidUnidadNegocio' => $idU,
		'Rfc' => $datosf['rfc'],
		'Denominacion' => $datosf['Nombre'],
		'Estado' => $datosEstado->Estado,
		'Municipio' => $datosEstado->Municipio,
		'Colonia' => $datosf['Colonia'],
		'CodigoPostal' => $datos['CodigoPostal'],
		'Calle' => $datosf['calle'],
		'NumeroInt' => $datosf['NumInt'],
		'NumeroExt' => $datosf['NumExt']);
}
else {
	$data = $tekodb->get_row("SELECT * FROM facturacion WHERE fkidUnidadNegocio ='{$datosf['facturacion']}'", ARRAY_A);
	$data['fkidUnidadNegocio'] =  $idU;
}
$res = $tekodb->insert('facturacion',$data);


if($res === FALSE){
	on(['error' => TRUE, 'mensaje' => _('Ocurrió un error al añadir la unida de negocio')]);
} else {
	teko_json(['error' => FALSE, 'mensaje' => _('La unidad de negocio se ha registrado correctamente')]);
}
