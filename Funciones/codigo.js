const btnPerfil = document.getElementById("perfil-btn"); // Apunta al botón del perfil

const popup = document.getElementById("perfil-popup"); // Apunta al menú desplegable

btnPerfil.addEventListener("click", (e) => // Verifica que hagas clic y ejecuta esta acción
                           {
    e.preventDefault(); // Evita que te lleve a perfil.php
    popup.classList.toggle("oculto"); // Cambia el estado de oculto a visible del menú desplegable
});

// Cierra el popup si haces clic fuera
document.addEventListener("click", (e) => // Verifica que hagas clic y ejecuta esta acción
                          {
    if (!btnPerfil.contains(e.target) && !popup.contains(e.target)) 
    {
        popup.classList.add("oculto"); // Pone oculto el menú desplegable
    }
});

// Bloquear Ctrl + Rueda del mouse
document.addEventListener('wheel', (event) => {
    if (event.ctrlKey) {
        event.preventDefault();
    }
}, { passive: false });

// Bloquear Ctrl y combinaciones (+, -, 0)
document.addEventListener('keydown', (event) => {
    if (event.ctrlKey && ['+', '-', '0', '='].includes(event.key)) {
        event.preventDefault();
    }
});