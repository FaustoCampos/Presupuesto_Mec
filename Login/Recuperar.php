<?php
session_start(); //Inicio la Sesion

if(!isset($_SESSION["Sesion"])) //En el caso que no exista la variable
{
    $_SESSION["Sesion"] = false; //Creo la variable
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
<body >
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

                    <a href="/Login/InicioSesion.php"><img loading="lazy" src="/Imagenes/Login.png" alt="Imagen de Iniciar Sesión"> Iniciar Sesión</a> <!-- Enlace a Login -->

                </div>
                
            </nav>
            
        </header>
    <br>
    <br>
    <br>
        <div class="contenedorRegis"> <!-- Contenedor del formulario -->
             
            <h1>Recuperar Cuenta: </h1> <!-- Titulo de la seccion -->
            
            <form action="back.php" method="post" name="registro"> <!-- Te manda a back.php con un value diferente para identificar c/ imput -->
                <?php
                session_start();
				if($_SESSION["verificar"]==1) // En el caso de que quiera cambiar la contraseña, ultimo paso
                {
                    ?>
						<label for="contra">Contraseña Nueva</label> 
                        <br>
                        <input type="password" name="contra" id="contra" minlength="8" maxlength="12"> <!-- Input con el campo de la contraseña nueva -->
                        <br>
                        <label for="contra_rep">Repita la Contraseña</label>
                        <br>
                        <input type="password" name="contra_rep" id="contra_rep" minlength="8" maxlength="12"> <!-- Input con el campo de la contraseña nueva de nuevo -->
						<br>
						<br>
                        <button value="Cambio" name="subir" type="submit">Cambiar Contraseña</button> <!-- Boton con el value "Cambio", el cual es el id -->
					<?php
                }
				else if($_SESSION["verificar"]==2) // En el caso que venga de aqui luego de enviar el codigo de verificacion
                {
                    ?>
						<label for="codigo">Código</label>
                        <br>
                        <input type="text" name="codigo" id="codigo" minlength="6" maxlength="6"> <!-- Input con el codigo de recuperacion -->
                        <br>
						<br>
                        <button value="VerificarRec" name="subir" type="submit">Verificar</button> <!-- Boton con el value "VerificarRec", el cual es el id -->
						<br>
						<br>
						<button value="ReenviarRec" name="subir" type="submit">Reenviar Código</button> <!-- Boton con el value "ReenviarRec", el cual es el id -->
					<?php
                }
                else // En el caso que venga de olvide mi contraseña
                {
					?>
						<label for="correo">Ingrese el Mail</label>
                        <br>
                        <input type="email" name="correo" id="correo" minlength="24">
                        <br>
                        <br>
                        <button value="Mail" name="subir" type="submit">Siguiente</button> <!-- Boton con el value "Mail", el cual es el id -->
					<?php
                }
                ?>
            </form>
        </div>
    
    	<footer> <!-- Footer de la página web -->
                    
            <p>&copy; 2026 Presupuesto Mecánica ITLF y <a href="/nosotros.php">Equipo FNSJT</a></p> <!-- Texto de Copyright -->
                
        </footer>
    
		<script src="/Funciones/codigo.js"></script> <!-- Script con las funciones basicas de la pagina con js -->
    
</body>
</html>