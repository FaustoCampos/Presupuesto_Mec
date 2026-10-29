<!--                                                        Codigo Terminado                                       -->
<?php
session_start(); //Inicio la Sesion

if(!isset($_SESSION["Sesion"])) //En el caso que no exista la variable
{
    $_SESSION["Sesion"] = false; //Creo la variable
}

if($_SESSION["Sesion"]==false) //En el caso que quiera entrar sin estar logueado
{
    echo "<script type='text/javascript'>
    alert('Qué Intentas? 🤨🤨');
    window.location.href = 'https://tenor.com/es/search/gorilla-giving-finger-gifs';
    </script>";
    exit(); //Muestro mensaje de broma y lo mando a otra broma
}
else // En el caso que sea legal el usuario
{
	session_unset(); //Borro el contenido de las Sesiones
	session_destroy(); //Destruyo las Sesiones

	$_SESSION["Sesion"]=false; //Pongo la sesion como falsa

	echo "<script type='text/javascript'>
    alert('Cerraste la Sesion Exitosamente');
    window.location.href = '/index.php';
    </script>";
    exit(); //Muestro mensaje de Confirmacion y lo manod al index
}    
?>