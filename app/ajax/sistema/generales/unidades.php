<?php
global $tekodb;
$units = null;
if (!empty($_POST['valor'] )) {
	$id=tk_id();
	$units = $tekodb->get_results("SELECT `vunidad_negocio`.`id`, `vunidad_negocio`.`Nombre`"
		 ."FROM `vunidad_negocio`".
		 " WHERE vunidad_negocio.idU = {$id} AND ".
		 " vunidad_negocio.id NOT IN".
		 "(SELECT	vunidad_negocio.id  FROM  `vunidad_negocio` WHERE  idU = {$_POST['valor']} )");
}
$selected = !empty($_POST['selected']) ? $_POST['selected'] : 0;
$html = ($selected) ? "<option value=''>Seleccionar...</option>" : "<option value='' selected>Seleccionar...</option>";
if($units !== null){
  foreach($units as $unit){
    $html .= "<option value='{$unit->id}' ".(($selected == $unit->id) ? 'selected' : '').">{$unit->Nombre}</option>\n";
	}
	teko_json(['error' => FALSE, 'html' => $html]);
} else {
	teko_json(['error' => FALSE, 'html' => $html]);
}
