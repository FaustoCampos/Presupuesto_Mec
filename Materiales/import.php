<?php

header("Content-Type: text/plain; charset=utf-8");

echo "Iniciando importación...\n";


// ==========================================
// CONEXIÓN
// ==========================================

$host = "sql301.infinityfree.com";
$user = "if0_42168178";
$password = "promo27lamejor";
$database = "if0_42168178_base_mecanica";

$conexion = new mysqli(
    $host,
    $user,
    $password,
    $database
);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

echo "Conexión a MySQL OK\n";


// ==========================================
// LEER JSON
// ==========================================
 
$json = file_get_contents("productos.json");
 
$productos = json_decode($json, true);
 
if ($productos === null) {
    die("Error al leer productos.json\n");
}
 
echo "Productos recibidos: " . count($productos) . "\n\n";
 
 
// ==========================================
// VACIAR TABLA
// ==========================================
 
echo "Vaciando tabla Materiales...\n";
 
$sql_vaciar = "DELETE FROM Materiales";
 
if (!$conexion->query($sql_vaciar)) {
    die("Error vaciando Materiales: " . $conexion->error . "\n");
}
 
echo "Tabla Materiales vaciada correctamente.\n\n";
 
 
// ==========================================
// PREPARAR INSERT
// ==========================================
 
$sql = "
    INSERT INTO Materiales
    (ID, Nombre, Descripcion, Precio, Tipo, Imagen)
 
    VALUES (?, ?, ?, ?, ?, ?)
";
 
$stmt = $conexion->prepare($sql);
 
if (!$stmt) {
    die("Error preparando SQL: " . $conexion->error);
}
 
 
// ==========================================
// INSERTAR PRODUCTOS
// ==========================================
 
$insertados = 0;
$errores = 0;
$contador = 1; // ID autoincremental generado por este script
 
foreach ($productos as $producto) {
 
    // ID final = contador autoincremental (propio de este script) + id/sku del producto
    $id_final = $contador . "-" . $producto["sku"];
 
    $stmt->bind_param(
        "sssdss",
        $id_final,
        $producto["nombre"],
        $producto["descripcion"],
        $producto["precio"],
        $producto["tipo"],
        $producto["imagen"]
    );
 
    if ($stmt->execute()) {
 
        echo "OK: " . $id_final . "\n";
        $insertados++;
 
    } else {
 
        echo "ERROR: " . $id_final;
        echo " -> " . $stmt->error . "\n";
 
        $errores++;
    }
 
    $contador++;
}
 
 
// ==========================================
// CERRAR
// ==========================================
 
$stmt->close();
$conexion->close();
 
echo "\n================================\n";
echo "Importación terminada.\n";
echo "Insertados: " . $insertados . "\n";
echo "Errores: " . $errores . "\n";
echo "================================\n";
 
?>