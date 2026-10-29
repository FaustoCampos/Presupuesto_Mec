<?php
    session_start(); //Inicio la Sesion

    if(!isset($_SESSION["Sesion"])) //En el caso de que no exista la sesion 
    {
        $_SESSION["Sesion"] = false; //Inicio la sesion en falso

        header("Location: https://mecanicapp.xo.je/index.php"); //Lo mando al index
    }
    else if($_SESSION["Sesion"]==false) //En el caso que no este logueado lo rebota
    {
        echo "<script type='text/javascript'>
        alert('Debes loguearte para acceder aquí');
        window.location.href = 'https://mecanicapp.xo.je/index.php';
        </script>";
        exit(); //Muestro mensaje y lo mando a otra broma
    }
?>

<!DOCTYPE html>

    <html lang="es-AR">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="description" content="Revisá los materiales seleccionados para tu presupuesto.">
        <title>Carrito | Presupuesto Mecánica</title>

        <link rel="shortcut icon" href="/Imagenes/logo.png" type="image/x-icon">

        <link rel="stylesheet" href="/style-carrito.css">

    </head>

    <body>

    	<header class="site-header">


<nav class="main-nav" aria-label="Navegación principal">

    <button type="button" class="nav-back-button" onclick="history.back()" aria-label="Volver a la página anterior" title="Volver a la página anterior">←</button>
    
    <a
        class="brand"
        href="/index.php"
        aria-label="Ir al inicio"
    >
        <img
            src="/Imagenes/logo.png"
            alt="Logo de Presupuesto Mec"
        >
    </a>


    <a href="/carrito.php" class="carrito-nav" aria-label="Abrir carrito" title="Carrito">
        <span class="carrito-icon" aria-hidden="true">🛒</span>
        <span>Carrito</span>
    </a>

    <a
        class="profile-link"
        href="/Login/Cuenta.php"
        aria-label="Mi cuenta"
    >
        <img
            src="/Imagenes/Invitado.png"
            alt=""
        >

        <span>Mi cuenta</span>
    </a>



</header>

    <main class="cart-page">

    <div class="cart-heading">

        <div>
            <p class="eyebrow">TU PRESUPUESTO</p>

            <h1>Carrito</h1>
        </div>

        <span
            class="item-count"
            id="material-count"
        >
            Cargando materiales...
        </span>

    </div>


    <div class="cart-layout">

        <section
            class="cart-list"
            aria-label="Materiales seleccionados"
        >


            <div id="cart-products">

                <p>
                    Cargando materiales...
                </p>

            </div>

        </section>


        <aside
            class="summary-card"
            aria-label="Resumen del presupuesto"
        >

            <h2>
                Resumen del presupuesto
            </h2>

            <div class="summary-row">

                <span>Productos (
                    <span id="total-items">0</span>
                    )
                </span>

                <strong id="subtotal">
                    $0
                </strong>

            </div>

            
            <div id="sub_total">

            </div>

            <hr>


            <div class="summary-total">

                <span>
                    Total estimado
                </span>

                <strong id="total">
                    $0
                </strong>

            </div>


            <button
                id="generate-pdf"
                class="primary-button"
                type="button"
            >
                Generar presupuesto PDF
            </button>


            <p class="summary-note">
                El valor final puede variar según medidas,
                disponibilidad y cotización del día.
            </p>

        </aside>

    </div>


    </main>

    <footer>

    <p>
        © 2026 Presupuesto Mecánica ITLF y <a href="/nosotros.php">Equipo FNSJT</a>
    </p>

    </footer>

<script>

const money = new Intl.NumberFormat(
    'es-AR',
    {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }
);


const cartProducts =
    document.getElementById('cart-products');

const totalItems =
    document.getElementById('total-items');

const subtotal =
    document.getElementById('subtotal');

const total =
    document.getElementById('total');

const materialCount =
    document.getElementById('material-count');


/*
=========================================================
CARRITO REAL
=========================================================
*/

let materiales = [];


/*
=========================================================
ESCAPAR HTML
=========================================================
*/

function escapeHTML(text) {

    if (
        text === null ||
        text === undefined
    ) {
        return '';
    }

    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

}


/*
=========================================================
FORMATEAR DINERO
=========================================================
*/

function formatoDinero(valor) {

    return '$' + money.format(
        Number(valor) || 0
    );

}


/*
=========================================================
ACTUALIZAR RESUMEN
=========================================================
*/
function nombreCorto(nombre) {

    nombre = String(nombre || '');

    if (nombre.length > 22) {
        return nombre.substring(0, 22) + '...   ';
    }

    return nombre;
}

function mostrar_sub_total(material) {

    const contenedor =
        document.getElementById('sub_total');

    const nombreOriginal =
        material.nombre ||
        material.Nombre ||
        'Material';

    const nombre =
        nombreCorto(nombreOriginal);

    const cantidad =
        Number(material.cantidad) || 0;

    const precio =
        Number(material.precio) || 0;

    const totalProducto =
        cantidad * precio;

    const tarjeta =
        document.createElement('div');

    tarjeta.dataset.nombre =
        nombre;

    tarjeta.innerHTML = `
        <span>
            ${escapeHTML(nombre)}
            (${formatoDinero(totalProducto)})
        </span>
    `;

    contenedor.appendChild(tarjeta);
}




function updateCart() {

    let totalGeneral = 0;
    let cantidadTotal = 0;

    /*
    Limpiar el detalle de productos
    */
    const subTotalContainer =
        document.getElementById('sub_total');

    subTotalContainer.innerHTML = '';


    /*
    Calcular totales reales
    */
    materiales.forEach(material => {

        const cantidad =
            Number(material.cantidad) || 0;

        const precio =
            Number(material.precio) || 0;

        const totalProducto =
            cantidad * precio;

        cantidadTotal += cantidad;

        totalGeneral += totalProducto;


        /*
        Mostrar detalle del producto
        */
        mostrar_sub_total(material);

    });


    /*
    Actualizar resumen
    */
    totalItems.textContent =
        cantidadTotal;

    subtotal.textContent =
        formatoDinero(totalGeneral);

    total.textContent =
        formatoDinero(totalGeneral);


    /*
    Cantidad de materiales diferentes
    */
    const diferentes =
        materiales.length;

    materialCount.textContent =
        diferentes +
        (
            diferentes === 1
                ? ' material seleccionado'
                : ' materiales seleccionados'
        );

}



/*
=========================================================
CREAR PRODUCTO
=========================================================
*/

function crearProducto(material) {

    const id =
        material.ID_material;

    const nombre =
        material.nombre ||
        'Material';

    /*
    IMPORTANTE:
    La descripción viene directamente
    de obtener_carrito.php.
    */

    const descripcion =
        material.descripcion ||
        '';

    const precio =
        Number(material.precio) || 0;

    const tipo =
        material.material ||
        'Material';

    const imagen =
        material.imagen ||
        '';

    const cantidad =
        Number(material.cantidad) || 1;


    const article =
        document.createElement('article');


    article.className =
        'cart-item';


    article.dataset.id =
        id;


    article.dataset.price =
        precio;


    let imagenHTML = '';


    if (imagen !== '') {

        imagenHTML = `
            <img
                class="product-image"
                src="${escapeHTML(imagen)}"
                alt="${escapeHTML(nombre)}"
                onerror="this.style.display='none'"
            >
        `;

    } else {

        imagenHTML = `
            <div
                class="product-image"
                style="
                    display:flex;
                    align-items:center;
                    justify-content:center;
                "
            >
                Sin imagen
            </div>
        `;

    }


    /*
    Guardamos también los datos originales
    dentro del artículo.

    Esto sirve como respaldo y evita
    depender del texto visual.
    */

    article.dataset.nombre =
        nombre;

    article.dataset.descripcion =
        descripcion;

    article.dataset.material =
        tipo;


    article.innerHTML = `

        ${imagenHTML}

        <div class="product-info">

            <span class="tag">
                ${escapeHTML(tipo)}
            </span>

            <h2>
                ${escapeHTML(nombre)}
            </h2>

            <p class="product-detail">
                ${escapeHTML(descripcion)}
            </p>

            <button
                class="remove-button"
                type="button"
            >
                Eliminar
            </button>

        </div>


        <div class="item-purchase">

            <p class="price">
                ${formatoDinero(precio)}
            </p>


            <div
                class="quantity-control"
                aria-label="Cantidad"
            >

                <button
                    class="quantity-button decrement"
                    type="button"
                    aria-label="Restar unidad"
                >
                    −
                </button>

                <span class="quantity">
                    ${cantidad}
                </span>

                <button
                    class="quantity-button increment"
                    type="button"
                    aria-label="Sumar unidad"
                >
                    +
                </button>

            </div>

        </div>

    `;


    return article;

}


/*
=========================================================
CARGAR CARRITO
=========================================================
*/

async function cargarCarrito() {

    try {

        const respuesta =
            await fetch(
                '/obtener_carrito.php',
                {
                    method: 'GET',
                    cache: 'no-cache'
                }
            );


        if (!respuesta.ok) {

            throw new Error(
                'Error HTTP ' +
                respuesta.status
            );

        }


        const datos =
            await respuesta.json();


        console.log(
            'Respuesta del carrito:',
            datos
        );


        if (!datos.ok) {

            throw new Error(
                datos.error ||
                'No se pudo cargar el carrito.'
            );

        }


        /*
        =================================================
        GUARDAR LOS DATOS ORIGINALES
        =================================================
        */

        materiales =
            Array.isArray(datos.materiales)
                ? datos.materiales
                : [];


        cartProducts.innerHTML = '';


        if (materiales.length === 0) {

            cartProducts.innerHTML = `
                <p>
                    No hay materiales en tu carrito.
                </p>
            `;

            updateCart();

            return;

        }


        /*
        =================================================
        MOSTRAR PRODUCTOS
        =================================================
        */

        materiales.forEach(material => {

            const producto =
                crearProducto(material);

            cartProducts.appendChild(
                producto
            );

            mostrar_sub_total(material);

        });


        updateCart();


    } catch (error) {

        console.error(
            'ERROR:',
            error
        );


        cartProducts.innerHTML = `

            <p style="color:red;">

                Error al cargar los materiales:
                ${escapeHTML(error.message)}

            </p>

        `;

    }

}


/*
=========================================================
BOTONES DEL CARRITO
=========================================================
*/

document.addEventListener(
    'click',
    async function(event) {

        const item =
            event.target.closest(
                '.cart-item'
            );


        if (!item) {
            return;
        }


        const quantityElement =
            item.querySelector(
                '.quantity'
            );

        let cantidad =
            Number(
                quantityElement.textContent
            ) || 1;



        const idMaterial =
            item.dataset.id;


        /*
        ================================================
        SUMAR
        ================================================
        */

        if (
            event.target.closest(
                '.increment'
            )
        ) {

            const anterior =
                cantidad;

            cantidad++;

            quantityElement.textContent =
                cantidad;


            try {

                await actualizarCantidadEnBD(
                    idMaterial,
                    cantidad
                );

                const material =
                    materiales.find(
                        m =>
                            String(m.ID_material) ===
                            String(idMaterial)
                    );

                if (material) {
                    material.cantidad = cantidad;
                }

                updateCart();


            } catch (error) {

                quantityElement.textContent =
                    anterior;

                updateCart();

                alert(
                    'No se pudo actualizar la cantidad: ' +
                    error.message
                );

            }

            return;

        }


        /*
        ================================================
        RESTAR
        ================================================
        */

        if (
            event.target.closest(
                '.decrement'
            )
        ) {

            if (cantidad <= 1) {
                return;
            }


            const anterior =
                cantidad;

            cantidad--;

            quantityElement.textContent =
                cantidad;

            try {

                await actualizarCantidadEnBD(
                    idMaterial,
                    cantidad
                );


                const material =
                    materiales.find(
                        m =>
                            String(m.ID_material) ===
                            String(idMaterial)
                    );

                if (material) {
                    material.cantidad = cantidad;
                }


                updateCart();

            } catch (error) {

                quantityElement.textContent =
                    anterior;

                updateCart();

                alert(
                    'No se pudo actualizar la cantidad: ' +
                    error.message
                );

            }


            return;

        }


        /*
        ================================================
        ELIMINAR
        ================================================
        */

        if (
            event.target.closest(
                '.remove-button'
            )
        ) {

            const boton =
                event.target.closest(
                    '.remove-button'
                );


            boton.disabled =
                true;

            boton.textContent =
                'Eliminando...';


            try {

                const response =
                    await fetch(
                        '/eliminar_carrito.php',
                        {
                            method: 'POST',
                            headers: {
                                'Content-Type':
                                    'application/json'
                            },
                            body:
                                JSON.stringify({
                                    id_material:
                                        idMaterial
                                })
                        }
                    );


                const datos =
                    await response.json();


                if (
                    !response.ok ||
                    !datos.ok
                ) {

                    throw new Error(
                        datos.error ||
                        'No se pudo eliminar.'
                    );

                }


                item.remove();


                /*
                Actualizar también el array
                */

                materiales =
                    materiales.filter(
                        material =>
                            String(
                                material.ID_material
                            ) !==
                            String(idMaterial)
                    );


                /*
                Si se eliminó el último material,
                mostrar el mensaje inmediatamente sin
                necesidad de recargar la página.
                */

                if (
                    cartProducts.querySelectorAll(
                        '.cart-item'
                    ).length === 0
                ) {

                    cartProducts.innerHTML = `
                        <p>
                            No hay materiales
                        </p>
                    `;

                }


                updateCart();


            } catch (error) {

                console.error(
                    error
                );


                boton.disabled =
                    false;

                boton.textContent =
                    'Eliminar';


                alert(
                    'No se pudo eliminar el producto: ' +
                    error.message
                );

            }

            return;

        }

    }
);


/*
=========================================================
ACTUALIZAR CANTIDAD EN BASE DE DATOS
=========================================================
*/

async function actualizarCantidadEnBD(
    idMaterial,
    cantidad
) {

    const response =
        await fetch(
            '/actualizar_carrito.php',
            {
                method: 'POST',
                headers: {
                    'Content-Type':
                        'application/json'
                },
                body:
                    JSON.stringify({
                        id_material:
                            idMaterial,
                        cantidad:
                            cantidad
                    })
            }
        );


    const datos =
        await response.json();


    if (
        !response.ok ||
        !datos.ok
    ) {

        throw new Error(
            datos.error ||
            'No se pudo actualizar la cantidad.'
        );

    }


    return datos;

}


/*
=========================================================
GENERAR PDF
=========================================================
*/

document
    .getElementById('generate-pdf')
    .addEventListener(
        'click',
        function() {

            /*
            =================================================
            IMPORTANTE:
            NO LEEMOS LA DESCRIPCIÓN DEL HTML.

            Usamos directamente "materiales", que es la
            información recibida desde la base de datos.
            =================================================
            */

            const items =
                materiales
                    .map(material => {

                        const cantidad =
                            Number(
                                material.cantidad
                            ) || 0;


                        const precio =
                            Number(
                                material.precio
                            ) || 0;


                        return {

                            ID_material:
                                material.ID_material,

                            material:
                                material.material ||
                                material.Tipo ||
                                '',

                            nombre:
                                material.nombre ||
                                material.Nombre ||
                                'Material',

                            descripcion:
                                material.descripcion ||
                                material.Descripcion ||
                                '',

                            cantidad:
                                cantidad,

                            precio:
                                precio,

                            total:
                                cantidad * precio

                        };

                    })
                    .filter(
                        item =>
                            item.cantidad > 0
                    );


            console.log(
                'DATOS QUE SE ENVIAN AL PDF:',
                items
            );


            if (items.length === 0) {

                alert(
                    'No hay materiales en el carrito.'
                );

                return;

            }


            /*
            =================================================
            CREAR FORMULARIO
            =================================================
            */

            const form =
                document.createElement(
                    'form'
                );


            form.method =
                'POST';


            form.action =
                '/procesar.php';


            form.target =
                '_blank';


            /*
            =================================================
            INPUT JSON
            =================================================
            */

            const input =
                document.createElement(
                    'input'
                );


            input.type =
                'hidden';


            input.name =
                'carrito';


            input.value =
                JSON.stringify(items);


            form.appendChild(
                input
            );


            document.body.appendChild(
                form
            );


            /*
            =================================================
            ENVIAR
            =================================================
            */

            form.submit();


            /*
            =================================================
            BORRAR FORMULARIO
            =================================================
            */

            setTimeout(
                function() {
                    form.remove();
                },
                1000
            );

        }
    );


/*
=========================================================
INICIAR
=========================================================
*/

cargarCarrito();


</script>

</body>
</html>
