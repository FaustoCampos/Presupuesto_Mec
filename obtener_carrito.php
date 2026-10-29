<?php

session_start();

header(
    "Content-Type: application/json; charset=UTF-8"
);


/*
=========================================================
COMPROBAR SESIÓN
=========================================================
*/

if (
    !isset($_SESSION["correo"]) ||
    empty($_SESSION["correo"])
) {

    echo json_encode(
        [
            "ok" => false,
            "error" => "No hay un usuario iniciado.",
            "materiales" => []
        ],
        JSON_UNESCAPED_UNICODE
    );

    exit;
}


$email =
    $_SESSION["correo"];


/*
=========================================================
CONEXIÓN
=========================================================
*/

require_once __DIR__ .
    "/Funciones/conexion.php";


if (
    $conexion->connect_error
) {

    echo json_encode(
        [
            "ok" => false,
            "error" =>
                "Error de conexión a la base de datos.",
            "materiales" => []
        ],
        JSON_UNESCAPED_UNICODE
    );

    exit;
}


$conexion->set_charset(
    "latin1"
);


/*
=========================================================
CONSULTAR CARRITO
=========================================================
*/

$sql = "

    SELECT

        U_M.ID,
        U_M.ID_material,
        U_M.cantidad,

        Materiales.Nombre,
        Materiales.Descripcion,
        Materiales.Precio,
        Materiales.Tipo,
        Materiales.Imagen

    FROM U_M

    INNER JOIN Materiales

        ON U_M.ID_material =
           Materiales.ID

    WHERE U_M.email = ?

    ORDER BY U_M.ID DESC

";


$stmt =
    $conexion->prepare(
        $sql
    );


if (!$stmt) {

    echo json_encode(
        [
            "ok" => false,
            "error" =>
                "Error preparando consulta: " .
                $conexion->error,
            "materiales" => []
        ],
        JSON_UNESCAPED_UNICODE
    );

    $conexion->close();

    exit;
}


$stmt->bind_param(
    "s",
    $email
);


/*
=========================================================
EJECUTAR
=========================================================
*/

if (
    !$stmt->execute()
) {

    echo json_encode(
        [
            "ok" => false,
            "error" =>
                "Error ejecutando consulta: " .
                $stmt->error,
            "materiales" => []
        ],
        JSON_UNESCAPED_UNICODE
    );

    $stmt->close();

    $conexion->close();

    exit;
}


$resultado =
    $stmt->get_result();


$materiales = [];


/*
=========================================================
RECORRER CARRITO
=========================================================
*/

while (
    $fila =
        $resultado->fetch_assoc()
) {

    /*
    -----------------------------------------------------
    DATOS BÁSICOS
    -----------------------------------------------------
    */

    $idMaterial =
        $fila["ID_material"];


    $nombre =
        trim(
            (string)$fila["Nombre"]
        );


    $tipo =
        trim(
            (string)$fila["Tipo"]
        );


    /*
    -----------------------------------------------------
    DESCRIPCIÓN
    -----------------------------------------------------
    */

    $descripcion =
        trim(
            (string)$fila["Descripcion"]
        );


    /*
    =====================================================
    IMPORTANTE
    =====================================================

    Algunas descripciones están vacías en la base de
    datos.

    En las páginas de materiales actualmente tenés un
    sistema que muestra:

        Nombre + Tipo

    cuando la descripción está vacía.

    Hacemos exactamente lo mismo acá para que el carrito
    muestre el mismo contenido.
    */

    if (
        $descripcion === ""
    ) {

        $descripcion =
            trim(
                $nombre .
                " " .
                $tipo
            );

    }


    /*
    -----------------------------------------------------
    SI TODAVÍA ESTÁ VACÍA
    -----------------------------------------------------
    */

    if (
        $descripcion === ""
    ) {

        $descripcion =
            "Material sin descripción";

    }


    /*
    -----------------------------------------------------
    CANTIDAD
    -----------------------------------------------------
    */

    $cantidad =
        intval(
            $fila["cantidad"]
        );


    /*
    -----------------------------------------------------
    PRECIO
    -----------------------------------------------------
    */

    $precio =
        floatval(
            $fila["Precio"]
        );


    /*
    -----------------------------------------------------
    IMAGEN
    -----------------------------------------------------
    */

    $imagen =
        isset(
            $fila["Imagen"]
        )
            ? $fila["Imagen"]
            : "";


    /*
    =====================================================
    AGREGAR AL ARRAY
    =====================================================
    */

    $materiales[] = [

        "ID_material" =>
            $idMaterial,

        "cantidad" =>
            $cantidad,

        "nombre" =>
            $nombre,

        "descripcion" =>
            $descripcion,

        "precio" =>
            $precio,

        "material" =>
            $tipo,

        "imagen" =>
            $imagen

    ];

}


/*
=========================================================
RESPUESTA
=========================================================
*/

echo json_encode(
    [
        "ok" => true,
        "materiales" => $materiales
    ],
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
);


/*
=========================================================
CERRAR
=========================================================
*/

$stmt->close();

$conexion->close();

?>
