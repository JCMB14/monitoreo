<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header("Content-Type: application/json; charset=UTF-8");
header('Content-Type: application/json');
header("Access-Control-Allow-Headers", "Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS");

session_start(); 
require '../config/database.php'; 

$registros = ['codigo' => -1, 'mensaje' => 'No se pudo iniciar sesión'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method != 'POST') {
    $registros['mensaje'] = 'Error - No se permite el acceso por este método...';
    echo json_encode($registros);
    exit(1);
}

$email = isset($_POST['email']) ? $_POST['email'] : '';
$contraseña = isset($_POST['password']) ? $_POST['password'] : '';

if ($email && $contraseña) {
    $stmt = $mysqli->prepare("SELECT userID, email, contraseña FROM usuario WHERE email = ? AND contraseña = ?");
    $stmt->bind_param("ss", $email, $contraseña);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['userID']; 

        $registros['codigo'] = 'OK';
        $registros['mensaje'] = 'Inicio de sesión exitoso';
        header("Location: ../../client/welcome.html");
        exit();
    }
}

echo json_encode($registros);
?>
