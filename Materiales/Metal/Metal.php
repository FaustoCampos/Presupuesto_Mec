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
        alert('Debes loguearte para acceder aquí');
        window.location.href = 'https://mecanicapp.xo.je/index.php';
        </script>";
        exit(); //Muestro mensaje de broma y lo mando a otra broma
      }
$host = "sql301.infinityfree.com";
$user = "if0_42168178";
$password = "promo27lamejor";
$database = "if0_42168178_base_mecanica";

$conexion = new mysqli(
    $host,
    $user,
    $password,
    $database
);

$sql = "SELECT * FROM Materiales WHERE Tipo = ?";

$stmt = mysqli_prepare($conexion, $sql);

$tipo = "Acero";

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $tipo
);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

$datos = mysqli_fetch_all(
    $resultado,
    MYSQLI_ASSOC
);

?>

<!DOCTYPE html>
<html lang="es-AR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Presupuesto Mecánica | Metal</title>

    <link
        rel="shortcut icon"
        href="/Imagenes/logo.png"
        type="image/x-icon"
    >

    <link
        rel="stylesheet"
        href="/style.css"
    >

</head>

<body id="inicio">

<header>

    <nav>

                <button type="button" class="nav-back-button" onclick="history.back()" aria-label="Volver a la página anterior" title="Volver a la página anterior">←</button>

        <a href="/index.php">
            <img
                src="/Imagenes/logo.png"
                loading="lazy"
                alt="Logo de Presupuesto Mec"
                id="logo"
            >
        </a>

        
<a href="/carrito.php" class="carrito-nav" aria-label="Abrir carrito" title="Carrito">
    <span class="carrito-icon" aria-hidden="true">🛒</span>
    <span>Carrito</span>
</a>
<a class="jaz" id="perfil-btn">
            <img
                src="/Imagenes/Invitado.png"
                loading="lazy"
                alt="Imagen de la cuenta"
            >
        </a>

        <div
            id="perfil-popup"
            class="perfil-popup oculto"
        >

            <a href="/Login/Cuenta.php">
                <img
                    loading="lazy"
                    src="/Imagenes/Persona.png"
                    alt="Imagen de Registrarse"
                >
                Mi Cuenta
            </a>

            <a href="/Login/Cerrar.php">
                <img
                    loading="lazy"
                    src="/Imagenes/CerrarSesion.png"
                    alt="Imagen de Registrarse"
                >
                Cerrar Sesión
            </a>

        </div>

    </nav>

</header>

<button type="button" class="btn-volver-arriba" aria-label="Volver arriba" title="Volver arriba" onclick="window.history.replaceState(null, document.title, window.location.pathname + window.location.search); window.scrollTo({top: 0, behavior: 'smooth'});">↑</button>


<div id="fila">

    <div id="modal">

        <table>

            <tr>
                <th>Nombre:</th>
                <th id="nommm">fierro</th>
            </tr>

            <tr>
                <th>Descripcion:</th>
                <th id="descripcionnnn">
                    Esto es un fierro de 20cm * 15cm
                </th>
            </tr>

            <tr>
                <th>Precio por Unidad:</th>
                <th id="precio">30$</th>
            </tr>

            <tr>
                    <th>Cantidad:</th>
                    <th>
                        <div class="selector-cantidad">
                            <button type="button" id="menos" aria-label="Disminuir cantidad">−</button>
                            <span id="cantidad">0</span>
                            <button type="button" id="mas" aria-label="Aumentar cantidad">+</button>
                        </div>
                    </th>
                </tr>

            <tr>

                <th>
                    Precio Total:
                </th>

                <th id="total">
                    0
                </th>

            </tr>

        </table>


        <button
            type="button"
            id="aceptar">
            Aceptar
        </button>


        <button
            type="button"
            id="cerrar"
        >
            X
        </button>

    </div>

</div>


<script>
const resultado = <?php echo json_encode($datos); ?>;
const mi_contenedor = document.getElementById("fila");

resultado.forEach((item) => {
    const tarjeta = document.createElement("div");
    tarjeta.classList.add("tarjeta");
    tarjeta.dataset.id = item.ID;
    tarjeta.dataset.sku = item.SKU || "";
    tarjeta.dataset.nombre = item.Nombre || "";

    const descripcionOriginal = item.Descripcion || "";
    tarjeta.dataset.descripcion = descripcionOriginal.length > 50
        ? descripcionOriginal.substring(0, 50) + "..."
        : (descripcionOriginal || ((item.Nombre || "") + " " + (item.Tipo || "")));

    tarjeta.dataset.precio = item.Precio || 0;
    tarjeta.innerHTML = `
        <img src="${item.Imagen || ""}" alt="foto del material">
        <h2>${tarjeta.dataset.nombre}</h2>
        <p>${tarjeta.dataset.descripcion}</p>
    `;
    mi_contenedor.appendChild(tarjeta);
});
</script>

<script>
const nombre_modal = document.getElementById("nommm");
const descripcion = document.getElementById("descripcionnnn");
const precio = document.getElementById("precio");
const numero = document.getElementById("cantidad");
const total = document.getElementById("total");
const modal = document.getElementById("modal");
const aceptar = document.getElementById("aceptar");
const tarjetas = document.querySelectorAll(".tarjeta");

let cantidad = 0;

function actualizarModal() {
    const precioNumero = Number(precio.textContent) || 0;
    numero.textContent = cantidad;
    total.textContent = (cantidad * precioNumero) + "$";
}

function limpiarSeleccion() {
    const anterior = document.getElementById("antes");
    if (anterior) {
        anterior.id = "";
        anterior.classList.remove("seleccionada");
    }
    cantidad = 0;
    numero.textContent = "0";
    total.textContent = "0";
}

tarjetas.forEach((tarjeta) => {
    tarjeta.addEventListener("click", () => {
        const anterior = document.getElementById("antes");

        if (anterior === tarjeta) {
            limpiarSeleccion();
            modal.classList.remove("activo");
            return;
        }

        if (anterior) {
            anterior.id = "";
            anterior.classList.remove("seleccionada");
        }

        tarjeta.id = "antes";
        tarjeta.classList.add("seleccionada");
        cantidad = 0;

        nombre_modal.textContent = tarjeta.dataset.nombre;
        descripcion.textContent = tarjeta.dataset.descripcion;
        precio.textContent = tarjeta.dataset.precio;
        actualizarModal();
        modal.classList.add("activo");
    });
});

document.getElementById("mas").addEventListener("click", (event) => {
    event.stopPropagation();
    if (!document.getElementById("antes")) return;
    cantidad++;
    actualizarModal();
});

document.getElementById("menos").addEventListener("click", (event) => {
    event.stopPropagation();
    if (!document.getElementById("antes")) return;
    cantidad = Math.max(0, cantidad - 1);
    actualizarModal();
});

aceptar.addEventListener("click", async (event) => {
    event.stopPropagation();

    if (cantidad <= 0) {
        alert("Elegí una cantidad mayor que 0.");
        return;
    }

    const seleccionado = document.getElementById("antes");
    if (!seleccionado) {
        alert("No hay ningún producto seleccionado.");
        return;
    }

    const idMaterial = seleccionado.dataset.id;
    if (!idMaterial) {
        alert("No se encontró el ID del material.");
        return;
    }

    aceptar.disabled = true;
    aceptar.textContent = "Guardando...";

    try {
        const response = await fetch("/Materiales/guardar.php", {
method: "POST",
headers: {
"Content-Type": "application/json"
},
body: JSON.stringify({
id_material: idMaterial,
cantidad: cantidad
})
});

const texto = await response.text();

console.log("Respuesta de guardar.php:", texto);

let resultado;

try {
resultado = JSON.parse(texto);
} catch (error) {

```
throw new Error(
    "El servidor devolvió una respuesta inesperada: " + texto
);
```

}

if (!response.ok || !resultado.ok) {

```
throw new Error(
    resultado.error || "No se pudo guardar el producto."
);
```

}

limpiarSeleccion();
        modal.classList.remove("activo");
        aceptar.textContent = "¡Agregado!";
        setTimeout(() => {
            aceptar.disabled = false;
            aceptar.textContent = "Aceptar";
        }, 700);

    } catch (error) {
        console.error(error);
        alert("No se pudo guardar el producto: " + error.message);
        aceptar.disabled = false;
        aceptar.textContent = "Aceptar";
    }
});

document.getElementById("cerrar").addEventListener("click", (event) => {
    event.stopPropagation();
    limpiarSeleccion();
    modal.classList.remove("activo");
});
</script>
<script src="/Funciones/codigo.js"></script> <!-- Script con las funciones basicas de la pagina con js -->
</body>

</html>