<?php
session_start();
// Generate a random response code between 400 and 499
$randomResponseCode = rand(400, 499);
http_response_code($randomResponseCode);
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
    <link rel="stylesheet" href="css/main.css">
</head>

<body class="imgloaded" style="background-color:#0b120d!important;">
    <div class="glitch">
        <div class="glitch__img glitch__img_404"></div>
        <div class="glitch__img glitch__img_404"></div>
        <div class="glitch__img glitch__img_404"></div>
        <div class="glitch__img glitch__img_404"></div>
        <div class="glitch__img glitch__img_404"></div>
    </div>
    <div class="navbar-dark text-white">
        <div class="container">
            <nav class="navbar px-0 navbar-expand-lg navbar-dark">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a href="index.php" class="pl-md-0 p-3 text-decoration-none text-light">
                            <h3 class="bold"><h3 class="bold"> <img src="images/tramunthack.png" alt="CTF" style="height: 8rem;"> <span class="color_danger">TRAMUNTHACK</span><span class="color_white">CTF</span></h3>
                        </a>
                    </div>
                    <?php include_once 'includes/menu.php'; ?>
                </div>
            </nav>
        </div>
    </div>
    <div class="jumbotron bg-transparent mb-0 pt-5 radius-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 text-center">
                    <h1 style="background-color:#000000A4;" class="py-5 display-1 bold color_white content__title">404 N07 F0UND<span class="vim-caret">&nbsp;</span></h1>
                </div>
            </div>
        </div>
    </div>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>
<?php
// Generate a random number to append to the page size
$randomSize = rand(1, 1000);
?>

<style>
    body::after {
        content: "<?php echo str_repeat(' ', $randomSize); ?>";
        display: block;
        height: 0;
        overflow: hidden;
    }
</style>
