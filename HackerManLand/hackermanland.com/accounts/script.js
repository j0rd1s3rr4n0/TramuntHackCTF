function generateAccount(service) {
    // Función para generar una cadena aleatoria
    function generateRandomString(length) {
        const characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        return result;
    }

    // Generar el email aleatorio
    function generateEmail() {
        const domains = ['@gmail.com', '@yahoo.com'];
        const randomDomain = domains[Math.floor(Math.random() * domains.length)];
        const randomUsername = generateRandomString(10);  // Nombre de usuario aleatorio de 10 caracteres
        return randomUsername + randomDomain;
    }

    // Generar la contraseña aleatoria
    function generatePassword() {
        return generateRandomString(12); // Contraseña aleatoria de 12 caracteres
    }

    // Generar el email y la contraseña
    const email = generateEmail();
    const password = generatePassword();

    // Mostrar el email y la contraseña en el modal
    document.getElementById('email').textContent = email;
    document.getElementById('password').textContent = password;

    // Mostrar el modal
    const modal = document.getElementById('modal');
    modal.style.display = 'block';
}

// Cerrar el modal
document.getElementById('close').onclick = function() {
    document.getElementById('modal').style.display = 'none';
}

// Cerrar el modal si se hace clic fuera de él
window.onclick = function(event) {
    const modal = document.getElementById('modal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}
