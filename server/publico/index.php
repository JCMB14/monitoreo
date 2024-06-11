<?php
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'register':
        require_once '../api/user.php';
        break;
    case 'login':
        require_once '../api/login.php';
        break;
    case 'electrodomestico':
        require_once '../api/add_appliance.php';
        break;
    // Otros casos
    default:
        echo "Página no encontrada.";
        break;
}
?>
