<?php
require 'includes/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_GET['action'] = 'create_order';
    require 'api.php';
} else {
    $_GET['action'] = 'products';
    require 'api.php';
}
?>