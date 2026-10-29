<?php
session_start(); //Inicio la Sesion

if(!isset($_SESSION["Sesion"])) //En el caso que no exista la variable
{
    $_SESSION["Sesion"] = false; //Creo la variable
}

if($_SESSION["Sesion"]==true) //En el caso que quiera entrar sin estar logueado
{
    echo "<script type='text/javascript'>
    // Si el usuario ya está logueado y vuelve a esta página desde el historial,
    // reemplazamos esta entrada para que no quede atrapado en la página de login.
    history.replaceState(null, '', '/index.php');
    window.location.replace('/index.php');
    </script>";
    exit(); //Muestro mensaje de broma y lo mando a otra broma
}  
?>

<!DOCTYPE html>
<html lang="es-Ar">
    <head>
        <meta charset="UTF-8"> <!-- Indica los caracteres especiales -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Indica la escala inicial -->
        <title>Presupuesto Mecánica | Inicio de Sesión</title> <!-- Titulo de la pagina -->
        
        <meta name="description" content="Inicia Sesión en la página de Presupuestos de Mecánica de forma rapida y sencilla"> 
        <meta name="author" content="Equipo JFTSN"> <!-- Nombre de los autores, J(Jazmin), F(Fausto), T(Tomy), S(Santos), N(Nico) -->
        
        <link rel="canonical" href="https://mecanicapp.xo.je/Login/InicioSesion.html"> <!-- Indica que la pagina es la original para evitar duplicaciones -->
        
        <link rel="shortcut icon" href="/Imagenes/logo.png" type="image/x-icon"> <!-- Indica el icono que va a estar en la barra de navegacion -->
        
        <link rel="stylesheet" href="/style.css"> <!-- Enlazo con el css -->
        
        <meta property="og:title" content="Presupuesto Mecánica Online">
        <meta property="og:description" content="Realiza su presupuesta mecanico de forma rápida y sencilla.">
        <meta property="og:image" content="https://mecanicapp.xo.je/Imagenes/logo.png">
        <meta property="og:url" content="https://mecanicapp.xo.je">
        <meta property="og:type" content="website"> <!-- Indica el contenido de la pagina cuando la compartan -->
        
        <meta name="robots" content="index, follow"> <!-- Indica cual es el index -->
        
        <meta name="theme-color" content="#000000"> <!-- hace que el color del navegador sea negro -->
        
        <link rel="preload" href="/style.css" as="style"> <!-- carga por defecto los styles -->
    </head>
    
	<body>
    	<header> <!-- Contenedor del Nav -->
    
            <nav>

                <button type="button" class="nav-back-button" onclick="history.back()" aria-label="Volver a la página anterior" title="Volver a la página anterior">←</button> <!-- Contenido del Navegador -->
    
                <a href="/index.php"> <img src="/Imagenes/logo.png" loading="lazy" alt="Logo de Presupuesto Mec" id="logo"> </a> <!-- Logo de Mecanica -->
                    
                
<a href="/carrito.php" class="carrito-nav" aria-label="Abrir carrito" title="Carrito">
    <span class="carrito-icon" aria-hidden="true">🛒</span>
    <span>Carrito</span>
</a>
<a class="jaz" id="perfil-btn"><img src="/Imagenes/Invitado.png" loading="lazy" alt="Imagen de la cuenta"> </a> <!-- Menu Desplegable -->

                <div id="perfil-popup" class="perfil-popup oculto"> <!-- Contenido del menu desplegable -->

                    <a href="/Login/Registrate.php"><img loading="lazy" src="/Imagenes/Registrarse.png" alt="Imagen de Registrarse"> Registrarse</a> <!-- Enlace a Registro -->

                    <a class="especial" href="/Login/InicioSesion.php"><img loading="lazy" src="/Imagenes/Login.png" alt="Imagen de Iniciar Sesión"> Iniciar Sesión</a> <!-- Enlace a Login -->

                </div>
    
            </nav>
    
        </header>
        <br>
		<div class="contenedorRegis"> <!-- Contenedor de Inicio de Sesión -->
            
            <h1>Iniciar Sesión</h1> <!-- Titulo de la página -->
            
            <form action="back.php" method="post"> <!-- Formulario que te manda al back, con un valor para identificar que esta Iniciando Sesión -->

                <label for="correo">Correo Electronico:</label>
                <input type="email" name="correo" id="correo" required minlength="24"> <!-- Input donde deberas poner tu email -->
                <br>
                <br>
                <label for="contra">Contraseña:</label>
                <input class="jazTQM" type="password" name="contra" id="contra" required minlength="8" maxlength="12"> <!-- Input donde deberas poner tu contraseña --> 
				
                <a class="inicio" href="/Login/Recuperar.php"> Olvidaste tu Contraseña? </a> <!-- Link por si quiere recuperar su contraseña -->
                <br>
                <br>
                <button value="Iniciar Sesión" name="subir" type="submit">Iniciar Sesión</button> <!-- Boton que te envia al back con un valor especifico -->
            
            </form>
            
        </div>
                    
        <footer> <!-- Footer de la página web -->
                    
            <p>&copy; 2026 Presupuesto Mecánica ITLF y <a href="/nosotros.php">Equipo FNSJT</a></p> <!-- Texto de Copyright -->
                
        </footer>
                
		<script src="/Funciones/codigo.js"></script> <!-- Script con las funciones basicas de la pagina con js -->
        
	</body>
</html>