<?php
global $tekodb,$tabla,$search;
$tabla = 'planes_membresia';
$pagina = intval($_POST['current']);
$muestra = intval($_POST['rowCount']);
$empieza = ($pagina - 1) * $muestra;
$termina = $muestra;
$search = !empty($_POST['searchPhrase']) ?  $_POST['searchPhrase'] : '';
$orden = (array_key_exists('sort', $_POST)) ? $_POST['sort'] : array();
$orderby = [];
foreach($orden as $k=>$v){
	$orderby[]="$k $v";
}
$filtros = array();
parse_str($_POST['filtros'],$filtros);
$str_filtros = count($filtros) ?  where_filtros($filtros) : "WHERE ({$tabla}.Nombre LIKE '%{$search}%' OR {$tabla}.idPlanesMembresia LIKE '%{$search}%')";
$args_query = $str_filtros;
$args_query .= (count($orderby)) ? " ORDER BY ".implode(",",$orderby) : "";
$args_query_tot = $args_query;
$args_query .= (($muestra>0) ? " LIMIT {$empieza},{$termina}" : "");
$query = "SELECT  `idPlanesMembresia` as id, `Nombre`, `Monto`, `PagoDiferido`, `LimiteClientes`,`LimiteEncuestas`, `Duracion`  FROM {$tabla} {$args_query}";
$resultados = $tekodb->get_results($query);
$total = (strlen($str_filtros)) ? $tekodb->get_var("SELECT COUNT(*) FROM {$tabla} {$args_query_tot}") : $tekodb->get_var("SELECT COUNT(*) FROM {$tabla}");
$resultados = !empty($resultados) ? $resultados : [];

teko_json(["error" => FALSE, "current" => $pagina, "rowCount" => $muestra, "rows" => $resultados, "total" => $total, 'query' => $query]);

//Convierte un array en una sentencia where para los filtros
function where_filtros($filtros){
    global $tekodb,$tabla,$search;
    $where = array("");
    foreach ($filtros as $k => $v) {
        if(empty($v)) continue;
        switch ($k) {
            default:
                $where[] = "CAST({$tabla}.{$k} AS CHAR) ".(is_array($v) ? "IN ('".implode("','", $v)."')" :  "= '{$v}'");
                break;
        }
    }
    return count($where) ? "WHERE ({$tabla}.NombreEnfoque LIKE '%{$search}%' OR {$tabla}.idEnfoque LIKE '%{$search}%') AND ".implode(' AND ',$where) : "";
}
