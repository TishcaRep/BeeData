<?php
global $tekodb;
if(empty($_POST['CodigoPostal'])){
	teko_json(['error' => TRUE, 'mensaje' => _('No se recibieron los datos necesarios')]);
}
$datos = $tekodb->get_results("SELECT * FROM catalogo_ciudad WHERE catalogo_ciudad.CodigoPostal ='{$_POST['CodigoPostal']}'  ORDER BY catalogo_ciudad.Colonia");
$Colonias = $tekodb->get_results("SELECT Colonia AS Nombre FROM catalogo_ciudad WHERE catalogo_ciudad.CodigoPostal ='{$_POST['CodigoPostal']}'  ORDER BY catalogo_ciudad.Colonia");

if(empty($datos)){
	teko_json(['error' => TRUE, 'mensaje' => _('Ocurrió un error al consultar la ciudad')]);
} else {
	teko_json(['error' => FALSE, 'data' => $datos,'Colonias'=>$Colonias]);
}
