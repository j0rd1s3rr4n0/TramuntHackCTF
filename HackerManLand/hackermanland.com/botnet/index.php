<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: /login.php');
    exit;
}

// Ejecutar el comando Nmap
$command = "sudo nmap 172.17.0.1/24 -O -n 2>&1";
$output = [];
exec($command, $output);

// Debug: Mostrar el output
// echo "<pre>";
// print_r($output);
// echo "</pre>";

// Procesar la salida
$hosts = [];
$currentHost = null;

foreach ($output as $line) {
    if (preg_match('/^Nmap scan report for (.+)$/', $line, $matches)) {
        // Si ya había un host procesándose, guardarlo antes de empezar otro
        if ($currentHost) {
            $hosts[] = $currentHost;
        }

        // Iniciar un nuevo host
        $currentHost = [
            'ip' => $matches[1],
            'os' => 'unknown',
            'ports' => [],
        ];
    } elseif ($currentHost && preg_match('/^Running: (.+)$/', $line, $matches)) {
        // Detectar sistema operativo
        $currentHost['os'] = $matches[1];
    } elseif ($currentHost && preg_match('/^(\d+\/tcp)\s+open\s+(.+)$/', $line, $matches)) {
        // Detectar puertos abiertos
        $currentHost['ports'][] = $matches[1] . " (" . $matches[2] . ")";
    }
}

// Asegurarse de agregar el último host si quedó pendiente
if ($currentHost) {
    $hosts[] = $currentHost;
}

// Verificar si el archivo execute.php está disponible en cada host
foreach ($hosts as &$host) {
    $url = "http://" . $host['ip'] . "/execute.php";
    $headers = @get_headers($url);
    $host['has_execute'] = $headers && strpos($headers[0], '200') !== false;
}

// Mostrar el resultado final
// echo "<pre>";
//     print_r($hosts);
// echo "</pre>";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Red Interactivo</title>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
        body {
            background-color: #0d0d0d;
            color: #00ff00;
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #00cc00;
            margin-top: 20px;
        }

        svg {
            display: block;
            margin: 0 auto;
            background-color: #001a00;
            border: 1px solid #00ff00;
        }

        .node text {
            fill: #00ff00;
            font-size: 12px;
        }

        .node circle {
            fill: #004d00;
            stroke: #00ff00;
            stroke-width: 2px;
        }

        .node circle:hover {
            fill: #00cc00;
        }

        .details {
            margin: 20px auto;
            width: 80%;
            max-width: 600px;
            padding: 20px;
            border: 1px solid #00ff00;
            background-color: #001a00;
        }

        .details h2 {
            margin-top: 0;
        }

        .details p {
            margin: 5px 0;
        }

        .details ul {
            margin: 0;
            padding-left: 20px;
        }

        .details ul li {
            list-style: none;
        }
    </style>
</head>
<body>
    <h1>Mapa de Red Interactivo</h1>
    <svg id="network" width="800" height="600"></svg>
    <div id="details" class="details" style="display: none;">
        <h2>Información del Host</h2>
        <p><strong>IP:</strong> <span id="ip"></span></p>
        <p><strong>Sistema Operativo:</strong> <span id="os"></span></p>
        <p><strong>Puertos Abiertos:</strong></p>
        <ul id="ports"></ul>
        <p id="execute"></p>
    </div>

    <script>
        // Datos PHP convertidos a JavaScript
        const hosts = <?= json_encode($hosts) ?>;

        // Configuración del gráfico
        const width = 800;
        const height = 600;

        const svg = d3.select("#network");
        const simulation = d3.forceSimulation()
            .force("link", d3.forceLink().id(d => d.ip))
            .force("charge", d3.forceManyBody().strength(-200))
            .force("center", d3.forceCenter(width / 2, height / 2));

        // Convertir hosts a nodos y links
        const nodes = hosts.map(host => ({
            ...host,
            id: host.ip
        }));
        const links = nodes.map((node, i) => ({
            source: nodes[0].ip, // Conectar todos al primer nodo (puedes cambiar esto)
            target: node.ip
        }));

        // Crear links
        const link = svg.append("g")
            .attr("stroke", "#00ff00")
            .attr("stroke-width", 1.5)
            .selectAll("line")
            .data(links)
            .enter()
            .append("line");

        // Crear nodos
        const node = svg.append("g")
            .attr("stroke", "#003300")
            .attr("stroke-width", 1.5)
            .selectAll("circle")
            .data(nodes)
            .enter()
            .append("g")
            .attr("class", "node")
            .on("click", showDetails);

        node.append("circle")
            .attr("r", 10);

        node.append("text")
            .text(d => d.ip)
            .attr("x", 12)
            .attr("y", 3);

        // Simulación de fuerzas
        simulation
            .nodes(nodes)
            .on("tick", () => {
                link
                    .attr("x1", d => d.source.x)
                    .attr("y1", d => d.source.y)
                    .attr("x2", d => d.target.x)
                    .attr("y2", d => d.target.y);

                node
                    .attr("transform", d => `translate(${d.x},${d.y})`);
            });

        simulation.force("link")
            .links(links);

        // Mostrar detalles al hacer clic en un nodo
        function showDetails(event, d) {
            const details = document.getElementById("details");
            document.getElementById("ip").textContent = d.ip;
            document.getElementById("os").textContent = d.os;

            const portsList = document.getElementById("ports");
            portsList.innerHTML = "";
            d.ports.forEach(port => {
                const li = document.createElement("li");
                li.textContent = port;
                portsList.appendChild(li);
            });

            const execute = document.getElementById("execute");
            if (d.has_execute) {
                execute.innerHTML = `<a href="c2.php?ip=${d.ip}" target="_blank">Abrir execute.php</a>`;
            } else {
                execute.textContent = "execute.php no disponible";
            }

            details.style.display = "block";
        }
    </script>
</body>
</html>
