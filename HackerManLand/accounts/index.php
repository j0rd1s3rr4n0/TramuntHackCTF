<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Premium Account Generator</h1>
        <div class="buttons">
            <button class="gen-btn" onclick="generateAccount('Spotify')">Spotify</button>
            <button class="gen-btn" onclick="generateAccount('Netflix')">Netflix</button>
            <button class="gen-btn" onclick="generateAccount('TikTok')">TikTok</button>
            <button class="gen-btn" onclick="generateAccount('Instagram')">Instagram</button>
            <button class="gen-btn" onclick="generateAccount('SoundCloud')">SoundCloud</button>
            <button class="gen-btn" onclick="generateAccount('Riot Games')">Riot Games</button>
            <button class="gen-btn" onclick="generateAccount('Epic Games')">Epic Games</button>
            <button class="gen-btn" onclick="generateAccount('Steam')">Steam</button>
        </div>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span id="close" class="close">&times;</span>
            <h2>Generated Account</h2>
            <p><strong>Email:</strong> <span id="email"></span></p>
            <p><strong>Password:</strong> <span id="password"></span></p>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
