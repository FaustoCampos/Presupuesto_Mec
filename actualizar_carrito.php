<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_SESSION["Sesion"]) || $_SESSION["Sesion"] !== true || empty($_SESSION["correo"])) {
    http_response_code(401);
    echo json_encode(["ok" => false, "error" => "No hay un usuario iniciado."]);
    exit;
}

$datos = json_decode(file_get_contents("php://input"), true);
$id_material = trim((string)($datos["id_material"] ?? ""));
$cantidad = (int)($datos["cantidad"] ?? 0);
$email = $_SESSION["correo"];

if ($id_material === "" || $cantidad < 1) {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "Datos inválidos."]);
    exit;
}

require_once __DIR__ . "/Funciones/conexion.php";
$conexion->set_charset("utf8mb4");
$stmt = $conexion->prepare("UPDATE U_M SET cantidad = ? WHERE ID_material = ? AND email = ?");
if (!$stmt) { http_response_code(500); echo json_encode(["ok"=>false,"error"=>$conexion->error]); exit; }
$stmt->bind_param("iss", $cantidad, $id_material, $email);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["ok"=>false,"error"=>$stmt->error]);
    $stmt->close(); $conexion->close(); exit;
}
$actualizadas = $stmt->affected_rows;
$stmt->close(); $conexion->close();
echo json_encode(["ok"=>true,"actualizadas"=>$actualizadas]);
?>