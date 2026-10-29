<?php
// 1. Leer el archivo JSON actual
$jsonActual = file_get_contents('datos.json');
$arrayDatos = json_decode($jsonActual, true); // Lo convierte a un array de PHP

// 2. Recibir los datos nuevos que envió JavaScript
$datosNuevosJson = file_get_contents('php://input');
$datosNuevosArray = json_decode($datosNuevosJson, true);

// 3. Añadir los datos nuevos al array existente
$arrayDatos[] = $datosNuevosArray;

// 4. Guardar todo de vuelta en el archivo
$jsonFinal = json_encode($arrayDatos, JSON_PRETTY_PRINT);
file_put_contents('../datos.json', $jsonFinal);
print("aaaaaa");

echo "Datos guardados con éxito";
?>