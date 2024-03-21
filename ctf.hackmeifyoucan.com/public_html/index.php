<?php
session_start();
header("Content-Type: text/html;charset=utf-8");

// Establecer valores iniciales
if (!isset($_SESSION['reto'])) {
    $_SESSION['reto'] = 0;
}

if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = null;
}

$username = $_SESSION['username'];
$reto = $_SESSION['reto'];

$titulo = "";
$descripcion = "";

$error_message = ""; // Variable para almacenar mensajes de error

// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos (debes llenar los detalles de conexión)
    $conexion = new mysqli("127.0.0.1", "web", "P@ssw0rd!", "web");

    // Verifica la conexión
    if ($conexion->connect_error) {
        die("Error de conexión a la base de datos: " . $conexion->connect_error);
    }

    // Obtiene los valores del formulario
    $reto = $_SESSION['reto'];
    $flag = isset($_POST["flag"]) ? $_POST["flag"] : null;

    // Verifica si es el primer reto y obtiene el nombre de usuario
    if ($reto == 0) {
        $username = $_POST["username"];

        // Verifica si el nombre de usuario ya existe
        $query_check_username = "SELECT * FROM usuarios WHERE username = '$username'";
        $resultado_check_username = $conexion->query($query_check_username);
        if ($resultado_check_username->num_rows > 0) {
            $error_message = "El nombre de usuario '$username' ya existe. Por favor, elige otro.";
        } else {
            // Inserta el nombre de usuario en la tabla de usuarios
            $query_insert = "INSERT INTO usuarios (username) VALUES ('$username')";
            if (!$conexion->query($query_insert)) {
                $error_message = "Error al insertar el nombre de usuario: " . $conexion->error;
            } else {
                $_SESSION["username"] = $username;
            }
        }
    }

    // Verifica la bandera solamente si no es el primer reto
    if ($reto > 0) {
        // Verifica el reto actual y la bandera
        $query = "SELECT * FROM retos WHERE numero = $reto AND flag = '$flag'";
        $resultado = $conexion->query($query);
        if ($resultado->num_rows == 0) {
            // La bandera es incorrecta, muestra un mensaje de error
            $error_message = "La bandera es incorrecta. Por favor, intenta de nuevo.";
        }
    }

    // Si hay un mensaje de error, se mostraró dentro del formulario
    // en lugar de utilizar "echo" y "exit"
    if (!empty($error_message)) {
        $error_message = "<label class='error'>$error_message</label>";
    } else {
        // Avanza al siguiente reto
        $_SESSION["reto"] = $reto + 1;
        echo "óFelicitaciones! Has completado el reto $reto.";

        // Obtener información del siguiente reto
        $siguienteReto = $reto + 1;
        $query_siguiente = "SELECT * FROM retos WHERE numero = $siguienteReto";
        $resultado_siguiente = $conexion->query($query_siguiente);

        if ($resultado_siguiente->num_rows > 0) {
            $row = $resultado_siguiente->fetch_assoc();
            $titulo = $row["titulo"];
            $descripcion = $row["descripcion"];
        } else {
            echo "<br>No hay mós retos disponibles.";
        }
        $reto = $_SESSION["reto"];

        // Verifica si es el óltimo reto y redirige a google.com si es asó
        $query_total_retos = "SELECT COUNT(*) as total_retos FROM retos";
        $resultado_total_retos = $conexion->query($query_total_retos);
        $total_retos = $resultado_total_retos->fetch_assoc()["total_retos"];

        if ($reto >= $total_retos) {
            // Es el óltimo reto, redirige a Certificado
            header("Location: https://forms.gle/LLh1rcPYpujJqTo6A");
            exit(); // óImportante! Termina el script para evitar que se ejecute mós código
        }
    }

    // Cierra la conexión a la base de datos
    $conexion->close();
} else {
    // Si no se ha enviado un formulario (no es una solicitud POST), obtener el tótulo y la descripción del reto actual
    $conexion = new mysqli("127.0.0.1", "web", "P@ssw0rd!", "web");
    if ($conexion->connect_error) {
        die("Error de conexión a la base de datos: " . $conexion->connect_error);
    }

    $query_actual = "SELECT * FROM retos WHERE numero = $reto";
    $resultado_actual = $conexion->query($query_actual);
    if ($resultado_actual->num_rows > 0) {
        $row = $resultado_actual->fetch_assoc();
        $titulo = $row["titulo"];
        $descripcion = $row["descripcion"];
    }

    $conexion->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=no,user-scalable=0"/>
    <style type="text/css">
        *{padding:0;margin:0}
        body{font-family:Arial;font-size:14px;line-height:20px;color:#fff;background:#000}
        .a,.a:visited{color:#fff;text-decoration:underline}
        .btn,a.btn,a.btn:visited{text-decoration:none;color:#fff;display:inline-block;cursor:pointer;border:1px solid #c1c1c1;border-bottom:2px solid #c1c1c1;padding:5px;border-radius:2px;transition:250ms;font-size:14px}
        .btn:hover{box-shadow:0 0 12px 0 rgba(255,255,255,.5);background:rgba(255,255,255,.3)}
        .mainBox{width:500px;display:inline-block;border-radius:20pt;background:rgba(255,255,255,.2);margin-top:100px;padding:50pt;border:3pt solid rgba(255,255,255,.5)}
        @media only screen and (max-width:600px){.mainBox{width:300px;padding:50pt;border-radius:20pt;border:3pt solid rgba(255,255,255,.5)}}
        @media only screen and (max-height:700px){.mainBox{margin-top:50px;padding:50pt;border-radius:20pt;border:3pt solid rgba(255,255,255,.5)}}
        input[type=text]{padding:8px;border:1px solid #ccc;border-radius:4px;box-sizing:border-box;margin-bottom:10px;width:60%}
        input[type=submit]{background-color:#4caf50;color:#fff;padding:10px 20px;border:none;border-radius:4px;cursor:pointer;width:20%}
        label{font-weight:700;font-size:120%}
        a{color:#fff}
        a:visited{color:#ff0}
        a:hover,label.error{color:red}
        input[type=submit]:hover{background-color:#45a049;width:20%}
        h1{padding:25pt}
        form{padding:10pt}
        /* Estilos para el menó de encabezado */
        header{background-color: #333;padding: 10px;z-index:99999;position:relative;}
        footer{background-color: #333;padding: 10px;z-index:99999;position:relative;}
        header ul{list-style: none;padding: 0;text-align: center;}
        header ul li{display: inline;margin-right: 20px;}
        header ul li a{color: #fff;text-decoration: none;font-size: 18px;}
        header ul li a:hover{color: #ccc;}
    </style>
</head>
<body>
<header>
    <ul>
        <li><a href="#" target="_blank">Inicio</a></li>
        <li><a href="http://hackmeifyoucan.com/" target="_blank">CTF WEB</a></li>
        <li><a href="https://github.com/j0rd1s3rr4n0/VulnWeb/" target="_blank">GitHub VulnWeb</a></li>
        <li><a href="http://<?php echo  $_SERVER['SERVER_NAME']; ?>">Reload Page</a></li>
	<li><a href="https://googlethatforyou.com/?q=Que%20es%20el%20Virtual%20Hosting">Virtual Hosting</a></li>
	<li><a href="https://bluegraded.i234.me/drive/d/s/xUSqSHesnKDMuJ7ptyxRJUSGLOG7Uywv/0pWUYisMUmI3wm6zbi95uwqFFSL5r2kp-ObpAHnIkLQs">How to Virtual Hosting</a></li>

<li></li>
<li></li>
<li></li>
        <?php if (isset($_SESSION['username']) && $_SESSION['username']!=""){ 
        echo "<li><a href=\"javascript:alert('Hello, $username');\">Welcome, $username</a></li>";
} ?>
    </ul>
</header>
<div style="position: relative; z-index: 100;">
    <div style="text-align: center; font-family: 'Arial';">
        <div class="mainBox">
            <?php
            if($titulo!=""){
                echo "<h1>".$titulo."</h1>";
            }                if($descripcion!=""){
                echo "<p>".$descripcion."</p>";
            }else{
                echo "<p>Introduce un nombre de usuario</p>";
            }
            ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <?php if ($_SESSION['reto'] == 0) : ?>
                    <!-- Primer reto: Introducir el nombre de usuario -->
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="username" required>
                    <input type="submit" value="Enviar"><br>
                    <input type="hidden" name="reto" value="0">
                    <input type="hidden" name="flag" value="flag{username}">
                <?php else : ?>
                    <!-- Resto de los retos -->
                    <label for="flag">Flag <?php echo $reto; ?>:</label>

                    <input type="text" id="flag" name="flag" required><br><br>
                    <input type="submit" value="Enviar">
                <?php endif; ?><br>
                <sub><?php echo $error_message; ?></sub>
            </form>
            <div style="background-color:red;">
                <?php
		if($_GET['debug'] == 1){
	                print_r($_SESSION);
		}
                ?>
            </div>
        </div>
    </div>
</div>

<!-- <nodes.js embedding> -->
<script type="text/javascript" src="js/nodes.js"></script>
<script type="text/javascript">
    var nodesjs = new NodesJs({
        id: 'nodes',
        width: window.innerWidth,
        height: window.innerHeight,
        particleSize: 2,
        lineSize: 1,
        particleColor: [255, 255, 255, 0.3],
        lineColor: [255, 255, 255],
        backgroundFrom: [10, 25, 100],
        backgroundTo: [25, 50, 150],
        backgroundDuration: 4000,
        nobg: false,
        number: window.hasOwnProperty('orientation') ? 30: 100,
        speed: 20
    });
</script>
<div style="position: absolute; left: 0px; top: 0px; overflow: hidden; width: 100%; height: 100%;">
    <canvas id="nodes"></canvas>
</div>
<!-- </nodes.js embedding> -->
</body>
</html>

