<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leak Searcher</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Recent Leaks</h1>
            <p>Check if your accounts have been compromised.</p>
        </header>
        <section class="search-container">
            <form id="searchForm">
                <input type="email" id="emailInput" placeholder="Enter email to search" required>
                <button type="submit">Search Leaks</button>
            </form>
        </section>

        <section class="results-container">
            <p id="resultMessage"></p>
            <table id="leaksTable">
                <thead>
                    <tr>
                        <th>Email/Username</th>
                        <th>Password</th>
                        <th>IP</th>
                        <th>Date/Time</th>
                        <th>Location</th>
                        <th>Device</th>
                        <th>Authentication Method</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be generated here -->
                </tbody>
            </table>
        </section>
    </div>

    <script src="script.js"></script>
</body>
</html>
