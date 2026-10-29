<?php
session_start(); // Inicio la Sesion

error_reporting(E_ALL); //Lo uso para ver los errores
ini_set('display_errors', 1); // Hago que muestre todos los errores

include $_SERVER['DOCUMENT_ROOT'] . '/Funciones/conexion.php'; //Incluyo la conexion con la base de datos con include para mas seguridad

$correo = trim($_POST["correo"] ?? ''); //Obtengo el correo enviado por el usuario
$contra = trim($_POST["contra"] ?? ''); // Obtengo la contraseña enviada por el usuario
$codigo= trim($_POST["codigo"]?? ''); //Obtengo el codigo enviado por el usuario

$contrahash = password_hash($contra, PASSWORD_DEFAULT); // Hasheo la contra

if($_POST["subir"] == "Registrarse") //En el caso que venga de Resgistrarse.
{
    $contra_rep = trim($_POST["contra_rep"] ?? ''); // Obtengo la contraseña de confirmacion enviada por el usuario
    $curso = trim($_POST["curso"] ?? ''); // Obtengo el curso del usuario
    $nombre = trim($_POST["nombre"] ?? ''); // Obtengo el nombre del usuario
    
    if($contra_rep != $contra) //En el caso que las contras no sean iguales
    {
        echo "<script type='text/javascript'>
        alert('Las contraseñas ingresadas no concuerdan');
        window.location.href = '/Login/Registrate.php';
        </script>";
      exit(); //Muestro alerta y redirijo a Registrate
    }
    
    $sql = "SELECT email FROM Usuarios WHERE email = ?"; //Agarra todos los valores en el campo de email y lo guarda en ? para que sea mas seguro
    $stmt = $conexion->prepare($sql); //Prepara la consulta
    $stmt->bind_param("s", $correo); //cambia el "?" por la varibale del correo del usuario
    $stmt->execute(); //ejecuta la consulta
    $stmt->store_result(); //Se fija si hay coincidencias, si es 1, significa que si hay coincidencias
    
    if ($stmt->num_rows == 1) //Si hay coincidencias
    {
        echo "<script type='text/javascript'>
        alert('Email ya registrado');
        window.location.href = '/Login/Registrate.php';
        </script>";
        exit(); //Muestro alerta y redirijo al Registrate
	}
    else //En el caso que no haya coincidencia
    {
        $_SESSION["correo"]=$correo; //Guardo el correo en variable de sesion
        $_SESSION["contra2"]=$contra; // Guardo en sesiones la contra hash
        $_SESSION["contra"]=$contrahash; // Guardo en sesiones la contra hash
       	$_SESSION["nombre"]=$nombre; // Guardo en sesiones la contra hash
        $_SESSION["curso"]=$curso; // Guardo en sesiones la contra hash
        
        header("Location: https://mecanicapp.xo.je/mail.php"); // Lo mando a la funcion de enviar el mail
        
        exit(); //Detengo el programa
    }
}
else if($_POST["subir"] == "Iniciar Sesión") //En el caso que venga del Inicio de Sesión
{
    $sql = "SELECT email,contrasena ,nombre, curso FROM Usuarios WHERE email = ?"; //Agarra todos los valores en el campo de email y lo guarda en ? para que sea mas seguro
    $stmt = $conexion->prepare($sql); //Prepara la consulta
    $stmt->bind_param("s", $correo); //cambia el "?" por la varibale del correo del usuario
    $stmt->execute(); //ejecuta la consulta
    $stmt->store_result(); //Se fija si hay coincidencias, si es 1, significa que si hay coincidencias
    
    if ($stmt->num_rows == 1) //Si hay coincidencias
    {
        $stmt->bind_result($email_base_datos, $password_base_datos, $nombre_base_datos, $curso_base_datos);
        $stmt->fetch();

        if (password_verify($contra, $password_base_datos)) //En el caso que la contraseña ingresada pertenezca al mismo correo
        {
            $_SESSION["correo"]=$correo; //Guardo el correo en variable de sesion        
            $_SESSION["contra2"]=$contra; //Guardo la contraseña en variable de sesion
            $_SESSION["contra"]=$contrahash; //Guardo la contraseña en variable de sesion
            
             $_SESSION["nombre"] = $nombre_base_datos;
            $_SESSION["curso"] = $curso_base_datos;
            
            $_SESSION["Sesion"]=true; //Hago que el usuario se loguee
            
            echo "<script type='text/javascript'>
            alert('Felicidades ha iniciado sesión');
            window.location.href = '/index.php';
            </script>";
            exit(); //Muestro alerta y redirijo al index
        } 
        else //En el caso que el usuario ingreso valores erroneos
        {
            echo "<script type='text/javascript'>
            alert('La contraseña ingresada no es válida');
            window.location.href = '/Login/InicioSesion.php';
            </script>";
            exit(); //Muestro alerta y redirijo al inicio de sesion
        }
	}
    else
    {
        echo "<script type='text/javascript'>
            alert('No existe esta cuenta');
            window.location.href = '/Login/InicioSesion.php';
            </script>";
        exit(); //Muestro alerta y redirijo al inicio de sesion
    }
}
else if($_POST["subir"] == "Verificar") //En el caso que el usuario venga del formulario de verificar
{
    if($_SESSION["codigo"]==$codigo) //En el caso que el codigo enviado por el ususario coincida
    {
        $sql = "INSERT INTO Usuarios (email, contrasena, nombre, curso) VALUES (?, ?, ?, ?)"; //Pongo al usuario en la base de datos
        $stmt = $conexion->prepare($sql);//Preparo la consulta
        $stmt->bind_param("ssss", $_SESSION["correo"], $_SESSION["contra"], $_SESSION["nombre"], $_SESSION["curso"]); //Preparo los valores para ingresar en la base de datos
        $stmt->execute(); //Ejecuto la consulta
        
        $_SESSION["Sesion"]=true; // Variable de sesion que controla si esta logueado el usuario o no
        
        echo "<script type='text/javascript'>
            alert('Registro exitoso!!');
            window.location.href = '/index.php';
            </script>";
        exit(); //Muestro alerta y redirijo al index
    }
    else //En el caso que el usuario ingreso el codigo erroneo
    {
         echo "<script type='text/javascript'>
            alert('El código enviado no es correcto');
            window.location.href = '/Login/Verificar.php';
            </script>";
        exit(); //Muestro alerta y redirijo a la pagina de Verificacion
    }
}
else if($_POST["subir"] == "Reenviar") //En el caso que el usuario quiera reenviar el codigo
{
    $_SESSION["reenvio"]=1; //identificador para que reenvie el mensaje
    
     header("Location: https://mecanicapp.xo.je/mail.php"); // Lo mando a la funcion de enviar el mail
     
    exit(); //Detengo la ejecucion del codigo 
}
else if($_POST["subir"] == "Mail")
{
    $sql = "SELECT email FROM Usuarios WHERE email = ?"; //Agarra todos los valores en el campo de email y lo guarda en ? para que sea mas seguro
    $stmt = $conexion->prepare($sql); //Prepara la consulta
    $stmt->bind_param("s", $correo); //cambia el "?" por la varibale del correo del usuario
    $stmt->execute(); //ejecuta la consulta
    $stmt->store_result(); //Se fija si hay coincidencias, si es 1, significa que si hay coincidencias
    
    if ($stmt->num_rows == 1) //Si hay coincidencias
    {
        $_SESSION["correo"]=$correo;
        $_SESSION["verificar"]=1;
        
        header("Location: https://mecanicapp.xo.je/mail.php"); // Lo mando a la funcion de enviar el mail
     	exit();
	}
    else
    {
        echo "<script type='text/javascript'>
        alert('No existe ese email registrado');
        window.location.href = '/Login/Recuperar.php';
        </script>";
        exit(); //Muestro alerta y redirijo al Registrate
    }
}
else if($_POST["subir"] == "Cambio4")
{
    
    $curs = trim($_POST["curso"] ?? ''); // Obtengo la contraseña enviada por el usuario

    $sql = "UPDATE Usuarios SET curso = ? WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $curs, $_SESSION["correo"]);
    $stmt->execute(); //ejecuta la consulta
    
    $_SESSION["curso"]=$curs;
    
    echo "<script type='text/javascript'>
        alert('Curso actualizada');
        window.location.href = '/index.php';
        </script>";
      exit(); //Muestro alerta y redirijo a Registrate
}
else if($_POST["subir"] == "Cambio2")
{
    
    $contra_rep = trim($_POST["contra_rep"] ?? ''); // Obtengo la contraseña enviada por el usuario

    if($contra_rep != $contra)
    {
        echo "<script type='text/javascript'>
        alert('Las contraseñas ingresadas no concuerdan');
        window.location.href = '/Login/Recuperar.php';
        </script>";
      exit(); //Muestro alerta y redirijo a Registrate
    }
    $sql = "UPDATE Usuarios SET contrasena = ? WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $contrahash, $_SESSION["correo"]);
    $stmt->execute(); //ejecuta la consulta
    
    $_SESSION["contra2"]=$contra_rep;
    
    echo "<script type='text/javascript'>
        alert('Contraseña actualizada');
        window.location.href = '/index.php';
        </script>";
      exit(); //Muestro alerta y redirijo a Registrate
}
else if($_POST["subir"] == "Cambio3")
{
    
    $nombr = trim($_POST["nombre"] ?? ''); // Obtengo la contraseña enviada por el usuario

    $sql = "UPDATE Usuarios SET nombre = ? WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $nombr, $_SESSION["correo"]);
    $stmt->execute(); //ejecuta la consulta
    
    $_SESSION["nombre"]=$nombr;
    
    echo "<script type='text/javascript'>
        alert('Nombre actualizada');
        window.location.href = '/index.php';
        </script>";
      exit(); //Muestro alerta y redirijo a Registrate
}
else if($_POST["subir"] == "Cambio")
{
	$_SESSION["verificar"]=0;
    
    $contra_rep = trim($_POST["contra_rep"] ?? ''); // Obtengo la contraseña enviada por el usuario

    if($contra_rep != $contra)
    {
        echo "<script type='text/javascript'>
        alert('Las contraseñas ingresadas no concuerdan');
        window.location.href = '/Login/Recuperar.php';
        </script>";
      exit(); //Muestro alerta y redirijo a Registrate
    }
    $sql = "UPDATE Usuarios SET contrasena = ? WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $contrahash, $_SESSION["correo"]);
    $stmt->execute(); //ejecuta la consulta
    
    echo "<script type='text/javascript'>
        alert('Contraseña actualizada');
        window.location.href = '/Login/InicioSesion.php';
        </script>";
      exit(); //Muestro alerta y redirijo a Registrate
}
else if($_POST["subir"] == "VerificarRec")
{
    $codigo=$_POST["codigo"];
    
    if($_SESSION["codigo"]==$codigo)
    {
        $_SESSION["verificar"]= 1;
        header("Location: /Login/Recuperar.php");
        exit();
    }
}
else if($_POST["subir"] == "ReenviarRec")
{
    $_SESSION["reenvio"]=1;
    
    $_SESSION["verificar"]=1;
    
    header("Location: https://mecanicapp.xo.je/mail.php"); // Lo mando a la funcion de enviar el mail
    exit();
}
?>