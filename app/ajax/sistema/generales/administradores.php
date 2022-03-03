<?php
global $tekodb;
$idU = tk_id();
$usuarios = $tekodb->get_results("SELECT * FROM vadninistradores WHERE idP ={$idU}");
$selected = !empty($_POST['selected']) ? $_POST['selected'] : 0;
$html = ($selected) ? "<option value=''>Seleccionar...</option>" : "<option value='' selected>Seleccionar...</option>";
if($usuarios !== null){
  foreach($usuarios as $usuario){
    $html .= "<option value='{$usuario->id}' ".(($selected == $usuario->id) ? 'selected' : '').">{$usuario->Nombre}</option>\n";
	}
	teko_json(['error' => FALSE, 'html' => $html]);
} else {
	teko_json(['error' => FALSE, 'html' => $html]);
}
