<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$correo = trim($_POST["correo"] ?? '');
$contra = trim($_POST["contra"] ?? '');

$contrahash = password_hash($contra, PASSWORD_DEFAULT);

$conexion = new mysqli("sql301.infinityfree.com","if0_42168178","promo27lamejor","if0_42168178_base_mecanica");

$sql = "SELECT email FROM Usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
    header("Location: https://mecanicapp.xo.je/Login/InicioSesion.html");
    exit();
}

$sql = "INSERT INTO Usuarios (email, contrasena) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);

$stmt->bind_param("ss", $correo, $contrahash);
   
$stmt->execute();

header("Location: https://mecanicapp.xo.je");
exit();
    

?>