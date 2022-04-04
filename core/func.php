<?php

/**
 * @param string var
 * @return string 
 */
function env($var)
{
	if ($_ENV[$var]) {
		return $_ENV[$var];
	}
}

function route_segment($key, $route, $isAPI = false)
{
	$segments = explode('/', $route);
	$controller = $segments[0];

	$method = ($isAPI) ? 'checkApi' : $segments[1];
	$id = ($isAPI && isset($key[2])) ? end($key) : '';

	if (!empty($segments[2]) && !$isAPI) {
		$id = $segments[2] = end($key);
	}
	return array(
		'controller' => $controller,
		'method' => $method,
		'id' => $id,
	);
}

function separate_array_key_values($arr)
{
	$indices = array_keys($arr);
	$values = array_map("myfunction", array_values($arr));
	return array($indices, $values);
}

function myfunction($v)
{
	if (!is_numeric($v) && !preg_match('/ST_GeomFromText/i', $v)) {
		$v = "'" . $v . "'";
	}
	return $v;
}