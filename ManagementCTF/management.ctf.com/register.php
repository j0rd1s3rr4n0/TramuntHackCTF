<?php ob_start(); ?>
<?php session_start();?>
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
    <link rel="stylesheet" href="css/main.css">
</head>

<body class="imgloaded">
    <div class="glitch">
        <div class="glitch__img glitch__img_register"></div>
        <div class="glitch__img glitch__img_register"></div>
        <div class="glitch__img glitch__img_register"></div>
        <div class="glitch__img glitch__img_register"></div>
        <div class="glitch__img glitch__img_register"></div>
    </div>
    <?php include_once 'includes/menu.php'; ?>

    <div class="jumbotron bg-transparent mb-0 pt-3 radius-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-10">
                    <h1 class="display-1 bold color_white content__title">REGISTER<span class="vim-caret">&nbsp;</span></h1>
                    <p class="text-grey text-spacey hackerFont lead mb-5">
                        Join the community and be part of the future of the information security industry.
                    </p>
                    <div class="row hackerFont">
                        <div class="col-md-6">
                            <form method="POST" action="register.php">
                                <!-- Reciept ID -->
                                <div class="form-group">
                                    <input type="text" class="form-control" name="reciept_id" id="reciept_id" value="<?php if(isset($_POST['reciept_id'])){echo htmlspecialchars($_POST['reciept_id'], ENT_QUOTES, 'UTF-8');}?>" placeholder="RecieptId(ex. AAAA-BBBB-CCCC-DDDD-123)">
                                    <small id="reciept_id_help" class="form-text text-muted">Don't have reciept id? Register <a target="_blank" href="#Really?You?are?super?hacker?!#Try#Something">here</a></small>
                                </div>
                                <!-- Team Name -->
                                <div class="form-group">
                                    <input type="text" class="form-control" name="team_name" id="team_name" value="<?php if(isset($_POST['team_name'])){echo htmlspecialchars($_POST['team_name'], ENT_QUOTES, 'UTF-8');}?>" placeholder="Team name">
                                </div>
                                <!-- Password -->
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" id="password" value="<?php if(isset($_POST['password'])){echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');}?>" placeholder="New Password">
                                    <small id="passHelp" class="form-text text-muted">Make sure nobody's behind you</small>
                                </div>
                                <!-- Checkbox: Solemn Oath -->
                                <div class="custom-control custom-checkbox my-4">
                                    <input type="checkbox" class="custom-control-input" name="solemnly_swear" id="solemnly-swear" <?php if(isset($_POST['solemnly_swear']) && $_POST['solemnly_swear']==1){echo 'checked="checked"';}?>>
                                    <label class="custom-control-label" for="solemnly-swear">I solemnly swear that I am up to no good.</label>
                                </div>
                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-outline-danger btn-shadow px-3 my-2 ml-0 ml-sm-1 text-left typewriter">
                                    <h4>Register</h4>
                                </button>
                            </form>
                            <span class="text-danger">
                                <?php
                                // Función para cargar el archivo .env
                                function loadEnv($file)
                                {
                                    if (file_exists($file)) {
                                        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                                        foreach ($lines as $line) {
                                            // Ignorar comentarios
                                            if (strpos($line, '#') === 0) {
                                                continue;
                                            }

                                            // Dividir la línea en clave y valor
                                            list($key, $value) = explode('=', $line, 2);
                                            $key = trim($key);
                                            $value = trim($value);

                                            // Establecer las variables de entorno
                                            putenv("{$key}={$value}");
                                            $_ENV[$key] = $value;
                                        }
                                    }
                                }

                                // Cargar el archivo .env
                                $dotenv_path = __DIR__ . '/../.env';
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

                                $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
                                $dbusername = $_ENV['DB_USERNAME'] ?? 'user';
                                $dbpassword = $_ENV['DB_PASSWORD'] ?? 'defaultpassword';
                                $dbname = $_ENV['DB_DATABASE'] ?? 'accounts';

                                // Inicializar las variables de error y éxito
                                $error_message = '';
                                $success_message = '';

                                // Verifica si el formulario ha sido enviado
                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                    // Recoger los datos del formulario
                                    $reciept_id = $_POST['reciept_id'];
                                    $team_name = $_POST['team_name'];
                                    $password = $_POST['password'];
                                    $solemnly_swear = isset($_POST['solemnly_swear']) ? 1 : 0;

                                    if ($solemnly_swear == 0) {
                                        $error_message = 'You must solemnly swear that you have no good intentions.';
                                        die($error_message);
                                    }

                                    // Validar el formato del reciept_id (debe ser 4 grupos de letras seguidos de 3 números)
                                    if (!preg_match('/^[A-Z]{4}-[A-Z]{4}-[A-Z]{4}-[A-Z]{4}-\d{3}$/', $reciept_id)) {
                                        $error_message = 'The RecieptId format is invalid. It must have the format: ABCD-DEFG-HIJK-LMNO-123';
                                    }

                                    // Validar los datos
                                    if (empty($reciept_id) || empty($team_name) || empty($password)) {
                                        $error_message = 'Todos los campos son obligatorios.';
                                    } else {
                                        // Conectar a la base de datos
                                        try {
                                            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
                                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                            // Comprobar si ya existe el recibo
                                            $stmt = $pdo->prepare("SELECT * FROM users WHERE reciept_id = :reciept_id");
                                            $stmt->execute(['reciept_id' => $reciept_id]);
                                            if ($stmt->rowCount() > 0) {
                                                $error_message = 'Este recibo ya está registrado.';
                                            } else {
                                                // Insertar nuevo usuario
                                                $stmt = $pdo->prepare("INSERT INTO users (reciept_id, team_name, password, solemnly_swear) 
                                       VALUES (:reciept_id, :team_name, :password, :solemnly_swear)");
                                                $stmt->execute([
                                                    'reciept_id' => $reciept_id,
                                                    'team_name' => $team_name,
                                                    'password' => password_hash($password, PASSWORD_DEFAULT),
                                                    'solemnly_swear' => $solemnly_swear
                                                ]);
                                                $success_message = 'Registro exitoso. Por favor, inicie sesión.';
                                            }
                                        } catch (PDOException $e) {
                                            error_log('Database connection error: ' . $e->getMessage());
                                            $error_message = 'An error occurred while processing your request. Please try again later.';
                                        }
                                        if ($success_message != '') {
                                            header('Location: login.php', true, 301);
                                        }
                                        if ($error_message != '') {
                                            error_log($error_message); // Log the error internally
                                            echo 'An error occurred. Please try again later.'; // Display a generic error message to the user
                                        }
                                    }
                                }
                                ?>
                            </span>
                            <small id="registerHelp" class="mt-2 form-text text-muted">Already Registered? <a href="login.php">Login here</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>
<?php ob_end_flush(); ?>