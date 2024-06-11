<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=UTF-8');

session_start();
require '../config/database.php';

$registros = ['status' => 'error', 'message' => 'Error adding appliance'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method != 'POST') {
    $registros['message'] = 'Error - Method not allowed';
    echo json_encode($registros);
    exit(1);
}

if (!isset($_SESSION['user_id'])) {
    $registros['message'] = 'Error - User not authenticated';
    echo json_encode($registros);
    exit(1);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_userID = $_SESSION['user_id'];
    $tipo = $_POST['applianceType'];
    $marca = $_POST['brand'];
    $modelo = $_POST['model'];
    $consumoKWh = $_POST['kwh'];
    $precioKWh = $_POST['pricePerKwh'];
    $tiempoUso = $_POST['usageTime'];
    $created = date("Y-m-d H:i:s");

    $user_check_stmt = $mysqli->prepare("SELECT userID FROM usuario WHERE userID = ?");
    $user_check_stmt->bind_param("i", $usuario_userID);
    $user_check_stmt->execute();
    $user_check_stmt->store_result();

    if ($user_check_stmt->num_rows == 0) {
        $registros['message'] = 'Error - User not found';
        echo json_encode($registros);
        exit(1);
    }

    $stmt = $mysqli->prepare("INSERT INTO electrodomestico (usuario_userID, tipo, marca, modelo, consumoKWh, precioKWh, tiempoUso, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $usuario_userID, $tipo, $marca, $modelo, $consumoKWh, $precioKWh, $tiempoUso, $created);
    $stmt->execute();

    if ($mysqli->affected_rows > 0) {
        $registros['status'] = 'success';
        $registros['message'] = 'Appliance added successfully';
    } else {
        $registros['message'] = 'Error - Could not add appliance';
    }
    echo json_encode($registros);
}
?>
