<?php

session_start();


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








// select de todos los usuarios
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
$teams = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teams[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>TramuntHack CTF</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

    <link rel="stylesheet" href="css/bootstrap4-neon-glow.min.css">


    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel='stylesheet' href='//cdn.jsdelivr.net/font-hack/2.020/css/hack.min.css'>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <link rel="stylesheet" href="css/main.css">
</head>

<body class="imgloaded">
    <div class="glitch">
        <div class="glitch__img glitch__img_leaderboard"></div>
        <div class="glitch__img glitch__img_leaderboard"></div>
        <div class="glitch__img glitch__img_leaderboard"></div>
        <div class="glitch__img glitch__img_leaderboard"></div>
        <div class="glitch__img glitch__img_leaderboard"></div>
    </div>
    <div class="navbar-dark text-white">
        <div class="container">
            <?php include_once 'includes/menu.php'; ?>

        </div>
    </div>

    <div class="jumbotron bg-transparent mb-0 pt-3 radius-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <h1 class="display-1 bold color_white content__title text-center"><span class="color_danger">HACKER</span>BOARD<span class="vim-caret">&nbsp;</span></h1>
                    <p class="text-grey lead text-spacey text-center hackerFont">
                        Where the world get's ranked!
                    </p>
                    <div class="row justify-content-center my-5">
                        <div class="col-xl-10">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5  justify-content-center">
                <div class="col-xl-10">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark hackerFont">
                            <tr>
                                <th>#</th>
                                <th>Team Name</th>
                                <th># Challenges Solved</th>
                                <th>Total Time Taken<br><sub>HH:MM:SS</sub></th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador = 1;
                            foreach ($teams as $team):

                                // Call function count challenges
                                // Call function time taken
                                // Call function score
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $contador; ?></th>
                                    <td><?php echo $team['team_name']; ?></td>
                                    <td>
                                        <?php
                                        $submissions_query = "
                                            SELECT COUNT(DISTINCT submitted_flag) as total_submissions 
                                            FROM submissions 
                                            WHERE team_name = '{$team['team_name']}' AND is_correct = 1
                                        ";
                                        $submissions_result = $conn->query($submissions_query);
                                        $submissions_row = $submissions_result->fetch_assoc();
                                        echo $submissions_row['total_submissions'] ?? 0;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $latest_submission_query = "SELECT MAX(submission_time) as latest_submission FROM submissions WHERE team_name = '{$team['team_name']}'";
                                        $latest_submission_result = $conn->query($latest_submission_query);
                                        $latest_submission_row = $latest_submission_result->fetch_assoc();
                                        $latest_submission_time = $latest_submission_row['latest_submission'] ?? $team['created_at'];
                                        $time_taken = strtotime($latest_submission_time) - strtotime($team['created_at']);
                                        echo gmdate("H:i:s", $time_taken);
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        $points_query = "
    SELECT SUM(
        CASE 
            WHEN unique_submissions.challenge_type = 'Challenge' THEN c.points 
            WHEN unique_submissions.challenge_type = 'Machine' THEN m.points 
        END
    ) as total_points 
    FROM (
        SELECT DISTINCT submitted_flag, challenge_id, challenge_type 
        FROM submissions 
        WHERE team_name = '{$team['team_name']}' AND is_correct = 1
    ) as unique_submissions
    LEFT JOIN machines m ON unique_submissions.challenge_id = m.id AND unique_submissions.challenge_type = 'Machine'
    LEFT JOIN challenges c ON unique_submissions.challenge_id = c.id AND unique_submissions.challenge_type = 'Challenge'
";
                                        $points_result = $conn->query($points_query);
                                        $points_row = $points_result->fetch_assoc();
                                        echo $points_row['total_points'] ?? 0;
                                        ?>
                                    </td>
                                </tr>
                            <?php
                                $contador++;

                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
        <?php
        $colors = ['blue','green','red','pink','yellow','purple','orange','white','brown','gray','tomatoe','cyan','magenta','lime','indigo','violet','teal','navy','maroon','olive','aqua','fuchsia','silver','limegreen','skyblue','coral','gold','crimson','plum','khaki','salmon','turquoise','tan','lavender','orchid','peru','rosybrown','slateblue','steelblue','thistle','darkblue','darkcyan','darkgoldenrod','darkgray','darkgreen','darkkhaki','darkmagenta','darkolivegreen','darkorange','darkorchid','darkred','darksalmon','darkseagreen','darkslateblue','darkslategray','darkturquoise','darkviolet','deeppink','deepskyblue','dimgray','dodgerblue','firebrick','floralwhite','forestgreen','gainsboro','ghostwhite','gold','goldenrod','gray','greenyellow','honeydew','hotpink','indianred','ivory','khaki','lavender','lavenderblush','lawngreen','lemonchiffon','lightblue','lightcoral','lightcyan','lightgoldenrodyellow','lightgray','lightgreen','lightpink','lightsalmon','lightseagreen','lightskyblue','lightslategray','lightsteelblue','lightyellow','lime','linen','magenta','maroon','mediumaquamarine','mediumblue','mediumorchid','mediumpurple','mediumseagreen','mediumslateblue','mediumspringgreen','mediumturquoise','mediumvioletred','midnightblue','mintcream','mistyrose','moccasin','navajowhite','navy','oldlace','olive','olivedrab','orange','orangered','orchid','palegoldenrod','palegreen','paleturquoise','palevioletred','papayawhip','peachpuff','peru','pink','plum','powderblue','purple','red','rosybrown','royalblue','saddlebrown','salmon','sandybrown','seagreen','seashell','sienna','silver','skyblue','slateblue','slategray','snow','springgreen','steelblue','tan','teal','thistle','tomato','turquoise','violet','wheat','white','whitesmoke','yellow','yellowgreen'];
        $contador = 1;
        foreach ($teams as $team):
            $query = "SELECT 
        u.id AS id_user, 
        u.created_at AS creation_user, 
        fs.*, 
        CASE 
            WHEN fs.challenge_type = 'Challenge' THEN c.title
            WHEN fs.challenge_type = 'Machine' THEN CONCAT(m.name,' ',fs.type, ' Flag')
        END AS challenge_name,
        CASE 
            WHEN fs.challenge_type = 'Challenge' THEN c.points
            WHEN fs.challenge_type = 'Machine' THEN m.points
        END AS points
    FROM users u
    INNER JOIN (
        SELECT s.*
        FROM submissions s
        JOIN (
            SELECT submitted_flag, MIN(submission_time) AS earliest_submission_time
            FROM submissions
            WHERE is_correct = 1 AND team_name = '$team[team_name]'
            GROUP BY submitted_flag
        ) AS sub
        ON s.submitted_flag = sub.submitted_flag AND s.submission_time = sub.earliest_submission_time
        WHERE s.is_correct = 1 AND s.team_name = '$team[team_name]'
    ) AS fs
    ON u.team_name = fs.team_name
    LEFT JOIN challenges c 
    ON fs.challenge_id = c.id AND fs.challenge_type = 'Challenge'
    LEFT JOIN machines m 
    ON fs.challenge_id = m.id AND fs.challenge_type = 'Machine'
    ORDER BY fs.submission_time";
            $data = [];
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            // var_dump($data);
        ?>
            var s<?php echo $team['id']; ?> = {
                label: '<?php echo $team['team_name']; ?>',
                borderColor: '<?php echo $colors[$contador]; ?>',
                steppedLine: true,
                data: [
                    <?php if (!empty($data)): ?> {
                            x: '<?php echo $team['created_at']; ?>',
                            y: 0
                        },
                    <?php endif; ?>
                    <?php $points = 0;
                    foreach ($data as $d): $points += $d['points']; ?> {
                            x: '<?php echo $d['submission_time']; ?>',
                            y: <?php echo $points; ?>
                        },
                    <?php endforeach; ?>
                ]
            };
        <?php $contador++;
        endforeach; ?>


        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [<?php foreach ($teams as $team): ?>s<?php echo $team['id']; ?>, <?php endforeach; ?>]
            },
            options: {
                scales: {
                    yAxes: [{
                        type: 'linear'
                    }],
                    xAxes: [{
                        type: 'time',
                        distribution: '<?php
                                        if (isset($_GET['type']) && $_GET['type'] == 'series') {
                                            echo 'series';
                                        } else {
                                            echo 'linear';
                                        }
                                        ?>', //  series or linear
                        time: {
                            unit: 'minute',
                            displayFormats: {
                                minute: 'mm:ss'
                            },
                            tooltipFormat: 'mm:ss'
                        }
                    }]
                }
            }
        });
    </script>
</body>

</html>