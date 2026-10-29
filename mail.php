<?php   
session_start(); // Inicio Sesion

if($_SESSION["reenvio"]==1) // En el caso que el usuario pida reenvio del código
{
    $_SESSION["reenvio"]=0; // Cambio la variable de control para evitar que el código sea siempre el mismo
    
    goto menu1; //Lo mando a la funcion de enviar el mail
}

$abecedario1 = range('A', 'Z'); // Creo el abecedario con mayusculas
$abecedario2 = range('a', 'z'); // Creo el abecedario con minusculas

$_SESSION["codigo"]=""; // Creo variable de sesion con el código

for($a=0;$a<=5;$a++) // For que crea el código de 6 digitos
{
    $numeroAleatorio = rand(1, 3); // Crea un random del 1 al 3

    if($numeroAleatorio==1) // En el caso que sea 1, usa el abecedario de mayusculas
    {
        $Codigo_def = $abecedario1[rand(0, 25)]; // Elije una letra random
    }
    else if($numeroAleatorio==2 ) // En el caso que sea 2, usa el abecedario de minusculas
    {
        $Codigo_def = $abecedario2[rand(0, 25)]; // Elije una letra random
    }
    else // En el caso que sea 3, usa numeros
    {
        $Codigo_def = rand(0, 9); // Elije un numero random
    }
    
    $_SESSION["codigo"] .= $Codigo_def; //Creo poco a poco el código
}

menu1: //Llega aqui desde el if, en el caso que el usuario pida que le reenvien el código

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; //Espesifico el uso de gmail
    $mail->SMTPAuth = true;
    $mail->Username = 'equipo.f.i.n15@gmail.com'; //De la cuenta a la que se envia
    $mail->Password = 'kpqyboywqgejeudt'; // Contraseña de Aplicación 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('equipo.f.i.n15@gmail.com', 'Presupuesto Mecanica'); //De la cuenta a la que se envia, titulo de la cuenta
    $mail->addReplyTo('equipo.f.i.n15@gmail.com', 'Presupuesto Mecanica'); //De la cuenta a la que se envia de nuevo, titulo de la cuenta
    $mail->addAddress($_SESSION["correo"]); // A donde se envia

    $mail->isHTML(true);
    $mail->Subject = 'Verificacion de Correo'; //Titulo del mail
    $mail->Body = 'Hola, le enviamos su codigo de verificacion <b>' . $_SESSION["codigo"] . '</b>'; //Cuerpo del mail

    $mail->send();

} 
catch (Exception $e) //En el caso que halla problemas
{
    echo $mail->ErrorInfo; // Muestra el error
}
if($_SESSION["verificar"]==1)
{
    $_SESSION["verificar"]=2;
    header("Location: https://mecanicapp.xo.je/Login/Recuperar.php"); // Lo mando al html de verificar el mail
    exit();
}
header("Location: https://mecanicapp.xo.je/Login/Verificar.php"); // Lo mando al html de verificar el mail
exit();
?>