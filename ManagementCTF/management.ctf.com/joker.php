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



// if $_POST['challenge_id'] and $_POST['flag'] are set and optional difficulty
if (isset($_POST['flag'])) {

    // If exist difficulty set it to post on db
    if (isset($_POST['difficulty'])) {
        $difficulty = $_POST['difficulty'];
    } else {
        $difficulty = '';
    }

    // Get the challenge id and flag eviting sql injection or xss or any other attack
    $flag = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['flag']));

    // Find in the table challenges the challenge_id of the challenge who has the flag submitted
    $stmt = $conn->prepare("SELECT id FROM challenges WHERE flag = ?");
    $stmt->bind_param("s", $_POST['flag']);
    $stmt->execute();
    $flag_result_query = $stmt->get_result();
    $stmt->close();  
    
    if ($flag_result_query->num_rows > 0) {
        $challenge_id = $flag_result_query->fetch_assoc()['id'];
    } else {
        $challenge_id = 0;
        $submission_time = date('Y-m-d H:i:s');
        $conn->query("INSERT INTO submissions (team_name, challenge_type, challenge_id, submitted_flag, is_correct, submission_time,type) VALUES ('" . $_SESSION['team_name'] . "', 'Challenge', $challenge_id, '$flag', 0, '$submission_time','easteregg')");
        $conn->close();
        die(json_encode(['success' => false, 'message' => 'Invalid flag']));
    }



    
    

    // Look in submissions table finding if the flag is already submitted with value true 
    $query = "SELECT * FROM submissions WHERE challenge_type = 'Joker' AND challenge_id = $challenge_id AND submitted_flag = '$flag' AND team_name = '" . $_SESSION['team_name'] . "' AND is_correct = 1";

    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        die(json_encode(['success' => false, 'message' => 'Flag already submitted']));
    }

    $challenge = $conn->query("SELECT * FROM challenges WHERE id = $challenge_id")->fetch_assoc();
    $submission_time = date('Y-m-d H:i:s');
    if ($challenge['flag'] === $flag) {
        $conn->query("INSERT INTO submissions (team_name, challenge_type, challenge_id, submitted_flag, is_correct, submission_time,type) VALUES ('" . $_SESSION['team_name'] . "', 'Challenge', $challenge_id, '$flag', 1, '$submission_time','easteregg')");
        die(json_encode(['success' => true, 'points' => $challenge['points']]));
    }

    $conn->query("INSERT INTO submissions (team_name, challenge_type, challenge_id, submitted_flag, is_correct, submission_time,type) VALUES ('" . $_SESSION['team_name'] . "', 'Challenge', $challenge_id, '$flag', 0, '$submission_time','easteregg')");
    die(json_encode(['success' => false]));
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Easter Eggs Sender!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Your Flag</title>
    <link rel="stylesheet" href="css/bootstrap4-neon-glow.min.css">
    <link rel="stylesheet" href="css/joker.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <ctf>2613d5335779564f3dd7c44e5b6bebdb</ctf>
    <link rel='stylesheet' href='//cdn.jsdelivr.net/font-hack/2.020/css/hack.min.css'>

</head>

<body>
    <h1>Easter Eggs Sender!</h1>
    <h4>Submit All of the 6 Flag hidden on the pages</h4>
    <input type="hidden" name="ee2" value="CTF{26cff0571c90ee821fe50a106e2f4d73}">
    <form method="POST">
        <input type="text" name="flag" placeholder="Enter your flag" required>
        <br>
        <button type="submit">Submit</button>
    </form>
    <?php if (isset($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <script src="js/joker.js"></script>
</body>

</html>