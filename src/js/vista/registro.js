document.addEventListener('DOMContentLoaded', function () {
    const regexEmail = /^[^@]+@gmail.com$/i;
    const regexTexto = /^[a-zA-Z0-9\s]+$/;
    let inputNombre = document.getElementById('nombre');
    let inputEmail = document.getElementById('email');
    let inputPassword = document.getElementById('password');
    let inputPassword2 = document.getElementById('password2');
    let formRegistro = document.getElementById('formRegistro');
    let divNombre = document.getElementById('errorNombre');
    let divEmail = document.getElementById('errorEmail');
    let divPassword = document.getElementById('errorPassword');
    let divPassword2 = document.getElementById('errorPassword2');
    const botonVer = document.getElementById("iconoContrasena");

    formRegistro.addEventListener('submit', function (e) {
        e.preventDefault();
        let todoCorrecto = true;

        //Validar nombre
        if (!regexTexto.test(inputNombre.value.trim())) {
            todoCorrecto = false;
            divNombre.innerHTML = "El nombre no puede contener carácteres especiales";
        }

        //Validar email
        if (inputEmail.value.trim() == '') {
            todoCorrecto = false;
            divEmail.innerHTML = "El Email no puede estar vacío.";
        }

        if (!regexEmail.test(inputEmail.value.trim())) {
            todoCorrecto = false;
            divEmail.innerHTML = "El Email tiene que ser gmail.com.";
        }

        //Validar contraseña
        if (inputPassword.value.trim() == '') {
            todoCorrecto = false;
            divPassword.innerHTML = "La contraseña no puede estar vacía.";
        }

        //Validar contraseñas iguales

        if (inputPassword.value.trim() != inputPassword2.value.trim()) {
            todoCorrecto = false;
            divPassword2.innerHTML = "Las contraseñas no coinciden";
        }

        if (todoCorrecto)
            formRegistro.submit();
    });
    // Live validation for password matching
    function validarPass() {
        if (inputPassword2.value.length > 0) {
            if (inputPassword.value === inputPassword2.value) {
                inputPassword.style.border = "3px solid green";
                inputPassword2.style.border = "3px solid green";
                divPassword2.innerHTML = "";
            } else {
                inputPassword.style.border = "3px solid red";
                inputPassword2.style.border = "3px solid red";
            }
        } else {
            inputPassword.style.border = "1px solid #ccc";
            inputPassword2.style.border = "1px solid #ccc";
        }
    }

    inputPassword.addEventListener('input', validarPass);
    inputPassword2.addEventListener('input', validarPass);

    if (botonVer) {
        botonVer.addEventListener("click", () => {
            if (inputPassword.type === "password") {
                inputPassword.type = "text";
                botonVer.textContent = "🔓";
            } else {
                inputPassword.type = "password";
                botonVer.textContent = "🔒";
            }
        });
    }
});

