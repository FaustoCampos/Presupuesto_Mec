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
$email = $_SESSION["correo"];

if ($id_material === "") {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "ID_material inválido."]);
    exit;
}

require_once __DIR__ . "/Funciones/conexion.php";
$conexion->set_charset("utf8mb4");
$stmt = $conexion->prepare("DELETE FROM U_M WHERE ID_material = ? AND email = ?");
if (!$stmt) { http_response_code(500); echo json_encode(["ok"=>false,"error"=>$conexion->error]); exit; }
$stmt->bind_param("ss", $id_material, $email);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["ok"=>false,"error"=>$stmt->error]);
    $stmt->close(); $conexion->close(); exit;
}
$eliminados = $stmt->affected_rows;
$stmt->close(); $conexion->close();
echo json_encode(["ok"=>true,"eliminados"=>$eliminados]);
?>