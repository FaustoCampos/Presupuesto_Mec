<?php

session_start();

date_default_timezone_set("America/Argentina/Buenos_Aires");

require_once(__DIR__ . "/fpdf.php");

// ============================================================
// CONVERSIÓN DE TEXTO PARA FPDF
// ============================================================

function textoPDF($texto)
{
    $texto = (string)$texto;

    // Reemplazar algunos caracteres Unicode que ISO-8859-1 no soporta
    $texto = str_replace(
        [
            "–",
            "—",
            "“",
            "”",
            "‘",
            "’",
            "•",
            "✓",
            "×",
            "…"
        ],
        [
            "-",
            "-",
            '"',
            '"',
            "'",
            "'",
            "-",
            "OK",
            "x",
            "..."
        ],
        $texto
    );

    $convertido = @iconv(
        "UTF-8",
        "ISO-8859-1//TRANSLIT",
        $texto
    );

    return ($convertido !== false) ? $convertido : $texto;
}


// ============================================================
// CALCULAR CANTIDAD DE LÍNEAS
// ============================================================

function calcularLineas($pdf, $texto, $ancho)
{
    $texto = trim((string)$texto);

    if ($texto === '') {
        return 1;
    }

    $palabras = explode(' ', $texto);

    $lineas = 1;
    $lineaActual = '';

    foreach ($palabras as $palabra) {

        if ($palabra === '') {
            continue;
        }

        $prueba = ($lineaActual === '')
            ? $palabra
            : $lineaActual . ' ' . $palabra;

        // Dejamos 2 mm de margen interno
        if ($pdf->GetStringWidth($prueba) > ($ancho - 2)) {

            $lineas++;

            $lineaActual = $palabra;

        } else {

            $lineaActual = $prueba;
        }
    }

    return $lineas;
}


// ============================================================
// CLASE PDF
// ============================================================

class PresupuestoPDF extends FPDF
{
    function Header()
    {
        // Título
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(40, 40, 40);

        $this->Cell(
            0,
            10,
            textoPDF("PRESUPUESTO"),
            0,
            1,
            'C'
        );

        // Subtítulo
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(100, 100, 100);

        $this->Cell(
            0,
            6,
            textoPDF("Presupuesto de materiales"),
            0,
            1,
            'C'
        );

        // Separador
        $this->Ln(3);

        $this->SetDrawColor(80, 80, 80);

        $this->Line(
            10,
            $this->GetY(),
            200,
            $this->GetY()
        );

        $this->Ln(7);
    }


    function Footer()
    {
        $this->SetY(-15);

        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(120, 120, 120);

        $this->Cell(
            0,
            5,
            textoPDF("Presupuesto Mecánica ITLF"),
            0,
            0,
            'L'
        );

        $this->Cell(
            0,
            5,
            textoPDF("Página " . $this->PageNo()),
            0,
            0,
            'R'
        );
    }
}


// ============================================================
// ENCABEZADO DE LA TABLA
// ============================================================

function dibujarEncabezadoTabla($pdf)
{
    $anchoMaterial = 30;
    $anchoDescripcion = 52;
    $anchoCantidad = 22;
    $anchoPrecio = 30;
    $anchoTotal = 46;

    $altura = 9;

    $pdf->SetFont('Arial', 'B', 9);

    $pdf->SetFillColor(230, 230, 230);
    $pdf->SetTextColor(40, 40, 40);
    $pdf->SetDrawColor(160, 160, 160);

    $pdf->Cell(
        $anchoMaterial,
        $altura,
        textoPDF("Material"),
        1,
        0,
        'C',
        true
    );

    $pdf->Cell(
        $anchoDescripcion,
        $altura,
        textoPDF("Descripción"),
        1,
        0,
        'C',
        true
    );

    $pdf->Cell(
        $anchoCantidad,
        $altura,
        textoPDF("Cantidad"),
        1,
        0,
        'C',
        true
    );

    $pdf->Cell(
        $anchoPrecio,
        $altura,
        textoPDF("Precio"),
        1,
        0,
        'C',
        true
    );

    $pdf->Cell(
        $anchoTotal,
        $altura,
        textoPDF("Total"),
        1,
        1,
        'C',
        true
    );

    $pdf->SetFont('Arial', '', 9);
}


// ============================================================
// OBTENER DATOS RECIBIDOS
// ============================================================

$datos = $_POST['carrito'] ?? '';

if (empty($datos)) {

    die("No se recibieron los datos del carrito.");
}

$materiales = json_decode($datos, true);

if (!is_array($materiales)) {

    die("Los datos del carrito no son válidos.");
}


// ============================================================
// CREAR PDF
// ============================================================

$pdf = new PresupuestoPDF('P', 'mm', 'A4');

$pdf->SetMargins(10, 15, 10);

$pdf->SetAutoPageBreak(true, 20);

$pdf->AddPage();

$pdf->Image(__DIR__ . "/Imagenes/logoPresu.png", 175, 10, 25);

$pdf->Image(__DIR__ . "/Imagenes/LogoTec.png", 10, 5, 25);


// ============================================================
// INFORMACIÓN DEL PRESUPUESTO
// ============================================================

$pdf->SetFont('Arial', '', 9);

$pdf->SetTextColor(70, 70, 70);

$pdf->Cell(
    0,
    6,
    textoPDF(
        "Fecha: " . date("d/m/Y H:i")
    ),
    0,
    1,
    'R'
);

// ============================================================
// DATOS DEL USUARIO
// ============================================================
//
// Al iniciar sesión, Login/back.php obtiene nombre y curso
// desde la tabla Usuarios y los guarda en $_SESSION.
// Aquí los recuperamos para mostrarlos en el PDF.
//
$nombreUsuario = trim((string)($_SESSION["nombre"] ?? ""));
$cursoUsuario = trim((string)($_SESSION["curso"] ?? ""));

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(70, 70, 70);

$pdf->Cell(
    0,
    6,
    textoPDF("Nombre: " . ($nombreUsuario !== "" ? $nombreUsuario : "No disponible")),
    0,
    1,
    'L'
);

$pdf->Cell(
    0,
    6,
    textoPDF("Curso: " . ($cursoUsuario !== "" ? $cursoUsuario : "No disponible")),
    0,
    1,
    'L'
);

$pdf->Ln(4);


// ============================================================
// ANCHOS DE LAS COLUMNAS
// TOTAL = 180 mm
// ============================================================

$anchoMaterial = 30;
$anchoDescripcion = 52;
$anchoCantidad = 22;
$anchoPrecio = 30;
$anchoTotal = 46;


// ============================================================
// ENCABEZADO DE TABLA
// ============================================================

dibujarEncabezadoTabla($pdf);


// ============================================================
// VARIABLES GENERALES
// ============================================================

$totalGeneral = 0;
$totalUnidades = 0;

$alturaLinea = 5;


// ============================================================
// RECORRER MATERIALES
// ============================================================

foreach ($materiales as $item) {

    // --------------------------------------------------------
    // DATOS
    // --------------------------------------------------------

    $nombre = trim((string)($item['nombre'] ?? ''));

    $tipo = trim((string)($item['material'] ?? ''));

    $material = $nombre;

    if ($tipo !== '') {

        if ($material !== '') {
            $material .= " - ";
        }

        $material .= $tipo;
    }


    // --------------------------------------------------------
    // DESCRIPCIÓN
    // --------------------------------------------------------

    $descripcion = trim(
        (string)($item['descripcion'] ?? '')
    );

    // Convertir saltos de línea en comas
    $descripcion = preg_replace(
        "/\r\n|\r|\n/",
        ", ",
        $descripcion
    );

    // Evitar comas duplicadas
    $descripcion = preg_replace(
        '/,\s*,+/',
        ', ',
        $descripcion
    );

    $descripcion = trim(
        $descripcion,
        " ,"
    );


    if ($descripcion === '') {
        $descripcion = "Sin descripción";
    }


    // --------------------------------------------------------
    // CANTIDAD
    // --------------------------------------------------------

    $cantidadNumero = (int)(
        $item['cantidad'] ?? 0
    );

    $cantidad = (string)$cantidadNumero;


    // --------------------------------------------------------
    // PRECIO
    // --------------------------------------------------------

    $precio = (float)(
        $item['precio'] ?? 0
    );

    $total = $precio * $cantidadNumero;


    $precioTexto =
        "$ " .
        number_format(
            $precio,
            2,
            ',',
            '.'
        );


    $totalTexto =
        "$ " .
        number_format(
            $total,
            2,
            ',',
            '.'
        );


    // --------------------------------------------------------
    // ACUMULADORES
    // --------------------------------------------------------

    $totalGeneral += $total;

    $totalUnidades += $cantidadNumero;


    // --------------------------------------------------------
    // CONVERTIR TEXTO PARA FPDF
    // --------------------------------------------------------

    $materialPDF = textoPDF($material);

    $descripcionPDF = textoPDF($descripcion);

    $cantidadPDF = textoPDF($cantidad);

    $precioPDF = textoPDF($precioTexto);

    $totalPDF = textoPDF($totalTexto);


    // --------------------------------------------------------
    // CALCULAR LÍNEAS DE CADA COLUMNA
    // --------------------------------------------------------

    $lineasMaterial = calcularLineas(
        $pdf,
        $materialPDF,
        $anchoMaterial
    );

    $lineasDescripcion = calcularLineas(
        $pdf,
        $descripcionPDF,
        $anchoDescripcion
    );

    $lineasCantidad = calcularLineas(
        $pdf,
        $cantidadPDF,
        $anchoCantidad
    );

    $lineasPrecio = calcularLineas(
        $pdf,
        $precioPDF,
        $anchoPrecio
    );

    $lineasTotal = calcularLineas(
        $pdf,
        $totalPDF,
        $anchoTotal
    );


    // --------------------------------------------------------
    // TOMAR LA COLUMNA MÁS ALTA
    // --------------------------------------------------------

    $maxLineas = max(
        $lineasMaterial,
        $lineasDescripcion,
        $lineasCantidad,
        $lineasPrecio,
        $lineasTotal
    );


    // --------------------------------------------------------
    // ALTURA DEFINITIVA DE LA FILA
    // --------------------------------------------------------

    $alturaFila = max(
        8,
        $maxLineas * $alturaLinea
    );


    // --------------------------------------------------------
    // CONTROL DE PÁGINA
    // --------------------------------------------------------

    if (
        $pdf->GetY() + $alturaFila >
        270
    ) {

        $pdf->AddPage();

        dibujarEncabezadoTabla($pdf);
    }


    // --------------------------------------------------------
    // POSICIÓN INICIAL
    // --------------------------------------------------------

    $x = $pdf->GetX();
    $y = $pdf->GetY();


    // --------------------------------------------------------
    // DIBUJAR PRIMERO TODAS LAS CELDAS
    // CON LA MISMA ALTURA
    // --------------------------------------------------------

    $pdf->SetDrawColor(
        180,
        180,
        180
    );

    $pdf->SetFillColor(
        255,
        255,
        255
    );


    // Material
    $pdf->Rect(
        $x,
        $y,
        $anchoMaterial,
        $alturaFila
    );


    // Descripción
    $pdf->Rect(
        $x + $anchoMaterial,
        $y,
        $anchoDescripcion,
        $alturaFila
    );


    // Cantidad
    $pdf->Rect(
        $x +
        $anchoMaterial +
        $anchoDescripcion,
        $y,
        $anchoCantidad,
        $alturaFila
    );


    // Precio
    $pdf->Rect(
        $x +
        $anchoMaterial +
        $anchoDescripcion +
        $anchoCantidad,
        $y,
        $anchoPrecio,
        $alturaFila
    );


    // Total
    $pdf->Rect(
        $x +
        $anchoMaterial +
        $anchoDescripcion +
        $anchoCantidad +
        $anchoPrecio,
        $y,
        $anchoTotal,
        $alturaFila
    );


    // --------------------------------------------------------
    // MATERIAL
    // --------------------------------------------------------

    $pdf->SetXY(
        $x + 1,
        $y + 1
    );

    $pdf->MultiCell(
        $anchoMaterial - 2,
        $alturaLinea,
        $materialPDF,
        0,
        'L'
    );


    // --------------------------------------------------------
    // DESCRIPCIÓN
    // --------------------------------------------------------

    $pdf->SetXY(
        $x +
        $anchoMaterial +
        1,
        $y + 1
    );

    $pdf->MultiCell(
        $anchoDescripcion - 2,
        $alturaLinea,
        $descripcionPDF,
        0,
        'L'
    );


    // --------------------------------------------------------
    // CANTIDAD
    // --------------------------------------------------------

    $pdf->SetXY(
        $x +
        $anchoMaterial +
        $anchoDescripcion +
        1,
        $y + 1
    );

    $pdf->MultiCell(
        $anchoCantidad - 2,
        $alturaLinea,
        $cantidadPDF,
        0,
        'C'
    );


    // --------------------------------------------------------
    // PRECIO
    // --------------------------------------------------------

    $pdf->SetXY(
        $x +
        $anchoMaterial +
        $anchoDescripcion +
        $anchoCantidad +
        1,
        $y + 1
    );

    $pdf->MultiCell(
        $anchoPrecio - 2,
        $alturaLinea,
        $precioPDF,
        0,
        'R'
    );


    // --------------------------------------------------------
    // TOTAL
    // --------------------------------------------------------

    $pdf->SetXY(
        $x +
        $anchoMaterial +
        $anchoDescripcion +
        $anchoCantidad +
        $anchoPrecio +
        1,
        $y + 1
    );

    $pdf->MultiCell(
        $anchoTotal - 2,
        $alturaLinea,
        $totalPDF,
        0,
        'R'
    );


    // --------------------------------------------------------
    // AVANZAR EXACTAMENTE LA ALTURA DE LA FILA
    // --------------------------------------------------------

    $pdf->SetXY(
        $x,
        $y + $alturaFila
    );
}


// ============================================================
// TOTAL FINAL
// ============================================================

$pdf->Ln(6);

$pdf->SetFont(
    'Arial',
    'B',
    10
);

$pdf->SetTextColor(
    40,
    40,
    40
);


// ------------------------------------------------------------
// TOTAL
// ------------------------------------------------------------

$anchoEtiquetaTotal = 134;
$anchoValorTotal = 46;

$pdf->SetFillColor(
    235,
    235,
    235
);

$pdf->SetDrawColor(
    160,
    160,
    160
);

$pdf->Cell(
    $anchoEtiquetaTotal,
    10,
    textoPDF("TOTAL GENERAL"),
    1,
    0,
    'R',
    true
);

$pdf->Cell(
    $anchoValorTotal,
    10,
    textoPDF(
        "$ " .
        number_format(
            $totalGeneral,
            2,
            ',',
            '.'
        )
    ),
    1,
    1,
    'R',
    true
);


// ============================================================
// CANTIDAD TOTAL DE UNIDADES
// ============================================================

$pdf->Ln(3);

$pdf->SetFont(
    'Arial',
    '',
    9
);

$pdf->SetTextColor(
    70,
    70,
    70
);

$pdf->Cell(
    0,
    6,
    textoPDF(
        "Cantidad total de unidades: " .
        $totalUnidades
    ),
    0,
    1,
    'R'
);


// ============================================================
// OBSERVACIONES
// ============================================================

$pdf->Ln(5);

$pdf->SetFont(
    'Arial',
    'B',
    9
);

$pdf->Cell(
    0,
    6,
    textoPDF("Observaciones"),
    0,
    1,
    'L'
);

$pdf->SetFont(
    'Arial',
    '',
    9
);

$pdf->SetTextColor(
    90,
    90,
    90
);

$pdf->MultiCell(
    0,
    5,
    textoPDF(
        "Los precios indicados corresponden al presupuesto generado a partir de los materiales seleccionados."
    ),
    0,
    'L'
);


// ============================================================
// DESCARGAR PDF
// ============================================================

$nombreArchivo =
    "presupuesto_" .
    date("Y-m-d_H-i-s") .
    ".pdf";

$pdf->Output(
    "D",
    $nombreArchivo
);

exit;

?>