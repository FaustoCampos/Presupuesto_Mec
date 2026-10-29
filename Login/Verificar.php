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
        <title>Presupuesto Mecánica | Verificar</title> <!-- Titulo de la pagina -->
        
        <meta name="description" content="Verifica tu email para poder loguearte en nuestra página web de forma facil y rapido"> 
        <meta name="author" content="Equipo JFTSN"> <!-- Nombre de los autores, J(Jazmin), F(Fausto), T(Tomy), S(Santos), N(Nico) -->
        
        <link rel="canonical" href="https://mecanicapp.xo.je/Login/Verificar.html"> <!-- Indica que la pagina es la original para evitar duplicaciones -->
        
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
<body >
   <header> <!-- Contenedor del Nav -->
        
            <nav>

                <button type="button" class="nav-back-button" onclick="history.back()" aria-label="Volver a la página anterior" title="Volver a la página anterior">←</button> <!-- Contenido del Navegador -->
                
                <a href="/index.php"> <img src="/Imagenes/logo.png" loading="lazy" alt="Logo de Presupuesto Mec" id="logo"> </a> <!-- Logo de Mecanica -->
                    
                
<a href="/carrito.html" class="carrito-nav" aria-label="Abrir carrito" title="Carrito">
    <span class="carrito-icon" aria-hidden="true">🛒</span>
    <span>Carrito</span>
</a>
<a class="jaz" id="perfil-btn"><img src="/Imagenes/Invitado.png" loading="lazy" alt="Imagen de la cuenta"> </a> <!-- Menu Desplegable -->

                <div id="perfil-popup" class="perfil-popup oculto"> <!-- Contenido del menu desplegable -->

                    <a class="especial" href="/Login/Registrate.php"><img loading="lazy" src="/Imagenes/Registrarse.png" alt="Imagen de Registrarse"> Registrarse</a> <!-- Enlace a Registro -->

                    <a href="/Login/InicioSesion.php"><img loading="lazy" src="/Imagenes/Login.png" alt="Imagen de Iniciar Sesión"> Iniciar Sesión</a> <!-- Enlace a Login -->

                </div>
                
            </nav>
            
        </header>
        <div class="contenedorRegis">
            <h1>Verificar Código: </h1>
            
            <form action="back.php" method="post" name="registro">
                <label for="codigo">Código</label>
                <br>
                <input type="text" name="codigo" id="codigo" minlength="6" maxlength="6"> <!-- Input en el campo de ingresar el codigo enviado -->
                <br>
                <button value="Verificar" name="subir" type="submit">Verificar Código</button> <!-- Boton para verificar el codigo y crear su cuenta -->
                <br>
                <br>
                <button value="Reenviar" name="subir" type="submit">Reenviar Código</button> <!-- Boton para reenviar el codigo -->
            </form>
        </div>
        
    	<footer> <!-- Footer de la página web -->
                    
            <p>&copy; 2026 Presupuesto Mecánica ITLF y <a href="/nosotros.php">Equipo FNSJT</a></p>
                
        </footer>
    
	<script src="/Funciones/codigo.js"></script> <!-- Script con las funciones basicas de la pagina con js -->
</body>
</html>