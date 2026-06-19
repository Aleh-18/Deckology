document.addEventListener('DOMContentLoaded', function() {
    const regexEmail = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    let inputEmail = document.getElementById('email');
    let inputPassword = document.getElementById('password');
    let formLogin = document.getElementById('formLogin');
    let divEmail = document.getElementById('errorEmail');
    let divPassword = document.getElementById('errorPassword');
    const botonVer = document.getElementById("iconoContrasena");

    formLogin.addEventListener('submit', function(e) {
    e.preventDefault();
    let todoCorrecto = true;

    // Limpiar estilos anteriores
    inputEmail.style.border = "";
    inputPassword.style.border = "";
    divEmail.innerHTML = "";
    divPassword.innerHTML = "";

    // Validar email vacío
    if (inputEmail.value.trim() === '') {
        todoCorrecto = false;
        divEmail.innerHTML = "El Email no puede estar vacío.";
        inputEmail.style.border = "2px solid red";
    }

    // Validar formato email
    if (!regexEmail.test(inputEmail.value.trim())) {
        todoCorrecto = false;
        divEmail.innerHTML = "El Email no tiene un formato válido.";
        inputEmail.style.border = "2px solid red";
    }

    // Validar contraseña vacía
    if (inputPassword.value.trim() === '') {
        todoCorrecto = false;
        divPassword.innerHTML = "La contraseña no puede estar vacía.";
        inputPassword.style.border = "2px solid red";
    }
    // Enviar si todo está bien
    if (todoCorrecto) {
        formLogin.submit();
    }
    });
    botonVer.addEventListener("click", () => {
    if (inputPassword.type === "password") {
        inputPassword.type = "text";
        botonVer.textContent = "🔓"; 
    } else {
        inputPassword.type = "password";
        botonVer.textContent = "🔒";
    }
});
});

