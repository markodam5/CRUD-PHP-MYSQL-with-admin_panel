<?php

session_start();
require('../config.php');

if (!function_exists('admin_redirect')) {
    function admin_redirect($location) {
        header("Location: $location");
        exit;
    }
}

// Validate what page to show:
if (isset($_GET['page'])) {
    $p = $_GET['page'];
}else {
    $p = NULL;
}

// Determine what page to display:
switch ($p) {
     case 'login':
        $page = 'login.php';
        $page_title = 'Login Page';
    break;
    case 'loggedin':
        $page = 'loggedin.php';
        $page_title = 'Loggedin Page';
    break;
    case 'view_cars':
        $page = 'view_cars.php';
        $page_title = 'View Cars Page';
    break;
    case 'details':
        $page = 'details.php';
        $page_title = 'Details Cars Page';
    break;
    case 'insert':
        $page = 'insert.php';
        $page_title = 'Insert Cars Page';
    break;
    case 'update':
        $page = 'update.php';
        $page_title = 'Update Cars Page';
    break;
    case 'delete':
        $page = 'delete.php';
        $page_title = 'Delete Cars Page';
    break;
    
    default: // Default is to include the main page.
        $page = 'login.php';
        $page_title = 'Login Page';
    break;
        
} // End of main switch.

if (!file_exists($page)) {
    $page = 'index.php';
    $page_title = 'Admin Home Page';
}

// Handle redirecting actions before any HTML is sent.
if ($p === 'logout') {
    unset($_SESSION['username']);
    session_destroy();
    admin_redirect('../');
}

if ($p === 'delete' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];
    mysqli_query($conn, "delete from products where id = $id");
    admin_redirect('index.php');
}

if ($p === 'insert' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $manu = mysqli_real_escape_string($conn, $_POST['manu']);

    mysqli_query($conn, "insert into products values (null,'$model','$manu')");
    admin_redirect('index.php');
}

if ($p === 'update' && isset($_POST['btn_submit'])) {
    $search_id = isset($_POST['hf_id']) ? (int) $_POST['hf_id'] : 0;
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $manu = mysqli_real_escape_string($conn, $_POST['manufacturer']);

    mysqli_query($conn, "update products set model='{$model}', manu='{$manu}' where id = $search_id");
    admin_redirect('index.php');
}

include('./includes/header.php');
include($page);
include('./includes/footer.php');

