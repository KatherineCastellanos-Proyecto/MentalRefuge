// 1. Lógica para los Emojis (Estado de Ánimo)
function setMood(mood, el) {
    // Quitamos la clase activa de todos
    document.querySelectorAll('.emoji-btn').forEach(e => {
        e.classList.remove('active');
        e.style.opacity = "0.5";
        e.style.filter = "grayscale(100%)";
    });
    // Activo el seleccionado
    el.classList.add('active');
    el.style.opacity = "1";
    el.style.filter = "grayscale(0%)";
    document.getElementById('mood-label').innerText = "Hoy estás: " + mood;
}

// 2. Funciones de Perfil y Personalización
function changeAvatar(src) {
    const bigAvatar = document.getElementById('big-avatar');
    const navAvatar = document.getElementById('nav-avatar');
    if(bigAvatar) bigAvatar.src = src;
    if(navAvatar) navAvatar.src = src;
}

function setTheme(main, bg) {
    document.documentElement.style.setProperty('--main-color', main);
    document.documentElement.style.setProperty('--bg-color', bg);
    // Guardar en el navegador para que no se pierda al cambiar de sección
    localStorage.setItem('tema-principal', main);
    localStorage.setItem('tema-bg', bg);
}

// 3. Cargar el tema guardado al abrir la página
window.onload = function() {
    const mainSaved = localStorage.getItem('tema-principal');
    const bgSaved = localStorage.getItem('tema-bg');
    if(mainSaved && bgSaved) {
        setTheme(mainSaved, bgSaved);
    }
}

function saveProfile() {
    const name = document.getElementById('edit-name').value;
    alert("¡Perfil actualizado con éxito, " + name + "!");
}