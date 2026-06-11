<?php

$email=$_POST['email'];
$contraseña=$_POST['contraseña'];

$conexion = new mysqli("localhost", "root", "promo27lamejor","usuarios");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Genera un hash seguro
$password_hash = password_hash($contraseña, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (email, contraseña) VALUES (?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $email, $password_hash);

if ($stmt->execute()) {
    echo "Usuario registrado correctamente";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conexion->close();

?>