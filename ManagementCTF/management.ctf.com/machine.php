<?php
session_start();
if (!isset($_SESSION['team_name'])) {
    header('Location: 404.php', true, 301);
    exit();
} else

    // Ruta al archivo .env
    $dotenv_path = __DIR__ . '/../.env';

// Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cargar las variables del archivo .env
if (file_exists($dotenv_path)) {
    $lines = file($dotenv_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
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

// Obtener los valores de conexión
$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$db_username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_DATABASE'] ?? 'accounts';

// Crear conexión
$conn = new mysqli($host, $db_username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_REQUEST['id'])) {
    $machine_id = $_REQUEST['id'];
} else {
    die(json_encode(['success' => false, 'message' => 'Invalid machine ID']));
}

// Revisar el sumbissions de la flag e identificar cual ya ha sido completada
$flags_completed = array();
$query = "SELECT * FROM submissions WHERE team_name = ? AND challenge_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $_SESSION['team_name'], $machine_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['is_correct'] == 1) {
            if ($row['type'] == 'user') {
                $flags_completed['user'] = true;
            }
            if ($row['type'] == 'root') {
                $flags_completed['root'] = true;
            }
        }
    }
}
$stmt->close();

// var_dump($flags_completed);
// if(isset($flags_completed['user']) && $_flags_completed['user']==true){}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'get_hints' && isset($_POST['id'])) {
    $machine_id = $_POST['id'];
    $query = "SELECT hint1, hint2, hint3 FROM machines WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $machine_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $hints = $result->fetch_assoc();
        echo json_encode(["status" => "success", "hints" => $hints]);
    } else {
        echo json_encode(["status" => "error", "message" => "No hints found"]);
    }
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['user']) || isset($_POST['root']))) {
    $submited_flags = array();
    $fuser = "";
    $froot = "";

    if (isset($_POST['user'])) {
        $array_user = array();
        $array_user['flag'] = $_POST['user'] ?? "";
        $fuser = $array_user['flag'];
        $array_user['type'] = 'user';
        $array_user['field'] = 'user_flag';
        $submited_flags['user'] = $array_user;
    }
    if (isset($_POST['root'])) {
        $array_root = array();
        $array_root['flag'] = $_POST['root'] ?? "";
        $froot = $array_root['flag'];
        $array_root['type'] = 'root';
        $array_root['field'] = 'root_flag';
        $submited_flags['root'] = $array_root;
    }
    // Validar si la flag es correcta
    $result_flags = ["user_flag" => false, "root_flag" => false];
    foreach ($submited_flags as $flagObject) {
        $flag_field = $flagObject['field'];
        $flag = $flagObject['flag'];
        $type = $flagObject['type'];

        if ($flag !== "") {
            $query = "SELECT $flag_field FROM machines WHERE $flag_field = ? AND id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $flag, $machine_id);
            $stmt->execute();
            $result = $stmt->get_result();
            //aqui quiero ver la consulta que se esta ejecutando



            $isvalid_flag = false;
            if ($result->num_rows > 0) {
                if ('user_flag' == $flag_field) {
                    $result_flags['user_flag'] = true;
                }
                if ('root_flag' == $flag_field) {
                    $result_flags['root_flag'] = true;
                }
                $isvalid_flag = true;
            }

            // Revisar si existe ya una submission con la flag que is_correct = 1 del equipo $_SESSSION['team_name']
            $query = "SELECT * FROM submissions WHERE team_name = ? AND submitted_flag = ? AND is_correct = 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $_SESSION['team_name'], $flag);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                die(json_encode(["status" => "error", "message" => "Flag ya enviada anteriormente"]));
            }

            // Insertar en submissions 
            $submision_type = 'Machine';
            $submission_time = date('Y-m-d H:i:s');
            $insertQuery = "INSERT INTO submissions (team_name,challenge_type,type,challenge_id,submitted_flag,is_correct,submission_time) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("sssssis", $_SESSION['team_name'], $submision_type, $type, $machine_id, $flag, $isvalid_flag, $submission_time);

            if ($insertStmt->execute()) {
                //echo "New record created successfully";
            } else {
                die(json_encode(["status" => "error", "message" => "No se pudo validar la consulta"]));
            }
            $insertStmt->close();
        }
    }
    if ($result_flags['user_flag'] == true && $result_flags['root_flag'] == true) {
        die(json_encode(["status" => "success", "message" => "Flags correctas"]));
    }
    if ($fuser == "" && $froot !== "") {
        if ($result_flags['root_flag'] == true) {
            die(json_encode(["status" => "success", "message" => "Flag correcta de root"]));
        } else {
            die(json_encode(["status" => "error", "message" => "Flag incorrecta de root"]));
        }
    }
    if ($froot == "" && $fuser !== "") {
        if ($result_flags['user_flag'] == true) {
            die(json_encode(["status" => "success", "message" => "Flag correcta de user"]));
        } else {
            die(json_encode(["status" => "error", "message" => "Flag incorrecta de user"]));
        }
    } else {
        die(json_encode(["status" => "error", "message" => "Flags incorrectas"]));
    }
}

// Obtener el número de solvers
$query = "SELECT COUNT(DISTINCT team_name) as solvers FROM submissions WHERE challenge_id = ? AND is_correct = 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $machine_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $solvers = $result->fetch_assoc();
}
$stmt->close();
// var_dump($solvers);

// Obtener información de la máquina de manera segura
$machine = $conn->prepare("SELECT * FROM machines WHERE id = ?");
$machine->bind_param("i", $machine_id);
$machine->execute();
$machine_result = $machine->get_result();
$machine_data = $machine_result->fetch_assoc();
$machine->close();
// Condición para verificar si la máquina existe
if (!$machine_data) {
    header('Location: 404.php', true, 301);
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>TramuntHack CTF</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link rel="stylesheet" href="css/bootstrap4-neon-glow.min.css">


    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel='stylesheet' href='//cdn.jsdelivr.net/font-hack/2.020/css/hack.min.css'>
    <script src="js/chart.js"></script>
    <link rel="stylesheet" href="css/main.css">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
</head>

<body>
    <?php include_once 'includes/menu.php'; ?>

    <div class="jumbotron bg-transparent mb-0 pt-0 radius-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-12  text-center">
                    <h1 class="display-1 bold color_white content__title"><?php echo strtoupper($machine_data['name']); ?><span class="vim-caret">&nbsp;</span></h1>
                    <p class="text-grey text-spacey hackerFont lead mb-5">
                        <?php echo $machine_data['description']; ?>
                    </p>
                </div>
            </div>
            <div class="row hackerFont">
            </div>
            <h4>Machine info</h4>
            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="card border-primary mb-3 text-center">
                            <div class="card-body">

                                <h4>IP: <?php echo $machine_data['ip_address']; ?> <img src="./images/<?php echo $machine_data['os']; ?>.png" height="50" width="50" /></sub></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card border-primary mb-3 text-center">
                            <div class="card-body">

                                <h4>Base Points: <?php echo $machine_data['points']; ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card border-primary mb-3 text-center">
                            <div class="card-body machine_page justify-content-center" style="display: inline-flex;">
                                <h6 class="solvers">Solvers: <span class="solver_num">76</span> &nbsp;<span class="color_danger">Difficulty:</span></h6>
                                <div class="pl-2"><canvas style="width:80px;height:15px" id="machine_id_1_chart"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="card border-primary mb-3 text-center">
                            <div class="card-body">
                                <canvas id="machineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card border-primary mb-3 text-center">
                        <div class="card-body">
                            <?php if (!isset($flags_completed['user']) || !isset($flags_completed['root'])) { ?>
                                <h4>Rate the challenge</h4>
                                <div class="row input-group mt-3 justify-content-center">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customRadio1_p1" name="customRadio_p1" class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio1_p1" checked=false>Very Easy</label>
                                    </div>
                                    <div class="ht-tm-element custom-control custom-radio">
                                        <input type="radio" id="customRadio2_p1" name="customRadio_p1" class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio2_p1">Easy</label>
                                    </div>
                                    <div class="ht-tm-element custom-control custom-radio">
                                        <input type="radio" id="customRadio3_p1" name="customRadio_p1" class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio3_p1">Medium</label>
                                    </div>
                                    <div class="ht-tm-element custom-control custom-radio">
                                        <input type="radio" id="customRadio4_p1" name="customRadio_p1" class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio4_p1">Hard</label>
                                    </div>
                                    <div class="ht-tm-element custom-control custom-radio">
                                        <input type="radio" id="customRadio5_p1" name="customRadio_p1" class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio5_p1">Very Hard</label>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row justify-content-center pb-3">
                                <div class="input-group mt-3 col-md-6">
                                    <?php if (!isset($flags_completed['user'])) { ?>
                                        <input id="user_flag" type="text" class="form-control" placeholder="Enter User Flag" aria-label="Enter Flag" aria-describedby="basic-addon2"><br>
                                    <?php } else { ?>
                                        <input type="text" class="form-control" id="completed1" placeholder="User Flag Completed!" aria-label="Enter Flag" aria-describedby="basic-addon2" disabled><br>
                                    <?php } ?>
                                </div>
                                <div class="input-group mt-3 col-md-6">
                                    <?php if (!isset($flags_completed['root'])) { ?>
                                        <input id="root_flag" type="text" class="form-control" placeholder="Enter Root Flag" aria-label="Enter Flag" aria-describedby="basic-addon2"><br>
                                    <?php } else { ?>
                                        <input type="text" class="form-control" id="completed2" placeholder="Root Flag Completed!" aria-label="Enter Flag" aria-describedby="basic-addon2" disabled><br>
                                    <?php } ?>

                                    <?php if (!isset($flags_completed['user']) || !isset($flags_completed['root'])) { ?>
                                </div>
                                <div class="row input-group mt-3 justify-content-center">
                                    <div class="input-group-append">
                                        <button id="submit_p1" class="btn btn-outline-secondary" type="button"><i class="fas fa-paper-plane"></i> Send!</button>
                                    </div>
                                    <div class="input-group-append">
                                        <button id="hintButton" class="hint-button"><i class="fas fa-lightbulb"></i> Hints</button>
                                    </div>
                                <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="hint" tabindex="-1" role="dialog" aria-labelledby="hint label" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        HINT GOES HERE
                    </div>
                </div>
            </div>
        </div>
        <script>
            // comprovar si document.getElementById("submit_p1") existe
            if (document.getElementById("submit_p1") != null) {
                document.getElementById("submit_p1").addEventListener("click", function() {
                    // Obtener los valores seleccionados de las opciones de dificultad
                    const diff1 = document.getElementById("customRadio1_p1").checked;
                    const diff2 = document.getElementById("customRadio2_p1").checked;
                    const diff3 = document.getElementById("customRadio3_p1").checked;
                    const diff4 = document.getElementById("customRadio4_p1").checked;
                    const diff5 = document.getElementById("customRadio5_p1").checked;

                    // Determinar la dificultad seleccionada
                    const diff = diff1 ? "Very Easy" :
                        diff2 ? "Easy" :
                        diff3 ? "Medium" :
                        diff4 ? "Hard" :
                        diff5 ? "Very Hard" : "Easy";

                    // Obtener el valor de la flag introducida
                    let root_flag = "";
                    let user_flag = "";
                    if (document.getElementById("user_flag") !== null) {
                        user_flag = document.getElementById("user_flag").value ?? "";
                    } else {
                        user_flag = "";
                    }
                    if (document.getElementById("root_flag") !== null) {
                        root_flag = document.getElementById("root_flag").value ?? "";
                    } else {
                        root_flag = "";
                    }

                    if (user_flag == "" && root_flag == "") {
                        alert("Please enter the flag");
                        return;
                    }
                    // Obtener el ID de la máquina desde la URL
                    const machineId = "<?php echo htmlspecialchars($_REQUEST['id'], ENT_QUOTES, 'UTF-8'); ?>";

                    // Configurar el cuerpo de la solicitud
                    const data = new URLSearchParams({
                        user: user_flag,
                        root: root_flag,
                        diff: diff
                    });

                    // Enviar la solicitud usando fetch
                    fetch(`machine.php?id=${machineId}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: data.toString()
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(result => {
                            if (result.status === "success") {
                                Swal.fire({
                                    title: 'SUCCESS!',
                                    text: result.message || "Correct flag!",
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.replace(window.location.pathname + window.location.search + window.location.hash);
                                });
                            } else {
                                Swal.fire({
                                    title: 'FAIL!',
                                    text: result.message || "Incorrect flag!",
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error.message || error);
                            alert("Error al enviar la flag: " + error.message);
                        });
                });
            }

            //Swal2 Replace Style
            // Configuración del observer
            // const observer = new MutationObserver(() => {
            //     const elements = document.querySelectorAll(
            //         '[class*="swal2"]:not(button):not(.swal2-container):not([class*="swal2-x"]):not([class*="swal2-success"]):not([class*="swal2-error"]):not(.swal2-success-circular-line-left):not(.swal2-success-line-tip):not(.swal2-success-line-long):not(.swal2-success-ring):not(.swal2-success-fix):not(.swal2-success-circular-line-right)'
            //     );

            //     elements.forEach(element => {
            //         element.style.backgroundColor = 'gray';
            //     });
            // });

            // // Observar cambios en el body
            // observer.observe(document.body, {
            //     childList: true,
            //     subtree: true
            // });




            var config = {
                type: 'radar',
                data: {
                    labels: [
                        'Enumeration', 'CTF-Like', 'Custom Exploration', 'Real-life', 'CVE'
                    ],
                    datasets: [{
                        label: 'Problem Setter\'s Ratings',
                        backgroundColor: "#ff000054",
                        borderColor: 'red',
                        pointBackgroundColor: 'red',
                        data: [
                            20,
                            70,
                            40,
                            30,
                            100,
                        ]
                    }, ]
                },
                options: {
                    legend: {
                        display: false,
                    },
                    scale: {
                        ticks: {
                            display: false,
                        },
                        gridLines: {
                            color: "#FFF"
                        }
                    }
                }
            };

            window.myRadar = new Chart(document.getElementById('machineChart'), config);
            var matrixOptions = {
                legend: {
                    display: false,
                    position: 'left',
                },
                title: {
                    display: false,
                },
                elements: {
                    line: {
                        tension: 0,
                        borderWidth: 3
                    }
                },
                scale: {
                    pointLabels: {
                        fontColor: ['#00F', '#F00']
                    },
                    display: true,
                    ticks: {
                        display: false,
                        max: 10,
                        min: 0
                    },
                    gridLines: {
                        color: "#37393F"
                    },
                }
            };
            var ctx = document.getElementById('machine_id_1_chart').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData = {
                    labels: ['Easy1', 'Easy2', 'Medium3', 'Hard4', 'Hard5'],
                    datasets: [{
                        label: 'Dataset 1',
                        backgroundColor: [
                            '#17b06b', '#17b06b', '#ffce56', '#ffffff82', '#ffffff82'
                        ],
                        borderColor: [
                            '#17b06b', '#17b06b', '#ffce56', '#ffffff82', '#ffffff82'
                        ],
                        borderWidth: 1,
                        data: [11, 2, 13, 41, 15, 0]
                    }]

                },
                options: {
                    tooltips: {
                        enabled: false,
                    },
                    responsive: false,
                    legend: {
                        display: false,
                    },
                    scales: {
                        yAxes: [{
                            display: false
                        }],
                        xAxes: [{
                            display: false
                        }]
                    }
                }
            });
        </script>

        <script>
            if (document.getElementById('hintButton')) {
                document.getElementById('hintButton').addEventListener('click', () => {
                    const machineId = <?php echo htmlspecialchars($_REQUEST['id'], ENT_QUOTES, 'UTF-8'); ?>;

                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "machine.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    // Datos enviados en formato x-www-form-urlencoded
                    const params = `action=get_hints&id=${encodeURIComponent(machineId)}`;

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === "success") {
                                const {
                                    hint1,
                                    hint2,
                                    hint3
                                } = response.hints;

                                // Mostrar el popup con los hints usando SweetAlert2
                                Swal.fire({
                                    title: 'HINTS',
                                    html: `
                        <table class="hints-table">
                            <tr>
                                <td>Hint 1:</td>
                                <td><button class="hint-button" onclick="showHint('${hint1}')"><i class="fa fa-eye-slash" aria-hidden="true"></i></button></td>
                            </tr>
                            <tr>
                                <td>Hint 2:</td>
                                <td><button class="hint-button" onclick="showHint('${hint2}')"><i class="fa fa-eye-slash" aria-hidden="true"></i></button></td>
                            </tr>
                            <tr>
                                <td>Hint 3:</td>
                                <td><button class="hint-button" onclick="showHint('${hint3}')"><i class="fa fa-eye-slash" aria-hidden="true"></i></button></td>
                            </tr>
                        </table>
                    `,
                                    showCloseButton: true,
                                    showCancelButton: false,
                                    focusConfirm: false
                                });
                            } else {
                                Swal.fire('ERROR', response.message || 'No se pudieron cargar los hints', 'error');
                            }
                        } else {
                            Swal.fire('ERROR', 'Hubo un problema al cargar los hints.', 'error');
                        }
                    };

                    // Manejar errores de red
                    xhr.onerror = function() {
                        Swal.fire('ERROR', 'Error de red al intentar cargar los hints.', 'error');
                    };

                    // Enviar la solicitud
                    xhr.send(params);
                });
            }

            // Función para mostrar el hint dentro de un SweetAlert
            function showHint(hint) {
                Swal.fire({
                    title: 'HINT',
                    text: hint,
                    icon: 'info',
                    confirmButtonText: 'Cerrar'
                });
            }
        </script>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>