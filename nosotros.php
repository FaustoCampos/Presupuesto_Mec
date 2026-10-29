<?php
  session_start();
  if(!isset($_SESSION["Sesion"]))
  {
      $_SESSION["Sesion"] = false;
  }
  if(!isset($_SESSION["verificar"]))
  {
       $_SESSION["verificar"] = 0;
  }
?>

<!DOCTYPE html>
<html lang="es-AR">

    <head>
        <meta charset="UTF-8"> <!-- Indica los caracteres especiales -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Indica la escala inicial -->
        <title>Presupuesto Mecánica | Inicio</title> <!-- Titulo de la pagina -->
        
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

                 <!-- Contenido del Navegador -->
                
                <a href="index.php"> <img src="/Imagenes/logo.png" loading="lazy" alt="Logo de Presupuesto Mec" id="logo"> </a> <!-- Logo de Mecanica -->
                    
                <a href="/carrito.php" class="carrito-nav" aria-label="Abrir carrito" title="Carrito">
                    <span class="carrito-icon" aria-hidden="true">🛒</span>
                    <span>Carrito</span>
                </a>
                
				<a class="jaz" id="perfil-btn"><img src="/Imagenes/Invitado.png" loading="lazy" alt="Imagen de la cuenta"> </a> <!-- Menu Desplegable -->

                <div id="perfil-popup" class="perfil-popup oculto"> <!-- Contenido del menu desplegable -->
    
    <?php

		if($_SESSION["Sesion"])
        {
            ?>
            <a href="/Login/Cuenta.php"><img loading="lazy" src="/Imagenes/Persona.png" alt="Imagen de Registrarse"> Mi Cuenta</a> <!-- Enlace a Registro -->
<a href="/Login/Cerrar.php"><img loading="lazy" src="/Imagenes/CerrarSesion.png" alt="Imagen de Registrarse"> Cerrar Sesión</a> <!-- Enlace a Registro -->
			<?php
        }
        else
        {
			?>
         <a href="/Login/Registrate.php"><img loading="lazy" src="/Imagenes/Registrarse.png" alt="Imagen de Registrarse"> Registrarse</a> <!-- Enlace a Registro -->

        <a href="/Login/InicioSesion.php"><img loading="lazy" src="/Imagenes/Login.png" alt="Imagen de Iniciar Sesión"> Iniciar Sesión</a> <!-- Enlace a Login -->
                    
                    <?php
        }
    ?>
                </div>
            </nav>
        </header>
        
        <p class="nosotros"> Nuestro equipo de programadores

Somos un equipo de programadores formado por Jazmín Sánchez, Campos Fausto, Santos Cepeda, Tomás Vázquez y Nicolás Moreno. Cada integrante aporta sus conocimientos, ideas y habilidades para trabajar de manera colaborativa y lograr los objetivos propuestos.
<br>
<br>
Nos caracterizamos por la responsabilidad, la creatividad y el trabajo en equipo. A través de la programación buscamos desarrollar soluciones útiles, aprender nuevas herramientas y mejorar constantemente nuestros proyectos.
<br>
<br>
Cada miembro cumple un papel importante dentro del equipo, y la colaboración entre todos nos permite resolver problemas, compartir conocimientos y avanzar juntos. Nuestro objetivo es seguir aprendiendo y creciendo como programadores, enfrentando nuevos desafíos y creando proyectos de calidad. </p>
        
        <footer>
            <p>&copy; 2026 Presupuesto Mecánica ITLF y <a href="/nosotros.php">Equipo FNSJT</a></p>
        </footer>
        
    	<script src="/Funciones/codigo.js"></script> <!-- Script con las funciones basicas de la pagina con js -->
	</body>


</html>
