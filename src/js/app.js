// evento que se ejecuta luego de que haya cargado el HTML, el CSS y el JS
document.addEventListener("DOMContentLoaded", () => {
    eventListeners();
    darkMode();
})

// modo oscuro del sitio
function darkMode() {


    // detecta si en las preferencias de sistema del cliente esta activado el modo oscuro, y en ese caso activa el modo oscuro por defecto
    // tambien reacciona en tiempo real si el usuario cambia las preferencias del sistema
    /* const prefiereDarkMode = window.matchMedia('(prefers-color-scheme: dark)');
    if(prefiereDarkMode.matches) {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
    prefiereDarkMode.addEventListener('change', function() {
        if(prefiereDarkMode.matches) {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
    }); */
    
    // detecta si en las preferencias de sistema del cliente esta activado el modo oscuro, y en ese caso activa el modo oscuro por defecto
    const prefiereDarkMode = window.matchMedia('(prefers-color-scheme: dark)');
    prefiereDarkMode.matches? document.body.classList.add('dark-mode') : document.body.classList.remove('dark-mode');
    
    // tambien reacciona en tiempo real si el usuario cambia las preferencias del sistema
    prefiereDarkMode.addEventListener("change", () => {
        prefiereDarkMode.matches? document.body.classList.add('dark-mode') : document.body.classList.remove('dark-mode');
    })

    const botonDarkMode = document.querySelector(".dark-mode-boton");
    botonDarkMode.addEventListener("click", () => {
        document.body.classList.toggle("dark-mode");
    });
}

function eventListeners(){
    const mobileMenu = document.querySelector(".mobile-menu");
    mobileMenu.addEventListener("click", navegacionResponsive);
}

function navegacionResponsive() {
    const navegacion = document.querySelector(".navegacion");
    navegacion.classList.toggle("mostrar");
}

