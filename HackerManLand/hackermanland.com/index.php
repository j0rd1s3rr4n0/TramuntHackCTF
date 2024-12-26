<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HackerManLand</title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="./font-awesome/css/all.min.css">
    <style>
        video#bgvid {
            position: fixed;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -100;
            -ms-transform: translateX(-50%) translateY(-50%);
            -moz-transform: translateX(-50%) translateY(-50%);
            -webkit-transform: translateX(-50%) translateY(-50%);
            transform: translateX(-50%) translateY(-50%);
            background: url(hackerman.jpg) no-repeat;
            background-size: cover;
            opacity: 0.5;
        }

        .wal-text {
            outline: 0;
            text-align: center;
            font-family: Roboto, monospace
        }

        .wal-text,
        .wal-text * {
            animation: wal 6s infinite steps(50)
        }

        @keyframes wal {
            2% {
                font-weight: 700;
                font-style: normal;
                text-decoration: underline;
                text-transform: none
            }

            10%,
            4%,
            40%,
            6%,
            70%,
            96% {
                font-weight: 600;
                font-style: normal;
                text-decoration: none;
                text-transform: none
            }

            8% {
                font-weight: 700;
                font-style: normal;
                text-decoration: none;
                text-transform: none
            }

            12% {
                font-weight: 300;
                font-style: normal;
                text-decoration: line-through;
                text-transform: none
            }

            14%,
            16%,
            84% {
                font-weight: 200;
                font-style: normal;
                text-decoration: none;
                text-transform: none
            }

            18% {
                font-weight: 700;
                font-style: italic;
                text-decoration: underline;
                text-transform: capitalize
            }

            20%,
            26%,
            58%,
            90% {
                font-weight: 500;
                font-style: normal;
                text-decoration: none;
                text-transform: none
            }

            22%,
            50%,
            52% {
                font-weight: 400;
                font-style: normal;
                text-decoration: none;
                text-transform: none
            }

            24%,
            42%,
            60% {
                font-weight: 100;
                font-style: normal;
                text-decoration: none;
                text-transform: none
            }

            28% {
                font-weight: 200;
                font-style: normal;
                text-decoration: none;
                text-transform: capitalize
            }

            30% {
                font-weight: 300;
                font-style: normal;
                text-decoration: none;
                text-transform: none
            }

            32% {
                font-weight: 300;
                font-style: italic;
                text-decoration: none;
                text-transform: none
            }

            34%,
            72% {
                font-weight: 700;
                font-style: normal;
                text-decoration: none;
                text-transform: uppercase
            }

            36% {
                font-weight: 300;
                font-style: italic;
                text-decoration: none;
                text-transform: uppercase
            }

            38% {
                font-weight: 200;
                font-style: normal;
                text-decoration: underline;
                text-transform: none
            }

            44% {
                font-weight: 300;
                font-style: italic;
                text-decoration: none;
                text-transform: lowercase
            }

            46% {
                font-weight: 500;
                font-style: normal;
                text-decoration: none;
                text-transform: lowercase
            }

            48% {
                font-weight: 400;
                font-style: normal;
                text-decoration: none;
                text-transform: lowercase
            }

            54%,
            74%,
            80%,
            98% {
                font-weight: 600;
                font-style: italic;
                text-decoration: none;
                text-transform: none
            }

            56% {
                font-weight: 600;
                font-style: italic;
                text-decoration: none;
                text-transform: uppercase
            }

            62% {
                font-weight: 700;
                font-style: normal;
                text-decoration: line-through;
                text-transform: uppercase
            }

            64% {
                font-weight: 400;
                font-style: normal;
                text-decoration: line-through;
                text-transform: none
            }

            66% {
                font-weight: 100;
                font-style: normal;
                text-decoration: none;
                text-transform: uppercase
            }

            68% {
                font-weight: 500;
                font-style: normal;
                text-decoration: underline;
                text-transform: none
            }

            76% {
                font-weight: 300;
                font-style: normal;
                text-decoration: line-through;
                text-transform: lowercase
            }

            78% {
                font-weight: 100;
                font-style: normal;
                text-decoration: underline;
                text-transform: lowercase
            }

            82% {
                font-weight: 500;
                font-style: normal;
                text-decoration: line-through;
                text-transform: none
            }

            86% {
                font-weight: 300;
                font-style: normal;
                text-decoration: line-through;
                text-transform: uppercase
            }

            88% {
                font-weight: 700;
                font-style: normal;
                text-decoration: none;
                text-transform: lowercase
            }

            92% {
                font-weight: 400;
                font-style: italic;
                text-decoration: none;
                text-transform: none
            }

            94% {
                font-weight: 100;
                font-style: italic;
                text-decoration: none;
                text-transform: uppercase
            }
        }

        #icono {
            -webkit-transition: all 1.5s ease-in-out;
            -moz-transition: all 1.5s ease-in-out;
            -o-transition: all 1.5s ease-in-out;
            transition: all 1.5s ease-in-out;
        }

        .fadeout {
            background-color: #2a2a2a !important;
            opacity: 0;
            -webkit-transition: opacity 3s ease-in-out;
            -moz-transition: opacity 3s ease-in-out;
            -ms-transition: opacity 3s ease-in-out;
            -o-transition: opacity 3s ease-in-out;
        }

        .center {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-right: -50%;
            transform: translate(-50%, -50%);
        }

        .center pre {
            color: #ffffff;
            text-align: center;
            text-shadow: 0 0 1px #ff0000;
        }

        pre a {
            color: #ffffff;
            font-style: italic;
            font-weight: bold;
        }

        .center img {
            display: block;
            margin: 0 auto;
        }

        .floating {
            animation-name: floating;
            -webkit-animation-name: floating;
            animation-duration: 1.5s;
            -webkit-animation-duration: 1.5s;
            animation-iteration-count: infinite;
            -webkit-animation-iteration-count: infinite;
        }

        @keyframes floating {
            0% {
                transform: scale(0.9);
                background: none;
            }

            25% {
                transform: scale(1);
            }

            50% {
                transform: scale(0.9);
                background: none;
            }

            75% {
                transform: scale(1);
            }

            100% {
                transform: scale(0.9);
            }
        }

        @-webkit-keyframes floating {
            0% {
                transform: scale(0.9);
            }

            25% {
                transform: scale(1);
            }

            50% {
                transform: scale(0.9);
            }

            75% {
                transform: scale(1);
            }

            100% {
                transform: scale(0.9);
            }
        }

        .vistr {
            padding: 80px;
            background-color: rgb(0, 0, 0, 0.1);
            text-align: center;
            border-radius: 500px 500px 500px 500px;
            /*border: 20px rgb(255,255,255,0.2) solid;*/
        }
        #logo{
            padding: 0;
            max-height: 150pt;
            margin: -30pt auto !important;
        }
    </style>
</head>

<body style="background-color: black;" id="cuerpo"><video playsinline="" autoplay="autoplay" loop=""
        poster="hackerman.jpg" onmouseout="video.play();" onload="video.play();" id="bgvid">
        <source src="hackermanclip.mp4" type="video/mp4">
    </video>
    <div class="center">
        <div class="vistr">
            <div id="icono">
                <img id="logo" src='hackerman.png' class="floating" style="padding: 5px"></div>
            <div>
                <pre class="wal-text">HackerManLand</pre>
                <pre> [ <a href="#" id="pause"><i class="fa fa-play" aria-hidden="true"></i></a> / <a href="#" id="mute"><i class="fa fa-volume-off" aria-hidden="true"></i></a> ]</pre>
                <pre>Our Projects:</pre>
                <pre>[ <a href="/accounts/">Premium Account Generator</a> / <a href="/leakup/">LeakFinder</a> / <a href="/botnet/">Rent a Botnet</a> ]</pre>
                <pre>Members</pre>
                <pre>[ <a href="#">T2uh3</a> / <a href="#">hackerMan</a> / <a href="#">RobotMR</a> ]</pre>
                <pre>Contact us:</pre>
                <pre>[<a href="mailito:asdahsdkjhaskdhaksdhasdasd@pm.me" target="_blank">Email</a> | <a href="http://fg99di3121jg6rue2252oqsxfryouxe3ngawnmo4e62qy4kyii5wtqnwfj4ooad.onion/IRC/" target="_blank">IRC</a> ]</pre>
            </div>
        </div>
    </div>
    <script src="wal.js"></script>
    <footer>
        <?php include 'contador.php';?>
    </footer>
</body>

</html>