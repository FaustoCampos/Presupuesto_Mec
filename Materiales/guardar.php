<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");


// ======================================================
// COMPROBAR SESIÓN
// ======================================================

if (!isset($_SESSION["correo"]) || empty($_SESSION["correo"])) {

    http_response_code(401);

    echo json_encode([
        "ok" => false,
        "error" => "No hay una sesión iniciada."
    ]);

    exit;
}

$email = $_SESSION["correo"];


// ======================================================
// RECIBIR DATOS
// ======================================================

$contenido = file_get_contents("php://input");

$datos = json_decode($contenido, true);

if (!is_array($datos)) {

    http_response_code(400);

    echo json_encode([
        "ok" => false,
        "error" => "Los datos recibidos no son válidos."
    ]);

    exit;
}


// ======================================================
// DATOS DEL PRODUCTO
// ======================================================

$id_material = $datos["id_material"] ?? null;
$cantidad = isset($datos["cantidad"])
    ? intval($datos["cantidad"])
    : 0;


// ======================================================
// VALIDAR
// ======================================================

if ($id_material === null || $id_material === "") {

    http_response_code(400);

    echo json_encode([
        "ok" => false,
        "error" => "No se recibió el ID del material."
    ]);

    exit;
}

if ($cantidad <= 0) {

    http_response_code(400);

    echo json_encode([
        "ok" => false,
        "error" => "La cantidad debe ser mayor que 0."
    ]);

    exit;
}


// ======================================================
// CONEXIÓN
// ======================================================

require_once __DIR__ . "/../Funciones/conexion.php";


if ($conexion->connect_error) {

    http_response_code(500);

    echo json_encode([
        "ok" => false,
        "error" => "Error de conexión: " . $conexion->connect_error
    ]);

    exit;
}

$conexion->set_charset("utf8mb4");


// ======================================================
// COMPROBAR QUE EXISTE EL MATERIAL
// ======================================================
//
// IMPORTANTE:
// Usamos directamente el ID recibido.
// No hacemos una conversión con intval() porque
// queremos conservar exactamente el valor de Materiales.ID.
// ======================================================

$sql = "
    SELECT ID
    FROM Materiales
    WHERE ID = ?
    LIMIT 1
";

$stmt = $conexion->prepare($sql);

if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "ok" => false,
        "error" => "Error preparando Materiales: " . $conexion->error
    ]);

    $conexion->close();

    exit;
}


// Si ID es numérico, MySQL puede comparar correctamente
// aunque lo enviemos como string.

$id_material_string = (string)$id_material;

$stmt->bind_param(
    "s",
    $id_material_string
);

if (!$stmt->execute()) {

    http_response_code(500);

    echo json_encode([
        "ok" => false,
        "error" => "Error consultando Materiales: " . $stmt->error
    ]);

    $stmt->close();
    $conexion->close();

    exit;
}

$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {

    http_response_code(404);

    echo json_encode([
        "ok" => false,
        "error" => "El material con ID " . $id_material . " no existe."
    ]);

    $stmt->close();
    $conexion->close();

    exit;
}

$stmt->close();


// ======================================================
// COMPROBAR SI EL PRODUCTO YA ESTÁ EN EL CARRITO
// ======================================================

$sql = "
    SELECT ID
    FROM U_M
    WHERE ID_material = ?
    AND email = ?
    LIMIT 1
";

$stmt = $conexion->prepare($sql);

if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "ok" => false,
        "error" => "Error preparando U_M: " . $conexion->error
    ]);

    $conexion->close();

    exit;
}

$stmt->bind_param(
    "ss",
    $id_material_string,
    $email
);


if (!$stmt->execute()) {

    http_response_code(500);

    echo json_encode([
        "ok" => false,
        "error" => "Error consultando el carrito: " . $stmt->error
    ]);

    $stmt->close();
    $conexion->close();

    exit;
}

$resultado = $stmt->get_result();

$fila = $resultado->fetch_assoc();

$stmt->close();


// ======================================================
// SI YA EXISTE → ACTUALIZAR CANTIDAD
// ======================================================

if ($fila) {

    $id_carrito = intval($fila["ID"]);

    $sql = "
        UPDATE U_M
        SET cantidad = ?
        WHERE ID = ?
        AND email = ?
    ";

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {

        http_response_code(500);

        echo json_encode([
            "ok" => false,
            "error" => "Error preparando UPDATE: " . $conexion->error
        ]);

        $conexion->close();

        exit;
    }

    $stmt->bind_param(
        "iis",
        $cantidad,
        $id_carrito,
        $email
    );


// ======================================================
// SI NO EXISTE → INSERTAR
// ======================================================

} else {

    $sql = "
        INSERT INTO U_M
        (
            ID_material,
            email,
            cantidad
        )
        VALUES
        (
            ?,
            ?,
            ?
        )
    ";

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {

        http_response_code(500);

        echo json_encode([
            "ok" => false,
            "error" => "Error preparando INSERT: " . $conexion->error
        ]);

        $conexion->close();

        exit;
    }

    $stmt->bind_param(
        "ssi",
        $id_material_string,
        $email,
        $cantidad
    );
}


// ======================================================
// EJECUTAR
// ======================================================

if (!$stmt->execute()) {

    http_response_code(500);

    echo json_encode([
        "ok" => false,
        "error" => "Error de MySQL al guardar: " . $stmt->error
    ]);

    $stmt->close();
    $conexion->close();

    exit;
}


// ======================================================
// TODO CORRECTO
// ======================================================

echo json_encode([
    "ok" => true,
    "mensaje" => "Producto guardado correctamente."
]);


$stmt->close();
$conexion->close();

?>
