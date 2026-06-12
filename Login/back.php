<?php

$correo = trim($_POST["correo"]);
$contra = trim($_POST["contra"]);

$contrahash = password_hash($contra, PASSWORD_DEFAULT);

$conexion = new mysqli("sql301.infinityfree.com", "if0_42168178", "promo27lamejor", "if0_42168178_base_mecanica");

$stmt = $conexion->prepare(
    "INSERT INTO Usuarios (email, contraseña) VALUES (?, ?)"
);

$stmt->bind_param("ss", $correo, $contrahash);
$stmt->execute();

?>