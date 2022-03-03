<?php

//Redireccionar
function teko_redirect($url, $codigo = 303){
    header('Location: ' . $url, true, $codigo);
    die();
}

//Respuesta para Ajax en JSON
function teko_json($arr){
    header('Content-Type: application/json');
    die(json_encode($arr));
}

//Url del sito
function site_url($pagina = ''){
    global $site_url;
    return $site_url.'/'.$pagina;
}

//Nombre el archivo
function get_nombre_archivo($id_archivo){
	global $tekodb;
	$nombre = $tekodb->get_var("SELECT nombre FROM archivos WHERE id_archivo = {$id_archivo}");
	return !empty($nombre) ? $nombre : 'not-found';
}

//Url del archivo
function get_url_archivo($id_archivo){
	return site_url('archivos/'.get_nombre_archivo($id_archivo));
}

//Path del archivo
function get_path_archivo($id_archivo){
	global $tekodb;
	return ABSPATH.'/archivos/'.get_nombre_archivo($id_archivo);
}

//Eliminación fisica y lógica del archivo
function eliminar_archivo($id_archivo){
	global $tekodb;
	//Fisico
	$path = get_path_archivo($id_archivo);
	if(file_exists($path)){
		unlink($path);	
	}
	//Logico
	$tekodb->delete('archivos', compact('id_archivo'));
}

//Reorganizar array archivos multiples
function reorganizar_array_archivos(&$file_post) {
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);
    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }
    return $file_ary;
}

//Convertir string a slug
function to_slug($str, $delimiter = '-'){
	$unwanted_array = ['á' =>'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ñ' => 'n']; 
    $str = strtr( mb_strtolower($str) , $unwanted_array );
    $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
    return $slug;
}