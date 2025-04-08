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
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: black;
      color: #00ff00;
      font-family: 'Courier New', Courier, monospace;
      overflow: hidden;
    }

    .login-container {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(0, 0, 0, 0.8);
      border: 2px solid #00ff00;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 20px #00ff00;
      text-align: center;
    }

    h2.blinking {
      animation: blink 1.5s steps(2, start) infinite;
    }

    @keyframes blink {
      50% {
        opacity: 0;
      }
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-size: 14px;
    }

    input[type="text"],
    input[type="password"] {
      width: 95%;
      padding: 10px;
      margin-bottom: 15px;
      background: black;
      border: 1px solid #00ff00;
      color: #00ff00;
      border-radius: 5px;
    }

    input[type="submit"] {
      background: #00ff00;
      color: black;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 5px;
      font-weight: bold;
    }

    input[type="submit"]:hover {
      background: #00cc00;
    }

    #particles-js {
      position: absolute;
      width: 100%;
      height: 100%;
      z-index: -1;
    }
  </style>
</head>

<body>
  <div id="particles-js"></div>
  <div class="login-container">
    <h2 class="blinking">Login</h2>
    <form method="post" action="">
      <label for="username">Nombre de usuario:</label>
      <input type="text" id="username" name="username" required="">
      <label for="password">Contraseña:</label>
      <input type="password" id="password" name="password" required="">
      <input type="submit" value="Login">
    </form>
  </div>
  <script>
    particlesJS("particles-js", {
      "particles": {
        "number": {
          "value": 100,
          "density": {
            "enable": true,
            "value_area": 800
          }
        },
        "color": {
          "value": "#00ff00"
        },
        "shape": {
          "type": "circle",
          "stroke": {
            "width": 0,
            "color": "#000000"
          }
        },
        "opacity": {
          "value": 0.5,
          "random": false,
          "anim": {
            "enable": false,
            "speed": 1,
            "opacity_min": 0.1,
            "sync": false
          }
        },
        "size": {
          "value": 3,
          "random": true,
          "anim": {
            "enable": false,
            "speed": 40,
            "size_min": 0.1,
            "sync": false
          }
        },
        "line_linked": {
          "enable": true,
          "distance": 150,
          "color": "#00ff00",
          "opacity": 0.4,
          "width": 1
        },
        "move": {
          "enable": true,
          "speed": 6,
          "direction": "none",
          "random": false,
          "straight": false,
          "out_mode": "out",
          "attract": {
            "enable": false,
            "rotateX": 600,
            "rotateY": 1200
          }
        }
      },
      "interactivity": {
        "detect_on": "canvas",
        "events": {
          "onhover": {
            "enable": true,
            "mode": "repulse"
          },
          "onclick": {
            "enable": true,
            "mode": "push"
          },
          "resize": true
        },
        "modes": {
          "grab": {
            "distance": 400,
            "line_linked": {
              "opacity": 1
            }
          },
          "bubble": {
            "distance": 400,
            "size": 40,
            "duration": 2,
            "opacity": 8,
            "speed": 3
          },
          "repulse": {
            "distance": 200,
            "duration": 0.4
          },
          "push": {
            "particles_nb": 4
          },
          "remove": {
            "particles_nb": 2
          }
        }
      },
      "retina_detect": true
    });
  </script>
</body>

</html>

<!--
        <form method="post" action="">
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
-->