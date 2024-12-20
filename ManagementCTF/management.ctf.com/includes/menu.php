<?php header('Content-Type: text/html; charset=utf-8'); ?>
<div class="navbar-dark text-white">
    <div class="container">
        <nav class="navbar px-0 py-0 navbar-expand-lg navbar-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a href="index.php" class="pl-md-0 p-3 text-decoration-none text-light">
                        <h3 class="bold"> <img src="images/tramunthack.png" alt="CTF" style="height: 8rem;"> <span class="color_danger">TRAMUNTHACK</span><span class="color_white">CTF</span></h3>
                    </a>
                </div>
                <div class="navbar-nav ml-auto">
                    <a href="index.php" class="p-3 text-decoration-none text-white bold">Home</a>
                    <?php
                    if (isset($_SESSION['team_name'])) {
                        echo '<a href="instructions.php" class="p-3 text-decoration-none text-light bold">Instructions</a>';
                    } else {
                    ?>
                        <a href="about.php" class="p-3 text-decoration-none text-light bold">About</a>
                    <?php } ?>
                    <a href="hackerboard.php" class="p-3 text-decoration-none text-light bold">Hackerboard</a>
                    <?php
                    if (isset($_SESSION['team_name'])) {
                        echo '<a href="quests.php" class="p-3 text-decoration-none text-light bold">Quests</a>';
                    } 
                    if (isset($_SESSION['team_name'])) {
                        echo '<a class="p-3 text-decoration-none text-light bold" style="color:#acacff!important;">' . $_SESSION['team_name'] . '</a>';
                    } else {
                    ?>
                        <a href="login.php" class="p-3 text-decoration-none text-light bold">Login</a>
                        <?php
                    }
                    ?>
                    <?php
                    if (isset($_SESSION['team_name'])) {
                        echo '<a href="logout.php" class="p-3 text-decoration-none text-light bold">Logout</a>';
                    } else {
                    ?>
                        <a href="register.php" class="p-3 text-decoration-none text-light bold">Register</a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </nav>
    </div>
</div>
