<?php

// Execute query and format result as associative array with column names as keys
function db_get_array($query){
    $query = sanitize_db_query($query);
    $result = Request::$app['db']->query($query);
    $data = [];
    if ($result) {
        while ($arr = $result->fetch_assoc()) {
            $data[] = $arr;
        }
        $result->free_result();
    }
    return !empty($data) ? $data : array();
}

/**
* Execute query and format result as associative array with column names as keys and then return first element of this array
 * 
 * @param string $query
 * @return array $data
 */
function db_get_row($query){
    $query = sanitize_db_query($query);
    $result = Request::$app['db']->query($query);
    $data = [];
    if ($result) {
        $data = $result->fetch_assoc();
        $result->free_result();
    }
    return !empty($data) ? $data : array();
}

// Execute query and returns first field from the result
function db_get_field($query)
{
    $query = sanitize_db_query($query);
    $result = Request::$app['db']->query($query);
    $data = [];
    if ($result) {
        $data = $result->fetch_row();
        $result->free_result();
    }
    return !empty($data) ? $data[0] : '';
}

// Execute query and format result as set of first column from all rows
function db_get_column($query)
{
    $query = sanitize_db_query($query);
    $result = Request::$app['db']->query($query);
    $data = [];
    if ($result) {
        while ($arr = $result->fetch_row()) {
            $data[] = $arr[0];
        }
    }
    $result->free_result();
    return !empty($data) ? $data : array();
}

// Execute query
// bool|int|\mysqli_result|\PDOStatement mixed result set for "SELECT" statement / generated ID for an AUTO_INCREMENT field for insert statement / Affected rows count for DELETE/UPDATE statements

function db_query($query)
{
    $query = sanitize_db_query($query);
    $cmd = strtoupper(substr($query, 0, 6));
    $result = Request::$app['db']->query($query);
    $data = [];
    $affected_rows = 0;
    if ($result === true) {

        if($cmd == 'INSERT') {
            $affected_rows = Request::$app['db']->insert_id;
        }
        if($cmd == 'UPDATE' || $cmd == 'DELETE') {
            $affected_rows = Request::$app['db']->affected_rows;
        }

    }
    return $affected_rows;
}

function sanitize_db_query($query){
    $query = trim($query);
    $prefix = Request::$app['table_prefix'];
    if (preg_match("/\?:/", $query)) {
        $query = str_replace('?:', $prefix, $query);
    }
    return $query;
}

/**
 * Paginate query results
 *
 * @param int $page page number
 * @param int $items_per_page items per page
 * @return string SQL substring
 */
function db_paginate(&$page, &$items_per_page, $total_items = 0)
{
    $page = (int) $page;
    $items_per_page = (int) $items_per_page;

    if ($page <= 0) {
        $page = 1;
    }

    if ($items_per_page <= 0) {
        $items_per_page = (int) (!empty(Request::$app['item_per_page'])) ? Request::$app['item_per_page'] : '10';
    }

    // Check if page in valid limits
    if ($total_items > 0) {
        $page = db_get_valid_page($page, $items_per_page, $total_items);
    }
    if ($page == 0){
        return 'LIMT';
    } else {
        return ' LIMIT ' . (($page - 1) * $items_per_page) . ', ' . $items_per_page;
    }
}

function db_get_valid_page($page, $items_per_page, $total_items)
{
    if (($page - 1) * $items_per_page >= $total_items) {
        // $page = ceil($total_items / $items_per_page); //
        return 0;
    }

    return empty($page) ? 1 : $page;
}