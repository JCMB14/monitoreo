<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header("Content-Type: application/json; charset=UTF-8");
header('Content-Type: application/json');
header("Access-Control-Allow-Headers", "Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS");

require '../config/database.php';

$registros = ['codigo' => -1, 'mensaje' => 'No se pudo guardar el registro'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method != 'POST') {
    $registros['mensaje'] = 'Error - No se permite el acceso por este método...';
    echo json_encode($registros);
    exit(1);
}

$nombre = isset($_POST['fullname']) ? $_POST['fullname'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$contraseña = isset($_POST['password']) ? $_POST['password'] : '';
$fechaRegistro = date("Y-m-d H:i:s");

if ($nombre && $email && $contraseña) {
    $stmt = $mysqli->prepare("INSERT INTO usuario (nombre, email, contraseña, fechaRegistro) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $contraseña, $fechaRegistro);
    $stmt->execute();

    if ($mysqli->affected_rows > 0) {
        $registros['codigo'] = 'OK';
        $registros['mensaje'] = 'Registro guardado correctamente';
        header("Location: ../../client/welcome.html");
        exit();
    }
}

echo json_encode($registros);
?>
