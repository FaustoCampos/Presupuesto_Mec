<?php
  session_start();
  if(!isset($_SESSION["Sesion"]))
  {
      $_SESSION["Sesion"] = false;
      
      header("Location: https://mecanicapp.xo.je/index.php");
  }
  else if($_SESSION["Sesion"]==false)
  {
      echo "<script type='text/javascript'>
    alert('Qué Intentas? 🤨🤨');
    window.location.href = 'https://tenor.com/es/search/gorilla-giving-finger-gifs';
    </script>";
    exit(); //Muestro mensaje de broma y lo mando a otra broma
  }
?>

<!DOCTYPE html>
<html lang="es-AR">

    <head>
        <meta charset="UTF-8"> <!-- Indica los caracteres especiales -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Indica la escala inicial -->
        <title>Presupuesto Mecánica | Mi Cuenta</title> <!-- Titulo de la pagina -->
        
        <meta name="description" content="Solicitá tu presupuesto mecánico online de forma rápida y sencilla con la cotización actual de los materiales."> 
        <meta name="author" content="Equipo JFTSN"> <!-- Nombre de los autores, J(Jazmin), F(Fausto), T(Tomy), S(Santos), N(Nico) -->
        
        <link rel="canonical" href="https://mecanicapp.xo.je"> <!-- Indica que la pagina es la original para evitar duplicaciones -->
        
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
    
                    <a class="especial" href="/Login/Registrate.php"><img loading="lazy" src="/Imagenes/Persona.png" alt="Imagen de Registrarse"> Mi Cuenta</a> <!-- Enlace a Registro -->
                    <a href="/Login/Cerrar.php"><img loading="lazy" src="/Imagenes/CerrarSesion.png" alt="Imagen de Registrarse"> Cerrar Sesión</a> <!-- Enlace a Registro -->
          
            </nav>
            
        </header>
            
      <div class="contenedorCuenta">
          <h1 class="title">Mi cuenta: </h1> <!-- Titulo de mi seccion -->
          <div align="left">
          
              <p class="nose">Nombre: 
              <?php
              echo $_SESSION["nombre"]; // Muestro el correo del usuario 
              ?>
              </p>
              <p class="nose">Curso: 
              <?php
              echo $_SESSION["curso"]; // Muestro el correo del usuario 
              ?>
              </p>
              <p class="nose">Mail: 
              <?php
              echo $_SESSION["correo"]; // Muestro el correo del usuario 
              ?>
              </p>
              <p class="nose">Contraseña: 
              <?php
              for ($i=0; $i<strlen($_SESSION["contra2"]); $i++)
              {
                  echo "*"; //Muestro la contraseña en caracteres
              }
              ?>
          	</p>
          </div>
          <div align="right">
              
              	<a class="linkCambio" href="/Login/datos.php">Cambiar Datos</a> <!-- Link que te manda a cambiar la contraseña -->
              
          </div>
      </div>
                 
        <footer>
            <p>&copy; 2026 Presupuesto Mecánica ITLF y <a href="/nosotros.php">Equipo FNSJT</a></p>
        </footer>
        
    	<script src="/Funciones/codigo.js"></script> <!-- Script con las funciones basicas de la pagina con js -->
	</body>


</html>