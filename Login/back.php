<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include $_SERVER['DOCUMENT_ROOT'] . '/Funciones/conexion.php'; //Incluyo la conexion con la base de datos con include para mas seguridad

$correo = trim($_POST["correo"] ?? ''); //Obtengo el correo enviado por el usuario
$contra = trim($_POST["contra"] ?? ''); // Obtengo la contraseña enviada por el usuario

$contrahash = password_hash($contra, PASSWORD_DEFAULT);

if($_POST["subir"] == "Registrarse") //En el caso que venga de Resgistrarse.
{
    
    $sql = "SELECT email FROM Usuarios WHERE email = ?"; //Agarra todos los valores en el campo de email y lo guarda en ? para que sea mas seguro
    $stmt = $conexion->prepare($sql); //Prepara la consulta
    $stmt->bind_param("s", $correo); //cambia el "?" por la varibale del correo del usuario
    $stmt->execute(); //ejecuta la consulta
    $stmt->store_result(); //Se fija si hay coincidencias, si es 1, significa que si hay coincidencias
    
    if ($stmt->num_rows == 1) //Si hay coincidencias
    {
        echo "<script type='text/javascript'>
        alert('Email ya Registrado');
        window.location.href = '/Login/Registrate.html';
        </script>";
        exit(); //Muestro alerta y redirijo al Registrate
	}
    else
    {
        $sql = "INSERT INTO Usuarios (email, contrasena) VALUES (?, ?)";
        $stmt = $conexion->prepare($sql);

        $stmt->bind_param("ss", $correo, $contrahash);

        $stmt->execute();

        header("Location: https://mecanicapp.xo.je");
        exit();
    }
}
else if($_POST["subir"] == "Iniciar Sesión")
{
    $sql = "SELECT email,contrasena FROM Usuarios WHERE email = ?"; //Agarra todos los valores en el campo de email y lo guarda en ? para que sea mas seguro
    $stmt = $conexion->prepare($sql); //Prepara la consulta
    $stmt->bind_param("s", $correo); //cambia el "?" por la varibale del correo del usuario
    $stmt->execute(); //ejecuta la consulta
    $stmt->store_result(); //Se fija si hay coincidencias, si es 1, significa que si hay coincidencias
    
    if ($stmt->num_rows == 1) //Si hay coincidencias
    {
        $stmt->bind_result($email_base_datos, $password_base_datos);
        $stmt->fetch(); // Carga los datos reales en las variables

        if (password_verify($contra, $password_base_datos)) 
        {
            echo "¡Inicio de sesión exitoso! Los valores coinciden.";
            exit();
        } 
        else 
        {
            echo "La contraseña es incorrecta.";
            exit();
        }
	}
}

?>