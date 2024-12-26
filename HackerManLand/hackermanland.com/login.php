<?php
session_start();

// Ruta al archivo .env
$dotenv_path = __DIR__ . '/../.env';

// Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica si el archivo .env existe y puede leerse
if (file_exists($dotenv_path)) {
    $lines = file($dotenv_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Ignorar líneas que comienzan con "#" (comentarios) y las que no contienen un "="
        if (strpos($line, '#') !== 0 && strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
} else {
    die("El archivo .env no se encuentra en la ruta especificada.");
}

// Obtener los valores de conexión del archivo .env con valores predeterminados
$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$db_username = $_ENV['DB_USERNAME'] ?? 'user';
$password = $_ENV['DB_PASSWORD'] ?? 'defaultpassword';
$database = $_ENV['DB_DATABASE'] ?? 'hackermanland';

$conn = new mysqli($host, $db_username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consultar a la base de datos (vulnerable a SQL Injection)
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // de resultado quiero el username
        echo "Inicio de sesión exitoso. Bienvenido, " . htmlspecialchars($username) . "!";
        $_SESSION['user'] = $username;
        header('Location: /botnet/'); //TODO cambiar por el visualizador de shells diferentes ip
    } else {
        echo "Nombre de usuario o contraseña incorrectos.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        /* Estilo general */
        body {
            margin: 0;
            padding: 0;
            background: radial-gradient(circle, #001a00 0%, #000000 100%);
            color: #00ff00;
            font-family: 'Courier New', Courier, monospace;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }

        /* Contenedor del login */
        .login-container {
            background-color: #002200;
            border: 2px solid #00ff00;
            border-radius: 10px;
            padding: 30px 20px;
            box-shadow: 0 0 20px #00ff00;
            width: 300px;
            text-align: center;
        }

        /* Títulos */
        h2 {
            margin: 0 0 20px;
            color: #00cc00;
            font-size: 24px;
        }

        /* Inputs */
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 90%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #004d00;
            border-radius: 5px;
            background-color: #000000;
            color: #00ff00;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border: 1px solid #00ff00;
            outline: none;
            box-shadow: 0 0 5px #00ff00;
        }

        /* Botón */
        input[type="submit"] {
            background-color: #004d00;
            color: #00ff00;
            padding: 10px 20px;
            border: 1px solid #00ff00;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #00ff00;
            color: #002200;
        }

        /* Efecto dinámico (parpadeo) */
        .blinking {
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="blinking">Login</h2>
        <form method="post" action="">
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
