<?php
global $tekodb;

$giros = $tekodb->get_results("SELECT * FROM giros_empresariales");
$selected = !empty($_POST['selected']) ? $_POST['selected'] : 0;
$html = ($selected) ? "<option value=''>Seleccionar...</option>" : "<option value='' selected>Seleccionar...</option>";
if($giros !== null){
  foreach($giros as $giro){
    $html .= "<option value='{$giro->idGirosEmpresariales}' ".(($selected == $giro->idGirosEmpresariales) ? 'selected' : '').">{$giro->Nombre}</option>\n";
	}
	teko_json(['error' => FALSE, 'html' => $html]);
} else {
	teko_json(['error' => FALSE, 'html' => $html]);
}
