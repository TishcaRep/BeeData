<?php

//Menu del sistema
$sidebar_menu = [
	['nombre' =>  _('Inicio'), 'icono' => 'fa fa-home', 'url' => site_url('dashboard')],
	['nombre' => _('Catálogos Globales'), 'icono' => 'fas fa-folder-open', 'hijos' => [
		//['nombre' => 'Datos de acceso', 'icono' => 'fa fa-clock-o', 'url' => '#'],
		//['nombre' => 'Catálogos', 'icono' => 'fa fa-clock-o', 'url' => '#'],
		['nombre' =>  _('Ciudades'), 'icono' => 'fas fa-city', 'url' => site_url('dashboard/catalogos/ciudades')],
		['nombre' =>  _('Giros empresariales'), 'icono' => 'fas fa-briefcase', 'url' => site_url('dashboard/catalogos/giroempresarial')],
		['nombre' =>  _('Mapa Experiencia Usuario'), 'icono' => 'fas fa-users', 'url' =>site_url('dashboard/catalogos/mapas-experiencia-cliente')],
		['nombre' =>  _('Momento Verdad'), 'icono' => 'fas fa-user-check', 'url' => site_url('dashboard/catalogos/momento-verdad')],
		['nombre' =>  _('Planes de Membresia'), 'icono' => 'fas fa-money-check-alt', 'url' => site_url('dashboard/catalogos/membresia')],
		['nombre' =>  _('Repuestas'), 'icono' => 'fa fa-comment-o', 'url' => site_url('dashboard/catalogos/respuestas')],
		['nombre' =>  _('Semaforización'), 'icono' => 'fas fa-traffic-light', 'url' => site_url('dashboard/catalogos/semaforizacion')]
	]],

	['nombre' => _('Mis Unidades de Negocio'), 'icono' => 'fas fa-briefcase', 'hijos' => [
		['nombre' =>  _('Unidades de Negocio'), 'icono' => 'fas fa-building', 'url' => site_url('dashboard/unidades/nueva-unidad')],
		['nombre' =>  _('Administradores de Unidades de Negocio'), 'icono' => 'fas fa-user-tie', 'url' => site_url('dashboard/unidades/lista-unidad')],
		['nombre' =>  _('Vinculación de Unidades de Negocio'), 'icono' => 'fas fa-address-book', 'url' => site_url('dashboard/unidades/vinculacion-unidad')],
		['nombre' =>  _('Mis Planes'), 'icono' => 'fas fa-credit-card', 'url' => site_url('dashboard/unidades/planes')],
	]],

	['nombre' => _('Campañas Experiencia de Cliente'), 'icono' => 'fas fa-users', 'hijos' => [
		['nombre' =>  _('Campañas de Satisfacción'), 'icono' => 'fas fa-tasks', 'url' => site_url('dashboard/campanas/campanas-satisfaccion')],
		['nombre' =>  _('Avances de Campañas'), 'icono' => 'far fa-check-square', 'url' => site_url('dashboard/campanas/avances-campanas')],
		['nombre' =>  _('Resultados de Campañas'), 'icono' => 'fas fa-clipboard-check', 'url' => site_url('dashboard/campanas/resultados-campanas')],
	]],

	['nombre' => _('Plan de Acción'), 'icono' => 'fas fa-chalkboard-teacher', 'hijos' => [
		['nombre' =>  _('Planes de Acción'), 'icono' => 'far fa-check-circle', 'url' => site_url('dashboard/plan/planes-accion')],
	]],

	['nombre' => _('Análisis de Campañas'), 'icono' => 'far fa-chart-bar', 'hijos' => [
		['nombre' =>  _('Análisis de datos'), 'icono' => 'fas fa-diagnoses', 'url' => site_url('dashboard/analisis/analisis-datos')],
	]],

	['nombre' => _('Campañas REFILE'), 'icono' => 'fas fa-undo', 'hijos' => [
		['nombre' =>  _('Nueva campaña'), 'icono' => 'fas fa-chart-bar', 'url' => site_url('dashboard/refile/nueva-campana')],
		['nombre' =>  _('Administración de campañas'), 'icono' => 'fas fa-chalkboard-teacher', 'url' => site_url('dashboard/refile/admon-campana')],
		['nombre' =>  _('Resultados de campañas'), 'icono' => 'fas fa-clipboard-check', 'url' => site_url('dashboard/refile/resultados-campanas-refile')],
	]],

	['nombre' => _('Clientes'), 'icono' => 'fas fa-user-friends', 'hijos' => [
		['nombre' =>  _('Administración de Clientes'), 'icono' => 'fas fa-users', 'url' => site_url('dashboard/clientes/admon-clientes')],
	]],
	/*['nombre' => 'Perfil', 'icono' => 'fas fa-user-tie', 'hijos' => [
		['nombre' => 'Datos de acceso', 'icono' => 'fa fa-clock-o', 'url' => '#'],
		['nombre' => 'Datos de acceso', 'icono' => 'fa fa-clock-o', 'url' => '#'],
		['nombre' => 'Datos de acceso', 'icono' => 'fa fa-clock-o', 'url' => '#'],
		['nombre' => 'Submenu', 'icono' => 'fa fa-clock-o', 'url' => site_url('dashboard/usuario')],
	]],
	['nombre' => 'Catalogos', 'icono' => 'fa fa-home', 'url' => site_url('catalogos')],
	['nombre' => 'Catalogos', 'icono' => 'fa fa-home', 'url' => site_url('catalogos')],
	['nombre' => 'Catalogos', 'icono' => 'fa fa-home', 'url' => site_url('catalogos')],*/
];


/* Configuración Hora y Fecha */
date_default_timezone_set ( 'America/Mexico_City' );

/* Configuración de Moneda */
setlocale(LC_MONETARY, 'es_MX');
