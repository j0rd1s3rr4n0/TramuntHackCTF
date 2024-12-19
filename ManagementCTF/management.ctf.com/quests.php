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
$db_username = $_ENV['DB_USERNAME'] ?? 'user';
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_DATABASE'] ?? 'accounts';
// Crear conexión
$conn = new mysqli($host, $db_username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'get_hints' && isset($_POST['id'])) {
    $challenge_id = $_POST['id'];
    $query = "SELECT hint FROM challenges WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $challenge_id);
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

// if $_POST['challenge_id'] and $_POST['flag'] are set and optional difficulty
if (isset($_POST['challenge_id']) && isset($_POST['flag']) && isset($_POST['type'])) {

    // If exist difficulty set it to post on db
    if (isset($_POST['difficulty'])) {
        $difficulty = $_POST['difficulty'];
    } else {
        $difficulty = '';
    }

    // Get the challenge id and flag eviting sql injection or xss or any other attack
    $challenge_id = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['challenge_id']));
    $flag = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['flag']));
    $type = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['type']));

    // if flag or challenge_id is empty
    if (empty($flag) || empty($challenge_id)) {
        die(json_encode(['success' => false]));
    }

    // Look in submissions table finding if the flag is already submitted with value true 
    $query = "SELECT * FROM submissions WHERE challenge_type = 'Challenge' AND type = '$type' AND challenge_id = $challenge_id AND submitted_flag = '$flag' AND team_name = '" . $_SESSION['team_name'] . "' AND is_correct = 1";

    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        die(json_encode(['success' => false, 'message' => 'Flag already submitted']));
    }

    $challenge = $conn->query("SELECT * FROM challenges WHERE id = $challenge_id")->fetch_assoc();
    $submission_time = date('Y-m-d H:i:s');
    if ($challenge['flag'] === $flag) {
        $conn->query("INSERT INTO submissions (team_name, challenge_type, challenge_id, submitted_flag, is_correct, type, submission_time) VALUES ('" . $_SESSION['team_name'] . "', 'Challenge', $challenge_id, '$flag', 1, '$type', '$submission_time')");
        die(json_encode(['success' => true, 'points' => $challenge['points']]));
    }

    $conn->query("INSERT INTO submissions (team_name, challenge_type, challenge_id, submitted_flag, is_correct, type, submission_time) VALUES ('" . $_SESSION['team_name'] . "', 'Challenge', $challenge_id, '$flag', 0, '$type', '$submission_time')");
    die(json_encode(['success' => false]));
}

function checkSubmission($challenge_id)
{
    // Conexión a la base de datos
    $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $db_username = $_ENV['DB_USERNAME'] ?? 'user';
    $password = $_ENV['DB_PASSWORD'];
    $database = $_ENV['DB_DATABASE'] ?? 'accounts';
    // Crear conexión
    //$conn = new mysqli($host, $db_username, $password, $database);
    $pdo = new PDO("mysql:host=$host;dbname=$database", $db_username, $password);

    // Consulta para verificar si existe una submission válida
    $query = "SELECT * FROM submissions WHERE challenge_type = 'Challenge' AND challenge_id = :challenge_id AND team_name = :team_name AND is_correct = 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':challenge_id' => $challenge_id,
        ':team_name' => $_SESSION['team_name']
    ]);

    // Devuelve true si ya existe una submission exitosa
    return $stmt->fetchColumn() > 0;
}

function checkSubmissionMachine($machine_id)
{
    // Conexión a la base de datos
    $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $db_username = $_ENV['DB_USERNAME'] ?? 'user';
    $password = $_ENV['DB_PASSWORD'];
    $database = $_ENV['DB_DATABASE'] ?? 'accounts';
    // Crear conexión
    //$conn = new mysqli($host, $db_username, $password, $database);
    $pdo = new PDO("mysql:host=$host;dbname=$database", $db_username, $password);

    // Consulta para verificar si existe una submission válida
    $query = "SELECT * FROM submissions WHERE challenge_type = 'Machine' AND challenge_id = :challenge_id AND team_name = :team_name AND is_correct = 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':challenge_id' => $machine_id,
        ':team_name' => $_SESSION['team_name']
    ]);

    // Devuelve true si ya existe una submission exitosa
    return $stmt->fetchColumn() > 0;
}



// Consultar desafíos
$challenges = $conn->query("SELECT * FROM challenges WHERE category != 'EasterEgg'");
$machines = $conn->query("SELECT * FROM machines");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>TramuntHack CTF</title>


    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/bootstrap4-neon-glow.min.css">


    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel='stylesheet' href='//cdn.jsdelivr.net/font-hack/2.020/css/hack.min.css'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <link rel="stylesheet" href="css/main.css">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
</head>

<body>
    <?php include_once 'includes/menu.php'; ?>

    <div class="jumbotron bg-transparent mb-0 pt-0 radius-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-12  text-center">
                    <h1 class="display-1 bold color_white content__title">QUESTS<span class="vim-caret">&nbsp;</span></h1>
                    <p class="text-grey text-spacey hackerFont lead mb-5">
                        Its time to show the world what you can do!
                    </p>
                </div>
            </div>
            <div class="row hackerFont">
                <div class="col-md-12">
                    <h4>Challenges</h4>
                </div>

                <?php foreach ($challenges as $challenge): ?>
                    <!-- Challenge Card -->
                    <div class="col-md-4 mb-3">
                        <?php $submission_done = checkSubmission($challenge['id']); ?>
                        <div class="card <?php if ($submission_done) {
                                                echo 'solved';
                                            } ?> category_<?php echo $challenge['category']; ?>">
                            <div class="card-header" data-target="#problem_id_<?php echo $challenge['id']; ?>" data-toggle="collapse" aria-expanded="false" aria-controls="problem_id_2">
                                <?php echo $challenge['title']; ?> <span class="badge align-self-end <?php if ($submission_done) {
                                                                                                            echo 'solved';
                                                                                                        } ?> "><?php if ($submission_done) {
                                                                                                                    echo '<span class="fa fa-check"></span> Solved';
                                                                                                                } else {
                                                                                                                    echo $challenge['points'] . "  points";
                                                                                                                } ?></span>
                            </div>
                            <div id="problem_id_<?php echo $challenge['id']; ?>" class="collapse card-body">
                                <blockquote class="card-blockquote">
                                    <div style="display: flex;">
                                        <h6 class="solvers">Solvers: <span class="solver_num">-</span> &nbsp;<span class="color_danger">Difficulty:</span></h6>
                                        <div class="pl-2">
                                            <canvas style="width:80pt;height:35pt" id="problem_id_<?php echo $challenge['id']; ?>_chart"></canvas>
                                        </div>
                                    </div>
                                    <p>
                                        <?php echo $challenge['description']; ?>
                                        <br><br>
                                        <sub>Can you find the password? Enter the password as flag in the following form: CTF{passwordhere}</sub>
                                    </p>
                                    <?php
                                    if (!$submission_done) {
                                    ?>
                                        <a target="_blank" href="<?php echo $challenge['download']; ?>" class="btn btn-outline-secondary btn-shadow" download>
                                            <span class="fa fa-download mr-2"></span>
                                            Download
                                        </a>

                                        <a   onclick="hintButtonClick('<?php echo $challenge['id']; ?>');" class="btn btn-outline-secondary btn-shadow">
                                            <span class="far fa-lightbulb mr-2"></span>
                                            Hint
                                        </a>

                                        <div class="input-group mt-3">
                                            <!-- <div class="custom-control custom-radio"> -->
                                            <div class="ht-tm-element custom-control custom-radio">
                                                <input type="radio" id="customRadio1_p2_m<?php echo $challenge['id']; ?>" name="customRadio_p2" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio1_p2_m<?php echo $challenge['id']; ?>" aria-required="">Very Easy</label>
                                            </div>
                                            <div class="ht-tm-element custom-control custom-radio">
                                                <input type="radio" id="customRadio2_p2_m<?php echo $challenge['id']; ?>" name="customRadio_p2" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio2_p2_m<?php echo $challenge['id']; ?>">Easy</label>
                                            </div>
                                            <div class="ht-tm-element custom-control custom-radio">
                                                <input type="radio" id="customRadio3_p2_m<?php echo $challenge['id']; ?>" name="customRadio_p2" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio3_p2_m<?php echo $challenge['id']; ?>">Medium</label>
                                            </div>
                                            <div class="ht-tm-element custom-control custom-radio">
                                                <input type="radio" id="customRadio4_p2_m<?php echo $challenge['id']; ?>" name="customRadio_p2" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio4_p2_m<?php echo $challenge['id']; ?>">Hard</label>
                                            </div>
                                            <div class="ht-tm-element custom-control custom-radio">
                                                <input type="radio" id="customRadio5_p2_m<?php echo $challenge['id']; ?>" name="customRadio_p2" class="custom-control-input">
                                                <label class="custom-control-label" for="customRadio5_p2_m<?php echo $challenge['id']; ?>">Very Hard</label>
                                            </div>
                                        </div>
                                        <div class="input-group mt-3">
                                            <input type="text" class="form-control" placeholder="Enter Flag" aria-label="Enter Flag" aria-describedby="basic-addon2">
                                            <input type="hidden" id="challenge_type_m<?php echo $challenge['id']; ?>" value="<?php echo $challenge['category']; ?>">
                                            <div class="input-group-append">
                                                <button id="submit_p2_m<?php echo $challenge['id']; ?>" class="btn btn-outline-secondary" type="button">
                                                    Send!
                                                </button>
                                            </div>
                                        </div>

                                        <script>
                                            document.querySelector('button#submit_p2_m<?php echo $challenge['id']; ?>').addEventListener('click', function() {
                                                // TODO: Add the logic to get the difficulty
                                                var diff1 = document.getElementById("customRadio1_p2_m<?php echo $challenge['id']; ?>").checked;
                                                var diff2 = document.getElementById("customRadio2_p2_m<?php echo $challenge['id']; ?>").checked;
                                                var diff3 = document.getElementById("customRadio3_p2_m<?php echo $challenge['id']; ?>").checked;
                                                var diff4 = document.getElementById("customRadio4_p2_m<?php echo $challenge['id']; ?>").checked;
                                                var diff5 = document.getElementById("customRadio5_p2_m<?php echo $challenge['id']; ?>").checked;

                                                // Determinar la dificultad seleccionada
                                                var difficulty = diff1 ? "Very Easy" :
                                                    diff2 ? "Easy" :
                                                    diff3 ? "Medium" :
                                                    diff4 ? "Hard" :
                                                    diff5 ? "Very Hard" : "Easy";

                                                var flag = document.querySelector('input[aria-label="Enter Flag"]').value;
                                                if (flag === '') {
                                                    Swal.fire({
                                                        title: 'OH OH...',
                                                        text: "Flag Not Found - Please enter a flag",
                                                        icon: 'warning',
                                                        confirmButtonText: 'OK'
                                                    });
                                                    return;
                                                }
                                                var type = document.getElementById("challenge_type_m<?php echo $challenge['id']; ?>").value;
                                                if (type === '') {
                                                    Swal.fire({
                                                        title: 'OH OH...',
                                                        text: "Type Not Found - Please refresh the page",
                                                        icon: 'warning',
                                                        confirmButtonText: 'OK'
                                                    });
                                                    return;
                                                }
                                                var xhr = new XMLHttpRequest();
                                                xhr.open('POST', 'quests.php', true);
                                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                                xhr.onreadystatechange = function() {
                                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                                        var response = JSON.parse(xhr.responseText);
                                                        if (response.success) {
                                                            Swal.fire({
                                                                title: 'SUCCESS!',
                                                                text: "Correct flag!",
                                                                icon: 'success',
                                                                confirmButtonText: 'OK'
                                                            }).then(() => {
                                                                window.location.replace(window.location.pathname + window.location.search + window.location.hash);
                                                            });
                                                        } else {
                                                            Swal.fire({
                                                                title: 'FAIL!',
                                                                text: "Incorrect flag! Try again",
                                                                icon: 'error',
                                                                confirmButtonText: 'OK'
                                                            });
                                                        }
                                                    }
                                                };
                                                xhr.send('challenge_id=<?php echo $challenge['id']; ?>&flag=' + flag + '&type=' + type + '&difficulty=' + difficulty);
                                            });
                                        </script>
                                    <?php } else { ?>
                                        <div class="input-group mt-3">
                                            <div class="input-group-append">
                                                <button class="solved btn btn-outline-secondary" type="button" disabled>
                                                    <span class="fa fa-check"></span> Challenge Solved
                                                </button>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Challenge Card -->

            <div class="row hackerFont justify-content-center mt-5">
                <div class="col-md-12">
                    <h4>Machines</h4>
                </div>
                <?php foreach ($machines as $machine): ?>
                    <?php $submission_done = checkSubmissionMachine($machine['id']); ?>
                    <!-- Machine Card -->
                    <div class="col-md-12 mb-3">
                        <div class="<?php if ($submission_done) {
                                        echo 'solved ';
                                    } ?>card category_machine">
                            <a href="machine.php?id=<?php echo $machine['id']; ?>" class="color_white">
                                <div class="card-header">
                                    <?php echo $machine['name']; ?>
                                    <span class="ml-4 badge align-self-end <?php if ($submission_done) {
                                                                                echo 'solved';
                                                                            } ?> "><?php if ($submission_done) {
                                                                                        echo '<span class="fa fa-check"></span> Solved';
                                                                                    } else {
                                                                                        echo $machine['points'] . "  points";
                                                                                    } ?></span>
                                    <div class="pl-4 machine" style="display: inline-flex;">
                                        <?php echo $machine['ip_address']; ?>
                                        <h6 class=" pl-4 pt-1 solvers">Solvers: <span class="solver_num">-</span> &nbsp;<span class="color_danger">Difficulty:</span></h6>
                                        <div class="pl-2"><canvas style="width:80pt;height:45pt!important" id="problem_id_<?php echo $machine['id'] * 8; ?>_chart"></canvas></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <!-- Machine Card -->
                <?php endforeach; ?>
            </div>

            <div class="row hackerFont justify-content-center mt-5">
                <div class="col-md-12">
                    Chart Difficulties:
                    <span style="color:#14ffcb">Very Easy,</span>
                    <span style="color:#17b06b">Easy,</span>
                    <span style="color:#f9751594">Medium,</span>
                    <span style="color:#ffce56">Hard,</span>
                    <span style="color:#ff0000ad">Very Hard,</span>
                    <span style="color:#9966FF94">Insane</span>
                    <br><br>Challenge Types:
                    <span class="p-1" style="background-color:#b94c5c">Web</span>
                    <span class="p-1" style="background-color:#17b06b94">Reversing</span>
                    <span class="p-1" style="background-color:#f9751594">Steganography</span>
                    <span class="p-1" style="background-color:#36a2eb94">Pwning</span>
                    <span class="p-1" style="background-color:#9966FF94">Cryptography</span>
                    <span class="p-1" style="background-color:#ff230094">Misc</span>
                    <span class="p-1" style="background-color:#ffce5694">Other</span>
                </div>
            </div>
        </div>
        <div class="modal fade" id="hint" tabindex="-1" role="dialog" aria-labelledby="hint label" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body" id="hinttext">
                        HINT GOES HERE
                    </div>
                </div>
            </div>
        </div>

        <script>
                function hintButtonClick(challenge_id){

                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", "quests.php", true);
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                    // Datos enviados en formato x-www-form-urlencoded
                    const params = `action=get_hints&id=${encodeURIComponent(challenge_id)}`;

                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === "success") {
                                const {
                                    hint,
                                } = response.hints;

                                // Mostrar el popup con los hints usando SweetAlert2
                                Swal.fire({
                                    title: 'SHOW HINT',
                                    html: `
                        <table class="hints-table">
                            <tr>
                                <td>Hint:</td>
                                <td><button class="hint-button" onclick="showHint('${hint}')"><i class="fa fa-eye-slash" aria-hidden="true"></i></button></td>
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
                };

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

        <script>
            <?php
            //TODO : Add the data from the database

            ?>
            var dataset = [

                [4, 5, 3.5, 2, 1, 0, 0],
                [2, 4, 5, 2, 1, 0.1, 0],
                [0.5, 1, 2, 4, 5, 0.6, 0],
                [4, 5, 3.5, 2, 1, 0, 0],
                [2, 4, 5, 2, 1, 0.1, 0],
                [0.5, 1, 2, 4, 5, 0.6, 0],
                [5, 4, 3, 2, 1, 0.5, 0],
                [1, 2, 4, 3.5, 2.5, 0.5, 0],
                [0, 0.5, 1, 3.5, 5, 3, 0],
                [1, 2, 3, 4, 5, 6, 0],
                [1, 2, 3, 4, 5, 6, 0],
                [1, 2, 3, 4, 5, 6, 0],
                [1, 2, 3, 4, 5, 6, 0],
                [1, 2, 3, 4, 5, 6, 0]
            ]

            function getBarChartData(i) {
                return barChartData = {
                    labels: ['VeryEasy', 'Easey', 'Medium', 'Hard', 'VeryHard', 'Insane'],
                    datasets: [{
                        label: 'Dataset 1',
                        backgroundColor: [
                            '#14ffcb', '#17b06b', '#f9751594', '#ffce56', '#ff0000ad', '#9966FF94'
                        ],
                        borderColor: [
                            '#b94c5c', '#17b06b94', '#f9751594', '#36a2eb94', '#9966FF94', '#ffce5694'
                        ],
                        borderWidth: 1,
                        data: dataset[i - 1]
                    }]

                };
            }
            /*
            window.onload = function() {
                var numcharts = 20;
                for (let i = 1; i <= numcharts; i++) {
                    var ctx = document.getElementById('problem_id_' + i + '_chart').getContext('2d');
                    window.myBar = new Chart(ctx, {
                        type: 'bar',
                        data: getBarChartData(i),
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
                    myBar.canvas.parentNode.style.width = '80pt';
                    myBar.canvas.parentNode.style.height = '45pt';
                }
            };*/
        </script>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>
<?php $conn->close(); ?>