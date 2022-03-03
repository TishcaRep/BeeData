<?php
global $tekodb;
$cjms = null;
if (!empty($_POST['valor'] )) {
	$cjms = $tekodb->get_results("SELECT * FROM customer_journey_map WHERE fkidGirosEmpresariales = {$_POST['valor']} ORDER BY Nombre ");
}
$selected = !empty($_POST['selected']) ? $_POST['selected'] : 0;
$html = ($selected) ? "<option value=''>Seleccionar...</option>" : "<option value='' selected>Seleccionar...</option>";
if($cjms !== null){
  foreach($cjms as $cjm){
    $html .= "<option value='{$cjm->idCustomer_journey_map}' ".(($selected == $cjm->idCustomer_journey_map) ? 'selected' : '').">{$cjm->Nombre}</option>\n";
	}
	teko_json(['error' => FALSE, 'html' => $html,'data' => $cjms]);
} else {
	teko_json(['error' => FALSE, 'html' => $html,'data' => $cjms]);
}
